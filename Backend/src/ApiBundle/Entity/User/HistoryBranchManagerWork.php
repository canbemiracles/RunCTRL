<?php

namespace ApiBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoryBranchManagerWork
 *
 * @ORM\Table(name="history_branch_manager_work")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\User\HistoryBranchManagerWorkRepository")
 */
class HistoryBranchManagerWork
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User\BranchManager", fetch="EAGER")
     * @ORM\JoinColumn(name="branch_manager_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $branch_manager;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\BranchShift", inversedBy="history_employee_roles", fetch="EAGER")
     * @ORM\JoinColumn(name="branch_shift_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $branch_shift;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $date_end;


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
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return HistoryBranchManagerWork
     */
    public function setDateStart($dateStart)
    {
        $this->date_start = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     *
     * @return HistoryBranchManagerWork
     */
    public function setDateEnd($dateEnd)
    {
        $this->date_end = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->date_end;
    }

    /**
     * Set branchManager
     *
     * @param \ApiBundle\Entity\User\BranchManager $branchManager
     *
     * @return HistoryBranchManagerWork
     */
    public function setBranchManager(\ApiBundle\Entity\User\BranchManager $branchManager = null)
    {
        $this->branch_manager = $branchManager;

        return $this;
    }

    /**
     * Get branchManager
     *
     * @return \ApiBundle\Entity\User\BranchManager
     */
    public function getBranchManager()
    {
        return $this->branch_manager;
    }

    /**
     * Set branchShift
     *
     * @param \ApiBundle\Entity\BranchShift $branchShift
     *
     * @return HistoryBranchManagerWork
     */
    public function setBranchShift(\ApiBundle\Entity\BranchShift $branchShift = null)
    {
        $this->branch_shift = $branchShift;

        return $this;
    }

    /**
     * Get branchShift
     *
     * @return \ApiBundle\Entity\BranchShift
     */
    public function getBranchShift()
    {
        return $this->branch_shift;
    }
}
