<?php

namespace AssignmentsBundle\Controller\Notification;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\User\AbstractUser;
use AssignmentsBundle\Entity\Notification\DeviceNotificationStation;
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
 * Devicenotificationstation controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/device_notifications")
 */
class DeviceNotificationStationController extends AbstractController
{
    /**
     * @Security("is_granted('assignments_notification_station_index')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Station",
     *  description="Lists all device notification for station.",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationStation",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/", name="assignments_notification_station_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @return array
     */
    public function indexAction(Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.notifications'));
        }

        if($station->getBranch() !== $branch)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));
        }
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('AssignmentsBundle:Notification\DeviceNotificationStation')->findBy(['station'=> $station]);
    }

    /**
     * @Security("is_granted('assignments_notification_station_new')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Station",
     *  description="Creates a new device notification for station.",
     *  input="AssignmentsBundle\Form\Notification\DeviceNotificationStationType",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationStation",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\Post("/new", name="assignments_notification_station_new")
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @param BranchStation $station
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.notification'));
        }

        if($station->getBranch() !== $branch)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));
        }

        $deviceNotificationStation = new DeviceNotificationStation();
        $form = $this->createForm('AssignmentsBundle\Form\Notification\DeviceNotificationStationType', $deviceNotificationStation);
        $form->handleRequest($request);

        if ( $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $deviceNotificationStation->setStation($station);
            $em->persist($deviceNotificationStation);
            $em->flush();

            return $deviceNotificationStation;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('assignments_notification_station_show')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Station",
     *  description="Finds and displays a device notification station entity.",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationStation",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/{id}", name="assignments_notification_station_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param DeviceNotificationStation $deviceNotificationStation
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(DeviceNotificationStation $deviceNotificationStation, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.notification'));
        }

        if($station->getBranch() !== $branch)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));
        }
        if ($deviceNotificationStation->getStation() === $station) {
            return $deviceNotificationStation;
        }

        return new JsonResponse([]);

    }

    /**
     * @Security("is_granted('assignments_notification_station_edit')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Station",
     *  description="Edits an existing device notification station entity.",
     *  input="AssignmentsBundle\Form\Notification\DeviceNotificationStationType",
     *  output="AssignmentsBundle\Entity\Notification\DeviceNotificationStation",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="assignments_notification_station_put")
     * @Rest\Patch("/{id}", name="assignments_notification_station_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @param Request $request
     * @param DeviceNotificationStation $deviceNotificationStation
     * @param Branch $branch
     * @param BranchStation $station
     * @return DeviceNotificationStation|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, DeviceNotificationStation $deviceNotificationStation, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.notification'));
        }

        if($station->getBranch() !== $branch)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.device_notification'));
        }
        if($deviceNotificationStation->getStation() !== $station)
        {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $editForm = $this->createForm('AssignmentsBundle\Form\Notification\DeviceNotificationStationType', $deviceNotificationStation, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $deviceNotificationStation;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('assignments_notification_station_delete')")
     * @ApiDoc(
     *  section="[Assignments] Device Notification Station",
     *  description="Deletes a device notification station entity.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Delete("/{id}", name="assignments_notification_station_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param DeviceNotificationStation $deviceNotificationStation
     * @param Branch $branch
     * @param BranchStation $station
     * @return Response
     */
    public function deleteAction(Request $request, DeviceNotificationStation $deviceNotificationStation, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.notification'));
        }

        if($station->getBranch() !== $branch)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.assignment'));
        }
        if(!$deviceNotificationStation || $deviceNotificationStation->getStation() !== $station){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($deviceNotificationStation);
        $em->flush();

        return new Response();
    }
}
