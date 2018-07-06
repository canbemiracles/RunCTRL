<?php

namespace ApiBundle\Controller\Role;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Role\BranchStationTempRole;
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
 * BranchStationTempRole controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/temp_roles")
 */
class BranchStationTempRoleController extends AbstractController
{
    /**
     * @Security("is_granted('temp_role_index')")
     * @ApiDoc(
     *  section="BranchStationTempRole",
     *  description="Lists all role entities.",
     *  output="ApiBundle\Entity\Role\BranchStationTempRole",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="temp_role_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @return Collection|BranchStationTempRole
     */
    public function indexAction(Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.roles'));
        }

        return $station->getTempRoles();
    }

    /**
     * @Security("is_granted('temp_role_new')")
     * @ApiDoc(
     *  section="BranchStationTempRole",
     *  description="Creates a new role entity.",
     *  input="ApiBundle\Form\BranchStationTempRoleType",
     *  output="ApiBundle\Entity\Role\BranchStationTempRole",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="temp_role_new")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @param BranchStation $station
     * @return BranchStationTempRole|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.role'));
        }

        $form = $this->createForm('ApiBundle\Form\BranchStationTempRoleType',  new BranchStationTempRole());
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
     * @Security("is_granted('temp_role_show')")
     * @ApiDoc(
     *  section="BranchStationTempRole",
     *  description="Finds and displays a role entity.",
     *  output="ApiBundle\Entity\Role\BranchStationTempRole",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="temp_role_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param BranchStationTempRole $role
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(BranchStationTempRole $role, Branch $branch, BranchStation $station)
    {
        if(!$role) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user) || !$station->getTempRoles()->contains($role)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.role'));
        }

        return $role;
    }

    /**
     * @Security("is_granted('temp_role_edit')")
     *  @ApiDoc(
     *  section="BranchStationTempRole",
     *  description="Displays a form to edit an existing role entity.",
     *  output="ApiBundle\Entity\Role\BranchStationTempRole",
     *  input="ApiBundle\Form\BranchStationTempRoleType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="temp_role_put")
     * @Rest\Patch("/{id}", name="temp_role_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @param Request $request
     * @param BranchStationTempRole $role
     * @param Branch $branch
     * @return \Symfony\Component\HttpFoundation\JsonResponse|BranchStationTempRole
     */
    public function editAction(Request $request, BranchStationTempRole $role, Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.role'));
        }

        $editForm = $this->createForm('ApiBundle\Form\BranchStationTempRoleType', $role, ['method' => $request->getMethod()]);
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
     * @Security("is_granted('temp_role_delete')")
     * @ApiDoc(
     *  section="BranchStationTempRole",
     *  description="Deletes a role entity.",
     *  output="ApiBundle\Entity\Role\BranchStationTempRole",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="temp_role_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param BranchStationTempRole $role
     * @param Branch $branch
     * @return Response
     */
    public function deleteAction(Request $request, BranchStationTempRole $role, Branch $branch)
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
