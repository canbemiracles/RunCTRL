<?php

namespace ApiBundle\Repository\User;

/**
 * PermissionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PermissionRepository extends \Doctrine\ORM\EntityRepository
{

    public function findByPermission($permission)
    {
        return $this->findOneBy(['permission' => $permission]);
    }
}
