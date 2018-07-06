<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 04.10.2017
 * Time: 15:25
 */

namespace ApiBundle\Service\Security;


use ApiBundle\Entity\Security\RecentLogin;
use ApiBundle\Entity\User\AbstractUser;
use Doctrine\ORM\EntityManager;
use Maxmind\Bundle\GeoipBundle\Service\GeoipManager;

class LoginHistory
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var GeoipManager */
    protected $geoipManager;

    public function __construct(EntityManager $entityManager, GeoipManager $geoipManager)
    {
        $this->entityManager = $entityManager;
        $this->geoipManager = $geoipManager;
    }

    public function logToHistory(AbstractUser $user, string $ip)
    {
        $geo = $this->geoipManager->lookup($ip);

        //For internal address, such as 127.0.0.1 or internal docker addresses, this bundle return false
        if(!$geo) {
            return;
        }

        $recentLogin = new RecentLogin();

        $recentLogin->setUser($user);
        $recentLogin->setCity($geo->getCity());
        $recentLogin->setCountryCode($geo->getCountryCode());
        $recentLogin->setCountryName($geo->getCountryName());
        $recentLogin->setRegion($geo->getRegion());

        $recentLogin->setDate(new \DateTime());
        $recentLogin->setIp($ip);

        $this->entityManager->persist($recentLogin);
        $this->entityManager->flush();

    }
}