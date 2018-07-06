<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TimeZone
 *
 * @ORM\Table(name="time_zone")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\TimeZoneRepository")
 */
class TimeZone
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
    protected $time_zone;

    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $abbr;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isdst = false;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $text;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $offset;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Company", mappedBy="time_zone")
     */
    protected $companies;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\UTC", mappedBy="time_zone", cascade={"persist"})
     */
    protected $utc;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->companies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->utc = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set timeZone
     *
     * @param string $timeZone
     *
     * @return TimeZone
     */
    public function setTimeZone($timeZone)
    {
        $this->time_zone = $timeZone;

        return $this;
    }

    /**
     * Get timeZone
     *
     * @return string
     */
    public function getTimeZone()
    {
        return $this->time_zone;
    }


    /**
     * Add company
     *
     * @param \ApiBundle\Entity\Company $company
     *
     * @return TimeZone
     */
    public function addCompany(\ApiBundle\Entity\Company $company)
    {
        $this->companies[] = $company;

        return $this;
    }

    /**
     * Remove company
     *
     * @param \ApiBundle\Entity\Company $company
     */
    public function removeCompany(\ApiBundle\Entity\Company $company)
    {
        $this->companies->removeElement($company);
    }

    /**
     * Get companies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * Set offset
     *
     * @param string $offset
     *
     * @return TimeZone
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Get offset
     *
     * @return string
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Set abbr
     *
     * @param string $abbr
     *
     * @return TimeZone
     */
    public function setAbbr($abbr)
    {
        $this->abbr = $abbr;

        return $this;
    }

    /**
     * Get abbr
     *
     * @return string
     */
    public function getAbbr()
    {
        return $this->abbr;
    }

    /**
     * Set isdst
     *
     * @param boolean $isdst
     *
     * @return TimeZone
     */
    public function setIsdst($isdst)
    {
        $this->isdst = $isdst;

        return $this;
    }

    /**
     * Get isdst
     *
     * @return boolean
     */
    public function getIsdst()
    {
        return $this->isdst;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return TimeZone
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Add utc
     *
     * @param \ApiBundle\Entity\UTC $utc
     *
     * @return TimeZone
     */
    public function addUtc(\ApiBundle\Entity\UTC $utc)
    {
        $this->utc[] = $utc;

        return $this;
    }

    /**
     * Remove utc
     *
     * @param \ApiBundle\Entity\UTC $utc
     */
    public function removeUtc(\ApiBundle\Entity\UTC $utc)
    {
        $this->utc->removeElement($utc);
    }

    /**
     * Get utc
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUtc()
    {
        return $this->utc;
    }
}
