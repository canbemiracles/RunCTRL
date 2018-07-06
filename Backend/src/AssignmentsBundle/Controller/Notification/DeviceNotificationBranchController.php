<?php

namespace AssignmentsBundle\Controller\Notification;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\User\AbstractUser;
use AssignmentsBundle\Entity\Notification\DeviceNotificationBranch;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Devicenotificationbranch controller.
 *
 * @Route("branches/{branch_id}/device_notifications")
 */
class DeviceNotificationBranchController extends AbstractController
{
    /**
     * @Security("is_granted('assignments_notification_branch_index')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Branch",
     *  description="Lists all device notification for branch.",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationBranch",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/", name="assignments_notification_branch_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @return array
     */
    public function indexAction(Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.notifications'));
        }

        if(!$branch)
        {
            throw new BadRequestHttpException($this->get('translator')->trans('branch_not_exists'));
        }
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('AssignmentsBundle:Notification\DeviceNotificationBranch')->findBy(['branch'=> $branch]);;
    }

    /**
     * @Security("is_granted('assignments_notification_branch_new')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Branch",
     *  description="Creates a new device notification for branch.",
     *  input="AssignmentsBundle\Form\Notification\DeviceNotificationBranchType",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationBranch",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\Post("/new", name="assignments_notification_branch_new")
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.notification'));
        }

        if(!$branch)
        {
            throw new BadRequestHttpException($this->get('translator')->trans('branch_not_exists'));
        }

        $deviceNotificationBranch = new DeviceNotificationBranch();
        $form = $this->createForm('AssignmentsBundle\Form\Notification\DeviceNotificationBranchType', $deviceNotificationBranch);
        $form->handleRequest($request);

        if ( $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $deviceNotificationBranch->setBranch($branch);
            $em->persist($deviceNotificationBranch);
            $em->flush();

            return $deviceNotificationBranch;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('assignments_notification_branch_show')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Branch",
     *  description="Finds and displays a device notification branch entity.",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationBranch",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/{id}", name="assignments_notification_branch_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param DeviceNotificationBranch $deviceNotificationBranch
     * @param Branch $branch
     * @return object
     */
    public function showAction(DeviceNotificationBranch $deviceNotificationBranch, Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.notification'));
        }

        if(!$branch)
        {
            throw new BadRequestHttpException($this->get('translator')->trans('branch_not_exists'));
        }
        if ($deviceNotificationBranch->getBranch() === $branch) {
            return $deviceNotificationBranch;
        }

        return new JsonResponse([]);

    }

    /**
     * @Security("is_granted('assignments_notification_branch_edit')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Branch",
     *  description="Edits an existing device notification branch entity.",
     *  input="AssignmentsBundle\Form\Notification\DeviceNotificationBranchType",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationBranch",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="assignments_notification_branch_put")
     * @Rest\Patch("/{id}", name="assignments_notification_branch_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @param Request $request
     * @param DeviceNotificationBranch $deviceNotificationBranch
     * @param Branch $branch
     * @return DeviceNotificationBranch|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, DeviceNotificationBranch $deviceNotificationBranch, Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.notification'));
        }

        if(!$branch)
        {
            throw new BadRequestHttpException($this->get('translator')->trans('branch_not_exists'));
        }
        if ($deviceNotificationBranch->getBranch() !== $branch)
        {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $editForm = $this->createForm('AssignmentsBundle\Form\Notification\DeviceNotificationBranchType', $deviceNotificationBranch, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $deviceNotificationBranch;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('assignments_notification_branch_delete')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Branch",
     *  description="Deletes a device notification branch entity.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Delete("/{id}", name="assignments_notification_branch_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param DeviceNotificationBranch $deviceNotificationBranch
     * @param Branch $branch
     * @return Response
     */
    public function deleteAction(Request $request, DeviceNotificationBranch $deviceNotificationBranch, Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.notification'));
        }

        if(!$branch)
        {
            throw new BadRequestHttpException($this->get('translator')->trans('branch_not_exists'));
        }
        if ($deviceNotificationBranch->getBranch() !== $branch)
        {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($deviceNotificationBranch);
        $em->flush();

        return new Response();
    }
}
