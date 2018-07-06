<?php

namespace ApiBundle\Entity\Recommendations;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecommendationsRoles
 *
 * @ORM\Table(name="recommendations_roles")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Recommendations\RecommendationsRolesRepository")
 */
class RecommendationsRoles
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Recommendations\RecommendationsStations", inversedBy="recommendations_roles")
     * @ORM\JoinColumn(name="recommendations_station_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $recommendations_station;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $defaulted;


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
     * @return RecommendationsRoles
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
     * Set recommendationsStation
     *
     * @param \ApiBundle\Entity\Recommendations\RecommendationsStations $recommendationsStation
     *
     * @return RecommendationsRoles
     */
    public function setRecommendationsStation(\ApiBundle\Entity\Recommendations\RecommendationsStations $recommendationsStation = null)
    {
        $this->recommendations_station = $recommendationsStation;

        return $this;
    }

    /**
     * Get recommendationsStation
     *
     * @return \ApiBundle\Entity\Recommendations\RecommendationsStations
     */
    public function getRecommendationsStation()
    {
        return $this->recommendations_station;
    }
    

    /**
     * Set defaulted
     *
     * @param boolean $defaulted
     *
     * @return RecommendationsRoles
     */
    public function setDefaulted($defaulted)
    {
        $this->defaulted = $defaulted;

        return $this;
    }

    /**
     * Get defaulted
     *
     * @return boolean
     */
    public function getDefaulted()
    {
        return $this->defaulted;
    }

    /**
     * Get recommendations_station id
     *
     * @return integer
     */
    public function getRecommendationsStationId()
    {
        return $this->getRecommendationsStation()->getId();
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return RecommendationsRoles
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
