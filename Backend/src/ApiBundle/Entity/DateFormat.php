<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DateFormat
 *
 * @ORM\Table(name="date_format")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\DateFormatRepository")
 */
class DateFormat
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
    protected $date_format;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Company", mappedBy="date_format")
     */
    protected $companies;


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
     * Set dateFormat
     *
     * @param string $dateFormat
     *
     * @return DateFormat
     */
    public function setDateFormat($dateFormat)
    {
        $this->date_format = $dateFormat;

        return $this;
    }

    /**
     * Get dateFormat
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->date_format;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->companies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add company
     *
     * @param \ApiBundle\Entity\Company $company
     *
     * @return DateFormat
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
}
