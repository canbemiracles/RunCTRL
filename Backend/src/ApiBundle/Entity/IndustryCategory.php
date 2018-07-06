<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IndustryCategory
 *
 * @ORM\Table(name="industry_category")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\IndustryCategoryRepository")
 */
class IndustryCategory
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
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Subcategory", mappedBy="category")
     */
    protected $subcategories;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $category;


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
     * Set category
     *
     * @param string $category
     *
     * @return IndustryCategory
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
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
        $this->subcategories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add subcategory
     *
     * @param \ApiBundle\Entity\Subcategory $subcategory
     *
     * @return IndustryCategory
     */
    public function addSubcategory(\ApiBundle\Entity\Subcategory $subcategory)
    {
        $this->subcategories[] = $subcategory;

        return $this;
    }

    /**
     * Remove subcategory
     *
     * @param \ApiBundle\Entity\Subcategory $subcategory
     */
    public function removeSubcategory(\ApiBundle\Entity\Subcategory $subcategory)
    {
        $this->subcategories->removeElement($subcategory);
    }

    /**
     * Get subcategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubcategories()
    {
        return $this->subcategories;
    }
}
