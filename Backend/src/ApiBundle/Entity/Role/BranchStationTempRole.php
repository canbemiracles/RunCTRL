<?php

namespace ApiBundle\Entity\Role;

use Doctrine\ORM\Mapping as ORM;

/**
 * CashierReport
 *
 * @ORM\Table(name="branch_station_temp_role")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Role\BranchStationTempRoleRepository")
 */
class BranchStationTempRole extends AbstractBranchStationRole
{
    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Role\BranchStationOriginRole", inversedBy="temp_roles")
     * @ORM\JoinColumn(name="origin_role_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $origin_role;

    /**
     * Set originRole
     *
     * @param \ApiBundle\Entity\Role\BranchStationOriginRole $originRole
     *
     * @return BranchStationTempRole
     */
    public function setOriginRole(\ApiBundle\Entity\Role\BranchStationOriginRole $originRole = null)
    {
        $this->origin_role = $originRole;

        return $this;
    }

    /**
     * Get originRole
     *
     * @return \ApiBundle\Entity\Role\BranchStationOriginRole
     */
    public function getOriginRole()
    {
        return $this->origin_role;
    }
}
