<?php

namespace ApiBundle\Service\User;

use ApiBundle\Entity\User\Group;
use ApiBundle\Entity\User\Permission;
use ApiBundle\Repository\User\PermissionRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;

class PermissionService
{
    /** @var EntityManager */
    protected $em;

    /** @var TranslatorInterface */
    protected $translator;

    public function __construct(EntityManager $em, TranslatorInterface $translator) {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @param $group integer
     * @param $permissions array
     * @return mixed
     */
    public function assign($group, $permissions)
    {
        /** @var $group Group */
        $group = $this->em->getRepository('ApiBundle:User\Group')->findOneBy(['id' => $group]);

        if($group === null) {
            return $this->translator->trans("group.group_not_found");
        }

        foreach($group->getPermissions() as $permission) {
            $group->removePermission($permission);
        }

        $this->em->persist($group);

        /** @var $permission_repository PermissionRepository*/
        $permission_repository = $this->em->getRepository("ApiBundle:User\Permission");

        foreach ($permissions as $permission) {
            if($current_permission = $permission_repository->findOneBy(['id' => $permission])) {
               /** @var $current_permission Permission*/
               $group->addPermission($current_permission);
            }
        }
        $this->em->persist($group);
        $this->em->flush();

        return $group;
    }
}