<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subcategory
 *
 * @ORM\Table(name="subcategory")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\SubcategoryRepository")
 */
class Subcategory
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\IndustryCategory", inversedBy="companies")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Company", mappedBy="subcategory")
     */
    protected $companies;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Recommendations\RecommendationsStations", mappedBy="subcategory")
     */
    protected $recommendations_stations;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $subcategory;

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
     * Set subcategory
     *
     * @param string $subcategory
     *
     * @return Subcategory
     */
    public function setSubcategory($subcategory)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * Get subcategory
     *
     * @return string
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * Set category
     *
     * @param \ApiBundle\Entity\IndustryCategory $category
     *
     * @return Subcategory
     */
    public function setCategory(\ApiBundle\Entity\IndustryCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \ApiBundle\Entity\IndustryCategory
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->companies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recommendations_stations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add company
     *
     * @param \ApiBundle\Entity\Company $company
     *
     * @return Subcategory
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
     * Add recommendationsStation
     *
     * @param \ApiBundle\Entity\Recommendations\RecommendationsStations $recommendationsStation
     *
     * @return Subcategory
     */
    public function addRecommendationsStation(\ApiBundle\Entity\Recommendations\RecommendationsStations $recommendationsStation)
    {
        $this->recommendations_stations[] = $recommendationsStation;

        return $this;
    }

    /**
     * Remove recommendationsStation
     *
     * @param \ApiBundle\Entity\Recommendations\RecommendationsStations $recommendationsStation
     */
    public function removeRecommendationsStation(\ApiBundle\Entity\Recommendations\RecommendationsStations $recommendationsStation)
    {
        $this->recommendations_stations->removeElement($recommendationsStation);
    }

    /**
     * Get recommendationsStations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecommendationsStations()
    {
        return $this->recommendations_stations;
    }
}
