<?php

namespace ApiBundle\Controller\Role;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Role\BranchStationOriginRole;
use ApiBundle\Entity\User\AbstractUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ApiBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Doctrine\Common\Collections\Collection;

/**
 * BranchStationOriginRole controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/origin_roles")
 */
class BranchStationOriginRoleController extends AbstractController
{
    /**
     * @Security("is_granted('origin_role_index')")
     * @ApiDoc(
     *  section="BranchStationOriginRole",
     *  description="Lists all role entities.",
     *  output="ApiBundle\Entity\Role\BranchStationOriginRole",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="origin_role_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @return Collection|BranchStationOriginRole
     */
    public function indexAction(Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.roles'));
        }

        return $station->getOriginRoles();
    }

    /**
     * @Security("is_granted('origin_role_new')")
     * @ApiDoc(
     *  section="BranchStationOriginRole",
     *  description="Creates a new role entity.",
     *  input="ApiBundle\Form\BranchStationOriginRoleType",
     *  output="ApiBundle\Entity\Role\BranchStationRole",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="origin_role_new")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @param BranchStation $station
     * @return BranchStationOriginRole|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.role'));
        }

        $form = $this->createForm('ApiBundle\Form\BranchStationOriginRoleType',  new BranchStationOriginRole());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $role = $form->getData();
            $role->setBranchStation($station);
            $em->persist($role);
            $em->flush();

            return $role;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('get_working_roles')")
     * @ApiDoc(
     *  section="BranchStationOriginRole",
     *  description="List all roles.",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/get_working_roles", name="origin_get_working_roles")
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

        return $this->get('service.branch_station_role.branch_station_role_management')->getRoles($station);
    }

    /**
     * @Security("is_granted('origin_role_show')")
     * @ApiDoc(
     *  section="BranchStationOriginRole",
     *  description="Finds and displays a role entity.",
     *  output="ApiBundle\Entity\Role\BranchStationOriginRole",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="origin_role_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param BranchStationOriginRole $role
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(BranchStationOriginRole $role, Branch $branch, BranchStation $station)
    {
        if(!$role){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user) || !$station->getOriginRoles()->contains($role)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.role'));
        }

        return $role;
    }

    /**
     * @Security("is_granted('origin_role_edit')")
     *  @ApiDoc(
     *  section="BranchStationOriginRole",
     *  description="Displays a form to edit an existing role entity.",
     *  output="ApiBundle\Entity\Role\BranchStationOriginRole",
     *  input="ApiBundle\Form\BranchStationOriginRoleType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="role_put")
     * @Rest\Patch("/{id}", name="role_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @param Request $request
     * @param BranchStationOriginRole $role
     * @param Branch $branch
     * @return \Symfony\Component\HttpFoundation\JsonResponse|BranchStationOriginRole
     */
    public function editAction(Request $request, BranchStationOriginRole $role, Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.role'));
        }

        $editForm = $this->createForm('ApiBundle\Form\BranchStationOriginRoleType', $role, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $role;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('origin_role_delete')")
     * @ApiDoc(
     *  section="BranchStationOriginRole",
     *  description="Deletes a role entity.",
     *  output="ApiBundle\Entity\Role\BranchStationOriginRole",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="origin_role_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param BranchStationOriginRole $role
     * @param Branch $branch
     * @return Response
     */
    public function deleteAction(Request $request, BranchStationOriginRole $role, Branch $branch)
    {
        if(!$role){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.role'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($role);
        $em->flush();

        return new Response();
    }

}
