<?php

namespace ApiBundle\Entity\Security;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecentLogin
 *
 * @ORM\Table(name="security_recent_login")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Security\RecentLoginRepository")
 */
class RecentLogin
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User\AbstractUser", inversedBy="recentLogins")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

    /**
     * @ORM\Column(name="country_code", type="string", nullable=true)
     */
    protected $country_code;

    /**
     * @ORM\Column(name="country_name", type="string", nullable=true)
     */
    protected $country_name;

    /**
     * @ORM\Column(name="region", type="string", nullable=true)
     */
    protected $region;

    /**
     * @ORM\Column(name="city", type="string", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(name="ip", type="string", nullable=true)
     */
    protected $ip;

    public function __construct()
    {
        $this->date = new \DateTime(date('Y-m-d H:i:s'));
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return RecentLogin
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return RecentLogin
     */
    public function setCountryCode($countryCode)
    {
        $this->country_code = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * Set countryName
     *
     * @param string $countryName
     *
     * @return RecentLogin
     */
    public function setCountryName($countryName)
    {
        $this->country_name = $countryName;

        return $this;
    }

    /**
     * Get countryName
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->country_name;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return RecentLogin
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return RecentLogin
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return RecentLogin
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User\AbstractUser $user
     *
     * @return RecentLogin
     */
    public function setUser(\ApiBundle\Entity\User\AbstractUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ApiBundle\Entity\User\AbstractUser
     */
    public function getUser()
    {
        return $this->user;
    }
}
