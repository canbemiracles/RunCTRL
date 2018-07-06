<?php

namespace ApiBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * Supervisor
 *
 * @ORM\Table(name="user_supervisor")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\User\SupervisorRepository")
 */
class Supervisor extends AbstractUser
{

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Branch", mappedBy="supervisor")
     */
    protected $branches;


    /**
     * Add branch
     *
     * @param \ApiBundle\Entity\Branch $branch
     *
     * @return Supervisor
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
