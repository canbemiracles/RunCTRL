<?php

namespace ApiBundle\Service\User;

use ApiBundle\Entity\OAuth\AccessToken;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\BranchManager;
use ApiBundle\Entity\User\Supervisor;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Mailer\TwigSwiftMailer;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Util\TokenGenerator;
use Guzzle\Common\Collection;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Created by PhpStorm.
 * User: mail
 * Date: 16.11.2017
 * Time: 13:31
 */

class AccountConfirmation
{

    /** @var EntityManager */
    protected $entityManager;

    /** @var TwigSwiftMailer */
    protected $mailer;

    /** @var \Swift_Mailer*/
    protected $mailerMessage;

    /** @var Collection string*/
    protected $data;

    /** @var UserManager*/
    protected $userManager;

    /** @var TwigEngine */
    protected $templating;

    /** @var Router*/
    protected $router;

    public function __construct(EntityManager $entityManager, TwigSwiftMailer $mailer, \Swift_Mailer $swift_Mailer, UserManager $userManager,
                                TwigEngine $templating, Router $router, $data)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->mailerMessage = $swift_Mailer;
        $this->userManager = $userManager;
        $this->templating = $templating;
        $this->router = $router;
        $this->data = $data;
    }

    public function resendActivationLink(string $email)
    {
        if(!$email) {
            throw new BadRequestHttpException('Email is not valid');
        }

        /** @var AbstractUser $user */
        $user = $this->entityManager->getRepository('ApiBundle:User\AbstractUser')->findOneBy(['email' => $email]);

        if(!$user) {
            throw new BadRequestHttpException('User not found');
        }

        if($user->getConfirmationToken() == null) {
            throw new BadRequestHttpException('You can\'t resend confirmation message');
        }

        $this->mailer->sendConfirmationEmailMessage($user);

        return new JsonResponse(['message' => 'Email confirmation successfully sent']);
    }

    public function resendEmailInvite(string $email, $token, $branch)
    {
        if(!$email) {
            throw new BadRequestHttpException('Email is not valid');
        }

        /** @var AbstractUser $user */
        $user = $this->entityManager->getRepository('ApiBundle:User\AbstractUser')->findOneBy(['email' => $email]);

        if(!$user) {
            throw new BadRequestHttpException('User not found');
        }

        foreach($user->getAccessTokens() as $token) {
            /** @var $token AccessToken*/
            if($token->getExpiresAt() - time() > 0) {
                throw new BadRequestHttpException('You can\'t resend confirmation message');
            }
        }

        if($user->getConfirmationToken() == null) {
            $user->setConfirmationToken($token);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        if($user instanceof BranchManager) {
            $type_user = 'branch_managers';
        } else {
            $type_user = $user instanceof Supervisor ? 'supervisors' : '';
        }

        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $this->mailerMessage->send(
            (new \Swift_Message("Welcome {$email}!"))
                ->setFrom($this->data['mailer_sender_address'])
                ->setTo($email)
                ->setBody(
                    $this->templating->render(
                        'email_confirmation_invite.html.twig',
                        array('user' => $user, 'company' => $user->getCompanyId(), 'frontend_url' => $this->data['frontend_url'],
                            'type_user' => $type_user, 'confirmationUrl' => $url, 'branch' => $branch, 'system_email' => $this->data['system_email'])
                    ),
                    'text/html')
        );

        return new JsonResponse(['message' => 'Email confirmation successfully sent']);
    }

    public function sendConfirmationEmailInvite(string $email, $branch)
    {
        if(!$email) {
            throw new BadRequestHttpException('Email is not valid');
        }

        /** @var AbstractUser $user */
        $user = $this->entityManager->getRepository('ApiBundle:User\AbstractUser')->findOneBy(['email' => $email]);

        if(!$user) {
            throw new BadRequestHttpException('User not found');
        }

        if($user instanceof BranchManager) {
            $type_user = 'branch_managers';
        } else {
            $type_user = $user instanceof Supervisor ? 'supervisors' : '';
        }

        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $this->mailerMessage->send(
            (new \Swift_Message("Welcome {$email}!"))
                ->setFrom($this->data['mailer_sender_address'])
                ->setTo($email)
                ->setBody(
                    $this->templating->render(
                        'email_confirmation_invite.html.twig',
                        array('user' => $user, 'company' => $user->getCompanyId(), 'frontend_url' => $this->data['frontend_url'],
                            'type_user' => $type_user, 'confirmationUrl' => $url, 'branch' => $branch, 'system_email' => $this->data['system_email'])
                    ),
                    'text/html')
        );

        return new JsonResponse(['message' => 'Email confirmation successfully sent']);
    }

    public function password_resetting($user) {
        $this->mailer->sendResettingEmailMessage($user);
    }

    public function generatePasswordEmail(string $email)
    {
        if(!$email) {
            throw new BadRequestHttpException('Email is not valid');
        }

        /** @var AbstractUser $user */
        $user = $this->entityManager->getRepository('ApiBundle:User\AbstractUser')->findOneBy(['email' => $email]);

        if(!$user) {
            throw new BadRequestHttpException('User not found');
        }

        $generate_password = $this->generatePassword();
        $user->setPlainPassword($generate_password);
        $this->userManager->updateUser($user);

        $this->mailerMessage->send(
            (new \Swift_Message('New password'))
            ->setFrom($this->data['mailer_sender_address'])
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                        'new_password.email.twig',
                        array('password' => $generate_password, 'user' => $user, 'system_email' => $this->data['system_email'])
                    ),
                'text/html')
        );

        return new JsonResponse(['message' => 'A new password email sent successfully']);
    }

    public function generatePassword($length = 10)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}