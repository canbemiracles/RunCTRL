<?php

namespace ApiBundle\Service\Device;

use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\User\Device\Device;
use ApiBundle\Repository\BranchStationRepository;
use ApiBundle\Repository\User\Device\DeviceRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class DeviceService
{
    /** @var EntityManager */
    protected $em;

    /** @var TokenStorage */
    protected $tokenStorage;

    public function __construct(EntityManager $em, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Add/Edit token of device
     *
     * @param string $token
     * @return string
     */
    public function addToken(string $token)
    {
        /** @var Device $device*/
        $device = $this->tokenStorage->getToken()->getUser();

        $device->setToken($token);

        $this->em->flush();

        return $device;
    }

    /**
     * @param Device $device
     * @param int $station
     * @return string
     */
    public function editDeviceStation($device, $station)
    {
        /** @var $repository BranchStationRepository */
        $repository = $this->em->getRepository('ApiBundle:BranchStation');

        /** @var $station BranchStation*/
        $station = $repository->findOneBy(['id' => $station]);

        if(empty($station)) {
            throw new BadRequestHttpException("Station not found");
        }

        $device->setStation($station);
        $this->em->flush();
        return $device;
    }
}