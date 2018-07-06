<?php

namespace ApiBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * BranchManager
 *
 * @ORM\Table(name="user_branch_manager")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\User\BranchManagerRepository")
 */
class BranchManager extends AbstractUser
{
    /**
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\Branch", inversedBy="branch_manager")
     * @ORM\JoinColumn(name="branch_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $branch;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\User\HistoryBranchManagerWork", mappedBy="branch_manager")
     */
    protected $history_works;

    /**
     * Set branch
     *
     * @param \ApiBundle\Entity\Branch $branch
     *
     * @return BranchManager
     */
    public function setBranch(\ApiBundle\Entity\Branch $branch = null)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch
     *
     * @return \ApiBundle\Entity\Branch
     */
    public function getBranch()
    {
        return $this->branch;
    }

    public function getBranchId()
    {
        return !empty($this->getBranch()) ? $this->getBranch()->getId() : null;
    }


    /**
     * Add historyWork
     *
     * @param \ApiBundle\Entity\User\HistoryBranchManagerWork $historyWork
     *
     * @return BranchManager
     */
    public function addHistoryWork(\ApiBundle\Entity\User\HistoryBranchManagerWork $historyWork)
    {
        $this->history_works[] = $historyWork;

        return $this;
    }

    /**
     * Remove historyWork
     *
     * @param \ApiBundle\Entity\User\HistoryBranchManagerWork $historyWork
     */
    public function removeHistoryWork(\ApiBundle\Entity\User\HistoryBranchManagerWork $historyWork)
    {
        $this->history_works->removeElement($historyWork);
    }

    /**
     * Get historyWorks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoryWorks()
    {
        return $this->history_works;
    }
}
