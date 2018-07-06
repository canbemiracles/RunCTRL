<?php

namespace AssignmentsBundle\Controller\Notification;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\User\AbstractUser;
use AssignmentsBundle\Entity\Notification\DeviceNotificationMessage;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Devicenotificationmessage controller.
 *
 * @Route("branches/{branch_id}/employees/{employee_id}/device_notifications")
 */
class DeviceNotificationMessageController extends AbstractController
{
    /**
     * @Security("is_granted('assignments_notification_message_index')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Message",
     *  description="Lists all employee device notification.",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationMessage",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/", name="assignments_notification_message_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("employee", options={"mapping": {"employee_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param Employee $employee
     * @return array
     */
    public function indexAction(Branch $branch, Employee $employee)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.notifications'));
        }

        if(!$employee || !$branch || !$employee->getBranches()->contains($branch))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));
        }
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('AssignmentsBundle:Notification\DeviceNotificationMessage')->findBy(['employee' => $employee]);
    }

    /**
     * @Security("is_granted('assignments_notification_message_new')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Message",
     *  description="Creates a new notification message for employee.",
     *  input="AssignmentsBundle\Form\Notification\DeviceNotificationMessageType",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationMessage",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("employee", options={"mapping": {"employee_id" : "id"}})
     * @Rest\Post("/new", name="assignments_notification_message_new")
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @param Employee $employee
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Branch $branch, Employee $employee)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.notification'));
        }

        if(!$employee || !$branch || !$employee->getBranches()->contains($branch))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));
        }
        $deviceNotificationMessage = new Devicenotificationmessage();
        $form = $this->createForm('AssignmentsBundle\Form\Notification\DeviceNotificationMessageType', $deviceNotificationMessage);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $deviceNotificationMessage->setEmployee($employee);
            $em->persist($deviceNotificationMessage);
            $em->flush();

            return $deviceNotificationMessage;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('assignments_notification_message_show')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Message",
     *  description="Finds and displays notification message entity.",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationMessage",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/{id}", name="assignments_notification_message_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("employee", options={"mapping": {"employee_id" : "id"}})
     * @Rest\View()
     * @param DeviceNotificationMessage $deviceNotificationMessage
     * @param Branch $branch
     * @param Employee $employee
     * @return object
     */
    public function showAction(DeviceNotificationMessage $deviceNotificationMessage, Branch $branch, Employee $employee)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.notification'));
        }

        if(!$employee || !$branch || !$employee->getBranches()->contains($branch))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));

        }
        if($deviceNotificationMessage->getEmployee() === $employee) {
            return $deviceNotificationMessage;
        }
        return new JsonResponse([]);
    }

    /**
     * @Security("is_granted('assignments_notification_message_edit')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Message",
     *  description="Edits an existing notification message entity.",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationMessage",
     *  input="AssignmentsBundle\Form\Notification\DeviceNotificationMessageType",
     *  tags = {
     *    "Not Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="assignments_notification_message_put")
     * @Rest\Patch("/{id}", name="assignments_notification_message_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("employee", options={"mapping": {"employee_id" : "id"}})
     * @param Request $request
     * @param DeviceNotificationMessage $deviceNotificationMessage
     * @param Branch $branch
     * @param Employee $employee
     * @return DeviceNotificationMessage|JsonResponse
     */
    public function editAction(Request $request, DeviceNotificationMessage $deviceNotificationMessage, Branch $branch, Employee $employee)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.notification'));
        }

        if(!$employee || !$branch || !$employee->getBranches()->contains($branch))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));

        }
        $editForm = $this->createForm('AssignmentsBundle\Form\Notification\DeviceNotificationMessageType', $deviceNotificationMessage, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $deviceNotificationMessage;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('assignments_notification_message_delete')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Message",
     *  description="Deletes a device notification entity.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Delete("/{id}", name="assignments_notification_message_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("employee", options={"mapping": {"employee_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param DeviceNotificationMessage $deviceNotificationMessage
     * @param Branch $branch
     * @param Employee $employee
     * @return Response
     */
    public function deleteAction(Request $request, DeviceNotificationMessage $deviceNotificationMessage, Branch $branch, Employee $employee)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.notification'));
        }

        if(!$employee || !$branch || !$employee->getBranches()->contains($branch))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($deviceNotificationMessage);
        $em->flush();

        return new Response();
    }

}
