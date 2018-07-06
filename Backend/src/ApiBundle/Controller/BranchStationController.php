<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Service\Branch\BranchService;
use ApiBundle\Service\BranchStationRole\BranchStationRoleManagement;
use ApiBundle\Service\Generator\BranchStationAccessGenerator;
use ApiBundle\Service\Report\BranchStatus;
use ApiBundle\Service\Report\CashierReport;
use ApiBundle\Service\Subscription\SubscriptionLimits;
use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use AssignmentsBundle\Repository\Assignment\AbstractAssignmentRepository;
use Doctrine\Common\Collections\Collection;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Branchstation controller.
 *
 * @Route("branches/{branch_id}/stations")
 */
class BranchStationController extends AbstractController
{
    /**
     * @Security("is_granted('branch_station_index')")
     * @ApiDoc(
     *  section="BranchStation",
     *  description="Lists all branchStation entities.",
     *  output="ApiBundle\Entity\BranchStation",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="station_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @return array|BranchStation
     */
    public function indexAction(Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.stations'));
        }

        return $branch->getStations();
    }

    /**
     * @Security("is_granted('branch_station_new')")
     * @ApiDoc(
     *  section="BranchStation",
     *  description="Creates a new branchStation entity.",
     *  input="ApiBundle\Form\BranchStationType",
     *  output="ApiBundle\Entity\BranchStation",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\Post("/new", name="station_new")
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Branch $branch)
    {

        $user = $this->getUser();

        /** @var SubscriptionLimits*/
        $subscriptionLimits = $this->get('service.subscription_limits');

        if(!$subscriptionLimits->canCreateStation($user, $branch)) {
            throw new BadRequestHttpException($this->get('translator')->trans('you_not_allowed.create.more_station'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.station'));
        }

        $form = $this->createForm('ApiBundle\Form\BranchStationType',  new BranchStation());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $branchStation = $form->getData();
            $branchStation->setBranch($branch);
            $em->persist($branchStation);
            $em->flush();

            $this->get('service.generator.branch_access_generator')->generateCode($branchStation);

            return $branchStation;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('branch_station_show')")
     * @ApiDoc(
     *  section="BranchStation",
     *  description="Finds and displays a branchStation entity.",
     *  output="ApiBundle\Entity\BranchStation",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="station_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param BranchStation $station
     * @param Branch $branch
     * @return object
     */
    public function showAction(BranchStation $station, Branch $branch)
    {
        if(!$station){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user) || !$branch->getStations()->contains($station)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.station'));
        }

        return $station;
    }

    /**
     * @Security("is_granted('branch_station_edit')")
     * @ApiDoc(
     *  section="BranchStation",
     *  description="Displays a form to edit an existing branchStation entity.",
     *  output="ApiBundle\Entity\BranchStation",
     *  input="ApiBundle\Form\BranchStationType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="station_put")
     * @Rest\Patch("/{id}", name="station_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @param Request $request
     * @param BranchStation $branchStation
     * @param Branch $branch
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function editAction(Request $request, BranchStation $branchStation, Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.station'));
        }

        $editForm = $this->createForm('ApiBundle\Form\BranchStationType', $branchStation, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new Response();
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('branch_station_delete')")
     * @ApiDoc(
     *  section="BranchStation",
     *  description="Deletes a branchStation entity.",
     *  output="ApiBundle\Entity\BranchStation",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="station_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param BranchStation $branchStation
     * @param Branch $branch
     * @return Response
     */
    public function deleteAction(Request $request, BranchStation $branchStation, Branch $branch)
    {
        if(!$branchStation){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.station'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($branchStation);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('branch_station_generate_pin')")
     * Generates pin code for branch station
     * @ApiDoc(
     *  section="BranchStation",
     *  description="Generates pin code for branch station",
     *  output="ApiBundle\Entity\BranchStation",
     *  tags = {
     *    "Not implemented yet" = "#ff0000",
     *  },
     * )
     * @Rest\Get("/{id}/pins")
     * @Rest\View()
     * @param BranchStation $branchStation
     * @return BranchStation
     */
    public function generatePin(BranchStation $branchStation)
    {
        if(!$branchStation){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branchStation->getBranch()->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$branchStation->getDevices()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.generate.pin'));
        }

        /** @var BranchStationAccessGenerator */
        $generator = $this->get('service.generator.branch_access_generator');

        $generator->generatePin($branchStation);

        return $branchStation;
    }

    /**
     * Generates qr code for branch station
     *
     * @Security("is_granted('branch_station_qr_code')")
     * @ApiDoc(
     *  section="BranchStation",
     *  description="Generates qr code for branch station",
     *  output="ApiBundle\Entity\BranchStation",
     *  tags = {
     *    "Not implemented yet" = "#ff0000",
     *  },
     * )
     * @Rest\Get("/{id}/qr")
     * @Rest\View()
     * @param BranchStation $branchStation
     * @return JsonResponse
     */
    public function getQrCode(BranchStation $branchStation)
    {
        if(!$branchStation){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branchStation->getBranch()->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$branchStation->getDevices()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.qr_code'));
        }

        $code = $branchStation->getDeviceCode();
        $qrCode = $this->get('endroid.qrcode.factory')->create($code, ['size' => 200]);

        return new JsonResponse(['image' => $qrCode->writeDataUri()], 200);
    }

    /**
     * @Security("is_granted('branch_station_tasks')")
     * @Rest\QueryParam(name="future")
     * @ApiDoc(
     *  section="BranchStation",
     *  description="Lists all tasks by station.",
     *  output="ApiBundle\Entity\BranchStation",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\Get("/{id}/tasks", name="station_tasks_index")
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @param ParamFetcher $paramFetcher
     * @return mixed
     */
    public function getTasksByStationAction(Branch $branch, BranchStation $station, ParamFetcher $paramFetcher)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }
        /** @var $service BranchStationRoleManagement*/
        $service = $this->get('service.branch_station_role.branch_station_role_management');
        $future = !empty($paramFetcher->get('future')) && $paramFetcher->get('future');
        return $service->getTasksByStation($station, $future);
    }

    /**
     * @Security("is_granted('branch_station_tasks')")
     * @Rest\QueryParam(name="future")
     * @ApiDoc(
     *  section="BranchStation",
     *  description="Lists all tasks by station.",
     *  output="ApiBundle\Entity\BranchStation",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\Get("/{id}/all_tasks", name="station_all_tasks")
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @param ParamFetcher $paramFetcher
     * @return mixed
     */
    public function getAllTasksByStationAction(Branch $branch, BranchStation $station, ParamFetcher $paramFetcher)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        /** @var $service BranchStationRoleManagement*/
        $service = $this->get('service.branch_station_role.branch_station_role_management');
        $future = !empty($paramFetcher->get('future')) && $paramFetcher->get('future');
        return $service->getAllTasksByStation($station, $future);
    }

    /**
     *
     * @Security("is_granted('station_get_income')")
     * @ApiDoc(
     *  section="Station",
     *  description="Get station income",
     *  output="ApiBundle\Entity\BranchStation",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/{id}/get_income", name="station_get_income")
     * @Rest\View()
     * @param Request $request
     * @param BranchStation $station
     * @return mixed
     */
    public function getIncomeAction(Request $request, BranchStation $station)
    {
        if(!$station){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getBranch()->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.station'));
        }

        /** @var $service CashierReport */
        $service = $this->get('service.reports.cashier_report');

        return $service->getIncomeTodayByStation(
            $request->get('date_start'),
            $request->get('date_end'),
            $user->getCompany(),
            $station
        );
    }

    /**
     * @Security("is_granted('station_assignments_and_notifications')")
     * @Rest\QueryParam(name="date_start", nullable=false)
     * @Rest\QueryParam(name="date_end", nullable=false)
     * @ApiDoc(
     *  section="BranchStation",
     *  description="Get data from station.",
     *  output="ApiBundle\Entity\BranchStation",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/{id}/get_assignments_and_notifications", name="station_assignments_and_notifications", requirements={"id"="\d+"})
     * @Rest\View()
     * @param BranchStation $station
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getAllAssignmentAndDeviceNotification(ParamFetcher $paramFetcher, BranchStation $station)
    {
        $user = $this->getUser();
        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getBranch()->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.station'));
        }

        $date_start = new \DateTime($paramFetcher->get('date_start'));
        $date_end = new \DateTime($paramFetcher->get('date_end'));

        $offset = -intval($user->getCompany()->getTimeZone()->getOffset());

        $date_start = $date_start->modify("{$offset} hour");
        $date_end = $date_end->modify("{$offset} hour");

        /** @var $service BranchStatus */
        $service = $this->get('service.reports.branch_status');

        return $service->getAllAssignmentAndDeviceNotificationDataByStation($date_start, $date_end, $station);
    }

    /**
     * @Security("is_granted('branch_station_show')")
     * @ApiDoc(
     *  section="BranchStation",
     *  description="Get employees",
     *  output="ApiBundle\Entity\BranchStation",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/{id}/get_employees", name="stations_get_employees")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @param Branch $branch
     * @param BranchStation $station
     * @Rest\View()
     * @return mixed
     */
    public function getEmployeesGroupByShift(Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.station'));
        }

        /** @var $service BranchService */
        $service = $this->get('service.branch_service');

        return $service->getEmployeesGroupByShift($branch, $station);
    }
}
