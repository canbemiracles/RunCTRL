<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GeographicalArea
 *
 * @ORM\Table(name="geographical_area")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\GeographicalAreaRepository")
 */
class GeographicalArea
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Country", inversedBy="geographical_areas")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    protected $region;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $street_address;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $zip;


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
     * Set region
     *
     * @param string $region
     *
     * @return GeographicalArea
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
     * @return GeographicalArea
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
     * Set streetAddress
     *
     * @param string $streetAddress
     *
     * @return GeographicalArea
     */
    public function setStreetAddress($streetAddress)
    {
        $this->street_address = $streetAddress;

        return $this;
    }

    /**
     * Get streetAddress
     *
     * @return string
     */
    public function getStreetAddress()
    {
        return $this->street_address;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return GeographicalArea
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set country
     *
     * @param \ApiBundle\Entity\Country $country
     *
     * @return GeographicalArea
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
