<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employee
 *
 * @ORM\Table(name="phone_number")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\PhoneNumberRepository")
 */
class PhoneNumber
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
     * @ORM\Column(type="string", length=20)
     */
    protected $phone_number;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $prefix_number;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $country;

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return PhoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phone_number = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * Set prefixNumber
     *
     * @param string $prefixNumber
     *
     * @return PhoneNumber
     */
    public function setPrefixNumber($prefixNumber)
    {
        $this->prefix_number = $prefixNumber;

        return $this;
    }

    /**
     * Get prefixNumber
     *
     * @return string
     */
    public function getPrefixNumber()
    {
        return $this->prefix_number;
    }

    /**
     * Set country
     *
     * @param \ApiBundle\Entity\Country $country
     *
     * @return PhoneNumber
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

    public function getCountryInfo()
    {
        if(!empty($this->getCountry())) {
            return array('id' => $this->getCountry()->getId(), 'name' => $this->getCountry()->getName(), 'phone_code' => $this->getCountry()->getPhoneCode());
        } else {
            return null;
        }
    }
}
