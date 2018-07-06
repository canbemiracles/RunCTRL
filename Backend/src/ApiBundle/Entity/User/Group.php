<?php

namespace ApiBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * Group
 *
 * @ORM\Table(name="user_group")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\User\GroupRepository")
 */
class Group extends BaseGroup
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
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\User\Permission", inversedBy="groups")
     * * @ORM\JoinTable(name="permissions_groups",
     *      joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="permission_id", referencedColumnName="id")}
     * )
     */
    protected $permissions;


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
     * Add permission
     *
     * @param \ApiBundle\Entity\User\Permission $permission
     *
     * @return Group
     */
    public function addPermission(\ApiBundle\Entity\User\Permission $permission)
    {
        $this->permissions[] = $permission;

        return $this;
    }

    /**
     * Remove permission
     *
     * @param \ApiBundle\Entity\User\Permission $permission
     */
    public function removePermission(\ApiBundle\Entity\User\Permission $permission)
    {
        $this->permissions->removeElement($permission);
    }

    /**
     * Get permissions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}
