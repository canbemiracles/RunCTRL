<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FamilyStatuses
 *
 * @ORM\Table(name="family_statuses")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\FamilyStatusesRepository")
 */
class FamilyStatuses
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
    protected $family_status;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Employee", mappedBy="family_situation")
     */
    protected $employees;


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
     * Set familyStatus
     *
     * @param string $familyStatus
     *
     * @return FamilyStatuses
     */
    public function setFamilyStatus($familyStatus)
    {
        $this->family_status = $familyStatus;

        return $this;
    }

    /**
     * Get familyStatus
     *
     * @return string
     */
    public function getFamilyStatus()
    {
        return $this->family_status;
    }
}
