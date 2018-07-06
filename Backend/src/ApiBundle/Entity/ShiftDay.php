<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;

/**
 * ShiftDay
 *
 * @ORM\Table(name="shift_day")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ShiftDayRepository")
 */
class ShiftDay
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
     * @ORM\Column(type="integer")
     */
    protected $day;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\BranchShift", mappedBy="shift_day")
     */
    protected $branch_shifts;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\Branch", inversedBy="shift_days")
     * @ORM\JoinTable(name="schedule_shifts",
     *      joinColumns={@ORM\JoinColumn(name="shift_day_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="branch_id", referencedColumnName="id")}
     * )
     */
    protected $branches;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->branch_shifts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->branches = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set day
     *
     * @param integer $day
     *
     * @return ShiftDay
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return integer
     */
    public function getDay()
    {
        return $this->day;
    }


    /**
     * Add branchShift
     *
     * @param \ApiBundle\Entity\BranchShift $branch_shift
     *
     * @return ShiftDay
     */
    public function addBranchShift(\ApiBundle\Entity\BranchShift $branch_shift)
    {
        $this->branch_shifts[] = $branch_shift;

        return $this;
    }

    /**
     * Remove branchShift
     *
     * @param \ApiBundle\Entity\BranchShift $branch_shift
     */
    public function removeBranchShift(\ApiBundle\Entity\BranchShift $branch_shift)
    {
        $this->branch_shifts->removeElement($branch_shift);
    }

    /**
     * Get branchShifts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBranchShifts()
    {
        return $this->branch_shifts;
    }

    /**
     * Add branch
     *
     * @param \ApiBundle\Entity\Branch $branch
     *
     * @return ShiftDay
     */
    public function addBranch(\ApiBundle\Entity\Branch $branch)
    {
        $this->branches[] = $branch;

        return $this;
    }

    /**
     * Remove branch
     *
     * @param \ApiBundle\Entity\Branch $branch
     */
    public function removeBranch(\ApiBundle\Entity\Branch $branch)
    {
        $this->branches->removeElement($branch);
    }

    /**
     * Get branches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBranches()
    {
        return $this->branches;
    }
}
