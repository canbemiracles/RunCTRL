<?php

namespace ApiBundle\Service\AdminPanel;

use ApiBundle\Entity\Notification\Alert;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Repository\Notification\AlertRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;

class AlertService
{
    /** @var EntityManager */
    protected $em;

    /** @var TranslatorInterface */
    protected $translator;


    public function __construct(EntityManager $em, TranslatorInterface $translator) {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @param $current_ip
     * @param AbstractUser $user
     * @return mixed
     */
    public function check_ip($user, $current_ip)
    {
        /** @var $alert_repository AlertRepository*/
        $alert_repository = $this->em->getRepository('ApiBundle:Notification\Alert');

        $ips = $alert_repository->findBy(array('user' => $user->getId()));

        foreach($ips as $ip) {
            /** @var $ip Alert*/
            if($ip->getIp() === $current_ip && !$ip->getConfirmed()) {
                return false;
            } elseif($ip->getIp() === $current_ip) {
                return true;
            }
        }

        $alert = new Alert();
        $alert->setUser($user);
        $alert->setIp($current_ip);
        $this->em->persist($alert);
        $this->em->flush();

        return false;

    }

}