<?php

namespace AssignmentsBundle\Controller\Notification;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\User\AbstractUser;
use AssignmentsBundle\Entity\Notification\DeviceNotificationRole;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Devicenotificationrole controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/roles/{role_id}/device_notifications")
 */
class DeviceNotificationRoleController extends AbstractController
{
    /**
     * @Security("is_granted('assignments_notification_role_index')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Role",
     *  description="Lists all device notification for role.",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationRole",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/", name="assignments_notification_role_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @ParamConverter("role", options={"mapping": {"role_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @param AbstractBranchStationRole $role
     * @return array
     */
    public function indexAction(Branch $branch, BranchStation $station, AbstractBranchStationRole $role)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.notifications'));
        }

        if($role->getBranchStation() !== $station || $station->getBranch() !== $branch)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));
        }
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('AssignmentsBundle:Notification\DeviceNotificationRole')->findBy(['role'=> $role]);
    }

    /**
     * @Security("is_granted('assignments_notification_role_new')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Role",
     *  description="Creates a new device notification for role.",
     *  input="AssignmentsBundle\Form\Notification\DeviceNotificationRoleType",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationRole",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @ParamConverter("role", options={"mapping": {"role_id" : "id"}})
     * @Rest\Post("/new", name="assignments_notification_role_new")
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @param BranchStation $station
     * @param AbstractBranchStationRole $role
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Branch $branch, BranchStation $station, AbstractBranchStationRole $role)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.notification'));
        }

        if($role->getBranchStation() !== $station || $station->getBranch() !== $branch)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));
        }

        $deviceNotificationRole = new Devicenotificationrole();
        $form = $this->createForm('AssignmentsBundle\Form\Notification\DeviceNotificationRoleType', $deviceNotificationRole);
        $form->handleRequest($request);

        if ( $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $deviceNotificationRole->setRole($role);
            $em->persist($deviceNotificationRole);
            $em->flush();

            return $deviceNotificationRole;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('assignments_notification_role_show')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Role",
     *  description="Finds and displays a device notification role entity.",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationRole",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/{id}", name="assignments_notification_role_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @ParamConverter("role", options={"mapping": {"role_id" : "id"}})
     * @Rest\View()
     * @param DeviceNotificationRole $deviceNotificationRole
     * @param Branch $branch
     * @param BranchStation $station
     * @param AbstractBranchStationRole $role
     * @return object
     */
    public function showAction(DeviceNotificationRole $deviceNotificationRole, Branch $branch, BranchStation $station, AbstractBranchStationRole $role)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.notification'));
        }

        if($role->getBranchStation() !== $station || $station->getBranch() !== $branch)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));
        }
        if ($deviceNotificationRole->getRole() === $role) {
            return $deviceNotificationRole;
        }

        return new JsonResponse([]);

    }

    /**
     * @Security("is_granted('assignments_notification_role_edit')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Role",
     *  description="Edits an existing device notification role entity.",
     *  input="AssignmentsBundle\Form\Notification\DeviceNotificationRoleType",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationRole",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="assignments_notification_role_put")
     * @Rest\Patch("/{id}", name="assignments_notification_role_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @ParamConverter("role", options={"mapping": {"role_id" : "id"}})
     * @param Request $request
     * @param DeviceNotificationRole $deviceNotificationRole
     * @param Branch $branch
     * @param BranchStation $station
     * @param AbstractBranchStationRole $role
     * @return DeviceNotificationRole|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, DeviceNotificationRole $deviceNotificationRole, Branch $branch, BranchStation $station, AbstractBranchStationRole $role)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.notification'));
        }

        if($role->getBranchStation() !== $station || $station->getBranch() !== $branch)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));
        }
        if($deviceNotificationRole->getRole() !== $role)
        {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $editForm = $this->createForm('AssignmentsBundle\Form\Notification\DeviceNotificationRoleType', $deviceNotificationRole, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $deviceNotificationRole;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('assignments_notification_role_delete')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Role",
     *  description="Deletes a device notification role entity.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Delete("/{id}", name="assignments_notification_role_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @ParamConverter("role", options={"mapping": {"role_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param DeviceNotificationRole $deviceNotificationRole
     * @param Branch $branch
     * @param BranchStation $station
     * @param AbstractBranchStationRole $role
     * @return Response
     */
    public function deleteAction(Request $request, DeviceNotificationRole $deviceNotificationRole, Branch $branch, BranchStation $station, AbstractBranchStationRole $role)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.notification'));
        }

        if($role->getBranchStation() !== $station || $station->getBranch() !== $branch)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.assignment'));
        }
        if(!$deviceNotificationRole || $deviceNotificationRole->getRole() !== $role){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($deviceNotificationRole);
        $em->flush();

        return new Response();
    }
}
