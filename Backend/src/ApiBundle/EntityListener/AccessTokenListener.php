<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 04.10.2017
 * Time: 17:06
 */

namespace ApiBundle\EntityListener;


use ApiBundle\Entity\OAuth\AccessToken;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\Owner;
use ApiBundle\Service\Security\LoginHistory;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AccessTokenListener
{
    /** @var RequestStack */
    protected $requestStack;

    /** @var LoginHistory */
    protected $loginHistory;

    /** @var GoogleAuthenticator */
    protected $googleAuthenticator;

    public function __construct(RequestStack $requestStack, LoginHistory $loginHistory, GoogleAuthenticator $googleAuthenticator)
    {
        $this->requestStack = $requestStack;
        $this->loginHistory = $loginHistory;
        $this->googleAuthenticator = $googleAuthenticator;
    }

    /**
     * @param AccessToken $token
     * @param LifecycleEventArgs $event
     */
    public function prePersist(AccessToken $token, LifecycleEventArgs $event)
    {
        /** @var $user AbstractUser*/
        $user = $token->getUser();

        $request = $this->requestStack->getCurrentRequest();

        if(!$user || !$request) {
            return;
        }

        if(!empty($user->getGoogleAuthenticatorSecret())
            && $this->requestStack->getCurrentRequest()->get('grant_type') === "password") {
            if(!empty($request->query->get('code'))) {
                if(!$this->googleAuthenticator->checkCode($user, $request->query->get('code'))) {
                    throw new BadRequestHttpException("Google Authentication failed");
                }
            } else {
                echo \GuzzleHttp\json_encode(array('message' => 'You must specify a code for google authenticator', 'user_id' => $user->getId())); exit();
            }
        }

        //Setting locale to user
        if (null !== $user->getLocale()) {
            $request->getSession()->set('locale', $user->getLocale());
        }

        //Logging user auth
        $this->loginHistory->logToHistory($user, $request->getClientIp());

    }
}