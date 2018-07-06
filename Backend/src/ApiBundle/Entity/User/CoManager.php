<?php

namespace ApiBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoManager
 *
 * @ORM\Table(name="user_co_manager")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\User\CoManagerRepository")
 */
class CoManager extends AbstractUser
{
    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Branch", inversedBy="co_managers")
     * @ORM\JoinColumn(name="branch_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $branch;

    /**
     * Set branch
     *
     * @param \ApiBundle\Entity\Branch $branch
     *
     * @return CoManager
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
}
