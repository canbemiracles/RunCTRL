<?php

namespace ApiBundle\Controller\Role;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Service\BranchStationRole\BranchStationRoleManagement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use ApiBundle\Entity\Role\AbstractBranchStationRole;

/**
 * BranchStationRole controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/roles")
 */
class BranchStationRoleController extends AbstractController
{
    /**
     * @Security("is_granted('get_working_roles')")
     * @ApiDoc(
     *  section="AbstractBranchStationRole",
     *  description="List all roles.",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/get_working_roles", name="get_working_roles")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @return mixed $roles
     */
    public function getWorkingRolesAction(Branch $branch, BranchStation $station) {
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('device_not_allowed.see.these.roles'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.roles'));
        }

        /** @var $service BranchStationRoleManagement*/
        $service = $this->get('service.branch_station_role.branch_station_role_management');
        return $service->getAllRoles($station);
    }

    /**
     * @Security("is_granted('get_working_roles')")
     * @ApiDoc(
     *  section="AbstractBranchStationRole",
     *  description="List all roles.",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/{id}/get_working_role", name="get_working_role")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @param AbstractBranchStationRole $role
     * @return mixed $response
     */
    public function getWorkingRoleAction(Branch $branch, BranchStation $station, AbstractBranchStationRole $role) {
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('device_not_allowed.see.these.roles'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.roles'));
        }

        return $this->get('service.branch_station_role.branch_station_role_management')->getRoleEmployee($station, $role);
    }

}
