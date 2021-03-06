<?php

namespace ApiBundle\Repository\Role;

use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\Role\AbstractBranchStationRole;

/**
 * AbstractBranchStationRole
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AbstractBranchStationRoleRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $station BranchStation
     * @param $employee Employee
     * @return mixed
    */
    public function getRolesByStation($station, $employee = null)
    {
        $result = [];
        foreach ($station->getRoles() as $role) {
            /** @var $role AbstractBranchStationRole*/
            if(is_null($employee) || $role->getEmployees()->contains($employee)) {
                $result[] = [
                    'id' => $role->getId(),
                    'role' => $role->getRole(),
                    'id_station' => $role->getBranchStationId(),
                    'id_branch' => $role->getBranchStation()->getBranch()->getId(),
                    'color' => $role->getColor()];
            }
        }
        return $result;
    }
}
