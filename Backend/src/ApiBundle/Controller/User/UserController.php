<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 27.10.2017
 * Time: 16:16
 */

namespace ApiBundle\Controller\User;

use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Repository\User\AbstractUserRepository;
use ApiBundle\Service\Manager\TokenManager;
use ApiBundle\Service\User\AccountConfirmation;
use FOS\RestBundle\Controller\Annotations as Rest;
use ApiBundle\Controller\AbstractController;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;

/**
 * User controller.
 *
 * @Route("users")
 */
class UserController extends AbstractController
{
    /**
     * @ApiDoc(
     *     description = "Get current user",
     *     section="User",
     *     output="ApiBundle\Entity\User",
     * )
     * @Rest\Get("/current", name="get_current_user")
     * @Rest\View()
     * @return AbstractUser
     */
    public function getCurrentUserAction()
    {
        return $this->getUser();
    }

    /**
     * @Rest\QueryParam(name="token", nullable=false)
     * @ApiDoc(
     *     description = "Get current user",
     *     section="User",
     *     output="ApiBundle\Entity\User",
     * )
     * @Rest\Get("/current_invite", name="get_current_user_invite")
     * @param ParamFetcher $paramFetcher
     * @Rest\View()
     * @return mixed
     */
    public function getCurrentUserInviteAction(ParamFetcher $paramFetcher)
    {
        /** @var $user_repository AbstractUserRepository*/
        $user_repository = $this->getDoctrine()->getRepository("ApiBundle:User\AbstractUser");
        $token = $paramFetcher->get('token');
        return $user_repository->findOneBy(['confirmationToken' => $token]);
    }

    public function confirmedAction()
    {
        $tokenStorage = $this->get('security.token_storage');
        $token = $tokenStorage->getToken();

        if(!$token) {
            return;
        }

        /** @var $tokenManager TokenManager */
        $tokenManager = $this->get('service.token_manager');

        $response = $tokenManager->grantToken($token->getUser());

        return new JsonResponse($response);
    }

    public function emailSentAction()
    {
        return new JsonResponse(['message' => 'E-mail successfully sent']);
    }

    public function resettingSuccessAction()
    {
        return new JsonResponse(['message' => 'Password successfully changed']);
    }

    /**
     * @Rest\RequestParam(name="email", allowBlank=false)
     * @ApiDoc(
     *      description = "Resends confirmation mail for user",
     *      section = "User"
     * )
     * @Rest\Post("/resend_confirmation")
     * @Rest\View()
     */
    public function resendConfirmationMail(ParamFetcher $paramFetcher)
    {
        $email = $paramFetcher->get('email');

        /** @var AccountConfirmation $accountConfirmationService */
        $accountConfirmationService = $this->get('service.account_confirmation');

        return $accountConfirmationService->resendActivationLink($email);
    }

    /**
     * @Rest\QueryParam(name="branch", nullable=false)
     * @Rest\RequestParam(name="email", allowBlank=false)
     * @ApiDoc(
     *      description = "Resends confirmation mail for user",
     *      section = "User"
     * )
     * @Rest\Post("/resend_invite_confirmation")
     * @Rest\View()
     */
    public function resendConfirmationMailInvite(ParamFetcher $paramFetcher)
    {
        $tokenGenerator = $this->get('fos_user.util.token_generator');
        $email = $paramFetcher->get('email');

        /** @var AccountConfirmation $accountConfirmationService */
        $accountConfirmationService = $this->get('service.account_confirmation');

        return $accountConfirmationService->resendEmailInvite($email, $tokenGenerator->generateToken(), $paramFetcher->get('branch'));
    }

    /**
     * @Rest\RequestParam(name="email", allowBlank=false)
     * @ApiDoc(
     *      description = "Generate password and send to mail user",
     *      section = "User"
     * )
     * @Rest\Post("/forgot_password")
     * @Rest\View()
     */
    public function generatePasswordAction(ParamFetcher $paramFetcher)
    {
        $email = $paramFetcher->get('email');

        /** @var AccountConfirmation $accountConfirmationService */
        $accountConfirmationService = $this->get('service.account_confirmation');

        return $accountConfirmationService->generatePasswordEmail($email);
    }

    /**
     * @ApiDoc(
     *      description = "edit Newsletter Notification",
     *      section = "User"
     * )
     * @Rest\Patch("/edit_newsletter", name="user_edit_newsletter")
     * @param Request $request
     * @Rest\View()
     * @return mixed
     */
    public function editNewsletter(Request $request)
    {
        $user = $this->getUser();

        if($user instanceof AbstractUser) {
            if(!is_null($request->get('newsletter_notification_alert'))) {
                $user->setNewsletterNotificationAlert($request->get('newsletter_notification_alert'));
            }
            if(!is_null($request->get('newsletter_notification_announcement'))) {
                $user->setNewsletterNotificationAnnouncement($request->get('newsletter_notification_announcement'));
            }
            if(!is_null($request->get('newsletter_notification_report'))) {
                $user->setNewsletterNotificationReport($request->get('newsletter_notification_report'));
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $user;
        }

        return new BadRequestHttpException($this->get('translator')->trans("user_not_found"));
    }

    /**
     * @ApiDoc(
     *  section="User",
     *  description="generate secret key for for Google Authenticator.",
     *  output="ApiBundle\Entity\User\AbstractUser",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/{id}/generate_secret", name="user_generate_secret")
     * @param AbstractUser $user
     * @Rest\View()
     * @return mixed
     */
    public function generateSecretAction(AbstractUser $user)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $google_authenticator GoogleAuthenticator*/
        $google_authenticator = $this->get("scheb_two_factor.security.google_authenticator");
        $secret = $google_authenticator->generateSecret();

        $user->setGoogleAuthenticatorSecret($secret);
        $em->persist($user);
        $em->flush();

        return $secret;
    }

    /**
     * @ApiDoc(
     *  section="User",
     *  description="delete secret key for for Google Authenticator.",
     *  output="ApiBundle\Entity\User\AbstractUser",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/{id}/delete_secret", name="user_delete_secret")
     * @param AbstractUser $user
     * @Rest\View()
     * @return mixed
     */
    public function deleteSecretAction(AbstractUser $user)
    {
        $em = $this->getDoctrine()->getManager();
        $user->setGoogleAuthenticatorSecret(null);
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @ApiDoc(
     *  section="User",
     *  description="generate secret key for for Google Authenticator.",
     *  output="ApiBundle\Entity\User\AbstractUser",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/{id}/qr_code", name="user_get_qr_code")
     * @param AbstractUser $user
     * @Rest\View()
     * @return mixed
     */
    public function getQRAction(AbstractUser $user)
    {
        /** @var $google_authenticator GoogleAuthenticator*/
        $google_authenticator = $this->get("scheb_two_factor.security.google_authenticator");
        return $google_authenticator->getUrl($user);
    }

    /**
     * @ApiDoc(
     *  section="User",
     *  description="Validates the code, which was entered by the user",
     *  output="ApiBundle\Entity\User\AbstractUser",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/{id}/check_code", name="user_check_code")
     * @param AbstractUser $user
     * @param Request $request
     * @Rest\View()
     * @return mixed
     */
    public function checkCodeAction(AbstractUser $user, Request $request)
    {
        /** @var $google_authenticator GoogleAuthenticator*/
        $google_authenticator = $this->get("scheb_two_factor.security.google_authenticator");
        return $google_authenticator->checkCode($user, $request->get('code'));
    }
}