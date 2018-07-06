<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\CountryRepository")
 */
class Country
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
     * @ORM\Column(type="string", length=80)
     */
    protected $name;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_state = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $workday_start;

    /**
     * @ORM\Column(type="string")
     */
    protected $country_code;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $phone_code;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\DateFormat", inversedBy="countries")
     * @ORM\JoinColumn(name="date_format_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $date_format;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Currency", inversedBy="countries")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $currency;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\GeographicalArea", mappedBy="country")
     */
    protected $geographical_areas;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->geographical_areas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->emergency_phones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Country
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Add geographicalArea
     *
     * @param \ApiBundle\Entity\GeographicalArea $geographicalArea
     *
     * @return Country
     */
    public function addGeographicalArea(\ApiBundle\Entity\GeographicalArea $geographicalArea)
    {
        $this->geographical_areas[] = $geographicalArea;

        return $this;
    }

    /**
     * Remove geographicalArea
     *
     * @param \ApiBundle\Entity\GeographicalArea $geographicalArea
     */
    public function removeGeographicalArea(\ApiBundle\Entity\GeographicalArea $geographicalArea)
    {
        $this->geographical_areas->removeElement($geographicalArea);
    }

    /**
     * Get geographicalAreas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGeographicalAreas()
    {
        return $this->geographical_areas;
    }

    /**
     * Set phoneCode
     *
     * @param string $phoneCode
     *
     * @return Country
     */
    public function setPhoneCode($phoneCode)
    {
        $this->phone_code = $phoneCode;

        return $this;
    }

    /**
     * Get phoneCode
     *
     * @return string
     */
    public function getPhoneCode()
    {
        return $this->phone_code;
    }

    /**
     * Set isState
     *
     * @param boolean $isState
     *
     * @return Country
     */
    public function setIsState($isState)
    {
        $this->is_state = $isState;

        return $this;
    }

    /**
     * Get isState
     *
     * @return boolean
     */
    public function getIsState()
    {
        return $this->is_state;
    }

    /**
     * Set workdayStart
     *
     * @param integer $workdayStart
     *
     * @return Country
     */
    public function setWorkdayStart($workdayStart)
    {
        $this->workday_start = $workdayStart;

        return $this;
    }

    /**
     * Get workdayStart
     *
     * @return integer
     */
    public function getWorkdayStart()
    {
        return $this->workday_start;
    }

    /**
     * Set dateFormat
     *
     * @param \ApiBundle\Entity\DateFormat $dateFormat
     *
     * @return Country
     */
    public function setDateFormat(\ApiBundle\Entity\DateFormat $dateFormat = null)
    {
        $this->date_format = $dateFormat;

        return $this;
    }

    /**
     * Get dateFormat
     *
     * @return \ApiBundle\Entity\DateFormat
     */
    public function getDateFormat()
    {
        return $this->date_format;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return Country
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
     * Set currency
     *
     * @param \ApiBundle\Entity\Currency $currency
     *
     * @return Country
     */
    public function setCurrency(\ApiBundle\Entity\Currency $currency = null)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \ApiBundle\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
