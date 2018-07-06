<?php

namespace ApiBundle\EventListener;

use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Service\AdminPanel\UsersManagementService;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\TranslatorInterface;
use WebSocketsBundle\Service\NotificationService;

class RequestListener
{
    /** @var TokenStorage */
    protected $tokenStorage;

    /** @var TranslatorInterface */
    protected $translator;

    /** @var NotificationService */
    protected $alert;

    public function __construct(TokenStorage $tokenStorage, TranslatorInterface $translator, NotificationService $alert)
    {
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->alert = $alert;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();
        $session = $request->getSession();

        if(!empty($session->get('locale'))) {
            $request->setLocale($session->get('locale'));
        }

        $now = new \DateTime();
        $token =  $this->tokenStorage->getToken();

        if(!$token) {
            return null;
        }

        $user = $token->getUser();

        if(!$user || !($user instanceof AbstractUser) || !$user->getCompany()) {
            return;
        }

        if($user->getCompany()->getPlanPayedUntil() < $now || $user->getCompany()->getPlan() == null)
        {
            //TODO: allow only payment routes.
//            throw new AccessDeniedHttpException($this->translator->trans('subscription_has_expired'));
        }
        $session->set('time_zone', $user->getCompany()->getTimeZone());

        $this->alert->check_ip($user, $request->getClientIp());
    }
}