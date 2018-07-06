<?php

namespace ApiBundle\Security;

use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\BranchManager;
use ApiBundle\Entity\User\Group;
use ApiBundle\Entity\User\Permission;
use ApiBundle\Entity\User\Supervisor;
use ApiBundle\Repository\User\AbstractUserRepository;
use ApiBundle\Repository\User\PermissionRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PermissionVoter extends Voter
{
    /** @var EntityManager */
    private $em;

    /** @var RequestStack */
    private $requestStack;

    public function __construct(EntityManager $em, RequestStack $request_stack)
    {
        $this->em = $em;
        $this->requestStack = $request_stack;
    }

    protected function supports($attribute, $subject)
    {
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        /** @var $user_repository AbstractUserRepository*/
        $user_repository = $this->em->getRepository("ApiBundle:User\AbstractUser");
        $getToken = $this->requestStack->getMasterRequest()->get('token');
        if(!empty($getToken) && !$user instanceof AbstractUser) {
           /** @var $confirmToken AbstractUser*/
           $confirmToken = $user_repository->findOneBy(['confirmationToken' => $getToken]);
           /** @var $user AbstractUser */
           if(empty($confirmToken)) {
               $user = $user_repository->getUserByAccessToken($getToken);
               $difExpires = !empty($user->getAccessToken($getToken)) ? $user->getAccessToken($getToken)->getExpiresAt() - time() : 0;
               if ((!$user instanceof BranchManager && !$user instanceof Supervisor) || $difExpires <= 0) {
                   $user = null;
               }
           } else {
               $user = $confirmToken;
           }
        }

        if (!$user instanceof AbstractUser) {
            return false;
        }

        return $this->hasPermission($attribute, $user);
    }

    /**
     * @param string $permission
     * @param AbstractUser $user
     *
     * @return bool
    */
    public function hasPermission($permission, $user)
    {

        /** @var $permission_repository PermissionRepository*/
        $permission_repository = $this->em->getRepository("ApiBundle:User\Permission");
        /** @var $permission Permission */
        $permission = $permission_repository->findByPermission($permission);
        if(!empty($permission)) {
            foreach ($user->getGroups() as $group) {
                /** @var Group $group */
                if ($permission->getGroups()->contains($group)) {
                    return true;
                }
            }
        }
        return false;
    }
}