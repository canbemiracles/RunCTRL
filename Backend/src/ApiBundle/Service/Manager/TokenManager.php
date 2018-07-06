<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 02.11.2017
 * Time: 14:09
 */

namespace ApiBundle\Service\Manager;


use ApiBundle\Entity\OAuth\AccessToken;
use ApiBundle\Entity\OAuth\Client;
use ApiBundle\Entity\OAuth\RefreshToken;
use ApiBundle\Entity\User\AbstractUser;
use Doctrine\ORM\EntityManager;
use FOS\OAuthServerBundle\Entity\AccessTokenManager;

class TokenManager
{

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  AccessTokenManager */
    protected $accessTokenManager;

    public function __construct(EntityManager $entityManager, AccessTokenManager $accessTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->accessTokenManager = $accessTokenManager;
    }

    public function grantToken(AbstractUser $user)
    {
        if(!$user) {
            return null;
        }

        $em = $this->entityManager;

        /** @var $client Client*/
        $client = $em->getRepository('ApiBundle:OAuth\Client')->findOneBy([]);

        $accessTokenLifeTime = new \DateTime('+1 hour');
        $refreshTokenLifeTime = new \DateTime('+1 week');

        /** @var AccessToken */
        $token = $this->accessTokenManager->createToken();

        $token->setUser($user);
        $token->setToken($this->genAccessToken());
        $token->setExpiresAt($accessTokenLifeTime->getTimestamp());
        $token->setClient($client);

        $em->persist($token);

        $refreshToken = new RefreshToken();

        $refreshToken->setUser($user);
        $refreshToken->setToken($this->genAccessToken());
        $refreshToken->setExpiresAt($refreshTokenLifeTime->getTimestamp());
        $refreshToken->setClient($client);

        $em->persist($refreshToken);

        $em->flush();

        return [
            'access_token' => $token->getToken(),
            'refresh_token' => $refreshToken->getToken(),
        ];
    }

    /**
     * Generates an unique access token.
     *
     * @return string An unique access token.
     *
     * @ingroup oauth2_section_4
     * @see     OAuth2::genAuthCode()
     */
    protected function genAccessToken()
    {
        if (@file_exists('/dev/urandom')) { // Get 100 bytes of random data
            $randomData = file_get_contents('/dev/urandom', false, null, 0, 100);
        } elseif (function_exists('openssl_random_pseudo_bytes')) { // Get 100 bytes of pseudo-random data
            $bytes = openssl_random_pseudo_bytes(100, $strong);
            if (true === $strong && false !== $bytes) {
                $randomData = $bytes;
            }
        }
        // Last resort: mt_rand
        if (empty($randomData)) { // Get 108 bytes of (pseudo-random, insecure) data
            $randomData = mt_rand() . mt_rand() . mt_rand() . uniqid(mt_rand(), true) . microtime(true) . uniqid(
                    mt_rand(),
                    true
                );
        }

        return rtrim(strtr(base64_encode(hash('sha256', $randomData)), '+/', '-_'), '=');
    }
}