<?php

namespace ApiBundle\Entity\Role;

use Doctrine\ORM\Mapping as ORM;

/**
 * CashierReport
 *
 * @ORM\Table(name="branch_station_origin_role")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Role\BranchStationOriginRoleRepository")
 */
class BranchStationOriginRole extends AbstractBranchStationRole
{
    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Role\BranchStationTempRole", mappedBy="origin_role")
     */
    protected $temp_roles;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->temp_roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add role
     *
     * @param \ApiBundle\Entity\Role\BranchStationTempRole $role
     *
     * @return BranchStationOriginRole
     */
    public function addTempRole(\ApiBundle\Entity\Role\BranchStationTempRole $role)
    {
        $this->temp_roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \ApiBundle\Entity\Role\BranchStationTempRole $role
     */
    public function removeTempRole(\ApiBundle\Entity\Role\BranchStationTempRole $role)
    {
        $this->temp_roles->removeElement($role);
    }

    /**
     * Get role
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTempRole()
    {
        return $this->temp_roles;
    }
}
