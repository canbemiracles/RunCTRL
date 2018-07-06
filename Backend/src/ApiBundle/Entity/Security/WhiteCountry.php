<?php

namespace ApiBundle\Entity\Security;

use Doctrine\ORM\Mapping as ORM;

/**
 * WhiteCountry
 *
 * @ORM\Table(name="security_white_country")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Security\WhiteCountryRepository")
 */
class WhiteCountry
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User\AbstractUser", inversedBy="whiteCountries")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $country;


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
     * Set user
     *
     * @param \ApiBundle\Entity\User\AbstractUser $user
     *
     * @return WhiteCountry
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

    /**
     * Set country
     *
     * @param \ApiBundle\Entity\Country $country
     *
     * @return WhiteCountry
     */
    public function setCountry(\ApiBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \ApiBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}
