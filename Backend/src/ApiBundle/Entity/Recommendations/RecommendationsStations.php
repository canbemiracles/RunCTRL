<?php

namespace ApiBundle\Entity\Recommendations;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecommendationsStations
 *
 * @ORM\Table(name="recommendations_stations")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Recommendations\RecommendationsStationsRepository")
 */
class RecommendationsStations
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
     * @ORM\Column(type="string", length=6)
     */
    protected $color;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Subcategory", inversedBy="recommendations_stations")
     * @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $subcategory;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Recommendations\RecommendationsRoles", mappedBy="recommendations_station")
     */
    protected $recommendations_roles;

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
     * Constructor
     */
    public function __construct()
    {
        $this->recommendations_roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return RecommendationsStations
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
     * Set subcategory
     *
     * @param \ApiBundle\Entity\Subcategory $subcategory
     *
     * @return RecommendationsStations
     */
    public function setSubcategory(\ApiBundle\Entity\Subcategory $subcategory = null)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * Get subcategory
     *
     * @return \ApiBundle\Entity\Subcategory
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * Add recommendationsRole
     *
     * @param \ApiBundle\Entity\Recommendations\RecommendationsRoles $recommendationsRole
     *
     * @return RecommendationsStations
     */
    public function addRecommendationsRole(\ApiBundle\Entity\Recommendations\RecommendationsRoles $recommendationsRole)
    {
        $this->recommendations_roles[] = $recommendationsRole;

        return $this;
    }

    /**
     * Remove recommendationsRole
     *
     * @param \ApiBundle\Entity\Recommendations\RecommendationsRoles $recommendationsRole
     */
    public function removeRecommendationsRole(\ApiBundle\Entity\Recommendations\RecommendationsRoles $recommendationsRole)
    {
        $this->recommendations_roles->removeElement($recommendationsRole);
    }

    /**
     * Get recommendationsRoles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecommendationsRoles()
    {
        return $this->recommendations_roles;
    }

    /**
     * Get subcategory id
     *
     * @return integer
     */
    public function getSubcategoryId()
    {
        return $this->getSubcategory()->getId();
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return RecommendationsStations
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
}
