<?php

namespace ApiBundle\Entity\User\Device;

use ApiBundle\Entity\User\AbstractUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="user_device")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\User\Device\DeviceRepository")
 */
class Device extends AbstractUser
{

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\BranchStation", inversedBy="devices")
     * @ORM\JoinColumn(name="station_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $station;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $token;

    /**
     * Set station
     *
     * @param \ApiBundle\Entity\BranchStation $station
     *
     * @return Device
     */
    public function setStation(\ApiBundle\Entity\BranchStation $station = null)
    {
        $this->station = $station;

        return $this;
    }

    /**
     * Get station
     *
     * @return \ApiBundle\Entity\BranchStation
     */
    public function getStation()
    {
        return $this->station;
    }

    public function getBranchId()
    {
        if(!empty($this->getStation()) && !empty($this->getStation()->getBranch())) {
            return $this->getStation()->getBranch()->getId();
        } else {
            return null;
        }
    }

    public function getStationId()
    {
        if(!empty($this->getStation())) {
            return $this->getStation()->getId();
        } else {
            return null;
        }
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Device
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}
