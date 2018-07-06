<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\BranchManager;
use ApiBundle\Service\Branch\BranchService;
use ApiBundle\Service\BranchShift\BranchShiftManagement;
use ApiBundle\Service\Report\BranchStatus;
use ApiBundle\Service\Report\CashierReport as CashierReportService;
use ApiBundle\Service\Subscription\SubscriptionLimits;
use Doctrine\Common\Collections\Collection;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Branch controller.
 *
 * @Route("branches")
 */
class BranchController extends AbstractController
{
    /**
     * @Security("is_granted('branch_index')")
     * @ApiDoc(
     *  section="Branch",
     *  description="Lists all branch entities.",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="branch_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $user = $this->getUser();
        if ($user->hasRole(AbstractUser::ROLE_ADMIN)) {
            /** @var $company Company*/
            $company = $user->getCompany();
            if($company != null) {
                $data = $company->getBranches();
            } else {
                $data = null;
            }
        } elseif ($user->hasRole(AbstractUser::ROLE_SUPERVISOR)) {
            $data = $user->getBranches();
        } elseif ($user->hasRole(AbstractUser::ROLE_BRANCH_MANAGER) || $user->hasRole(AbstractUser::ROLE_CO_MANAGER)) {
            $data = $user->getBranch();
        } else {
            $em = $this->getDoctrine()->getManager();
            $data = $em->getRepository('ApiBundle:Branch')->findAll();
        }
        return $data;
    }

    /**
     * @Security("is_granted('branch_index')")
     * @ApiDoc(
     *  section="Branch",
     *  description="Lists all branch entities.",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/free_data", name="free_data")
     * @Rest\View()
     */
    public function simpleDataAction()
    {
        $user = $this->getUser();

        /** @var $service BranchService */
        $service = $this->get('service.branch_service');

        switch (true) {
            case $user->hasRole(AbstractUser::ROLE_ADMIN):
                $company = $user->getCompany();
                if($company != null) {
                    $response = $service->simpleData($company);
                } else {
                    $response = null;
                }
                break;
            case $user->hasRole(AbstractUser::ROLE_SUPERVISOR) || $user->hasRole(AbstractUser::ROLE_BRANCH_MANAGER) || $user->hasRole(AbstractUser::ROLE_CO_MANAGER):
                $response = $service->simpleData(null, $user);
                break;
            default:
                $response = [];
                break;
        }

        return $response;
    }

    /**
     * @Security("is_granted('branch_new')")
     * @ApiDoc(
     *  section="Branch",
     *  description="Creates a new branch entity.",
     *  input="ApiBundle\Form\BranchType",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="branch_new")
     * @Rest\View()
     * @param Request $request
     * @return Branch|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {

        /** @var AbstractUser */
        $user= $this->get('security.token_storage')->getToken()->getUser();

        /** @var SubscriptionLimits*/
        $subscriptionLimits = $this->get('service.subscription_limits');

        if(!$subscriptionLimits->canCreateBranch($user)) {
            throw new BadRequestHttpException($this->get('translator')->trans('you_not_allowed.create.branch'));
        }

        $branch = new Branch();

        $form = $this->createForm('ApiBundle\Form\BranchType', $branch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($branch);
            $em->flush();

            return $branch;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('branch_live_data')")
     * @ApiDoc(
     *  section="Branch",
     *  description="Get data from branch.",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/{id}/get_data_live_employees_shift", name="branches_data_employees_shift")
     * @Rest\View()
     * @param Branch $branch
     * @return mixed
     */
    public function getDataLiveEmployeesShiftsAction(Branch $branch)
    {
        if(!$branch){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.branch'));
        }

        /** @var $branch_service BranchService */
        $branch_service = $this->get('service.branch_service');

        return $branch_service->getDataLiveEmployeesShifts($branch);
    }

    /**
     * @Security("is_granted('branch_live_data')")
     * @ApiDoc(
     *  section="Branch",
     *  description="Get data from branch.",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/get_live_data", name="branches_data")
     * @Rest\View()
     * @return mixed
     */
    public function getLiveDataBranchesAction()
    {
        /** @var $user AbstractUser*/
        $user = $this->getUser();

        /** @var $service BranchStatus*/
        $service = $this->get('service.reports.branch_status');

        $data = [];
        foreach ($user->getCompany()->getBranches() as $branch) {
            $data[] = $service->getLiveData($branch);
        }

        return $data;
    }

    /**
     * @Security("is_granted('branch_live_data')")
    * @Rest\Get("/{id}/live_roles_data", name="branch_live_roles_data", requirements={"id"="\d+"})
    * @Rest\View()
    * @param Branch $branch
    * @return Branch
     */
    public function getLiveRolesDataAction(Branch $branch)
    {
        /** @var BranchService $branchService */
        $branchService = $this->get('service.branch_service');

        return $branchService->getRolesStatistic($branch);
    }

    /**
     * @Security("is_granted('branch_show')")
     * @ApiDoc(
     *  section="Branch",
     *  description="Finds and displays a branch entity.",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="branch_show", requirements={"id"="\d+"})
     * @Rest\View()
     * @param Branch $branch
     * @return Branch
     */
    public function showAction(Branch $branch)
    {

        if(!$branch){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.branch'));
        }

        return $branch;
    }


    /**
     * @Security("is_granted('branch_edit')")
     * @ApiDoc(
     *  section="Branch",
     *  description="Displays a form to edit an existing branch entity.",
     *  output="ApiBundle\Entity\Branch",
     *  input="ApiBundle\Form\BranchType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="branch_put")
     * @Rest\Patch("/{id}", name="branch_patch")
     * @param Request $request
     * @param Branch $branch
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Branch
     */
    public function editAction(Request $request, Branch $branch)
    {

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.branch'));
        }

        $editForm = $this->createForm('ApiBundle\Form\BranchType', $branch, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $branch;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('branch_delete')")
     * @ApiDoc(
     *  section="Branch",
     *  description="Deletes a branch entity.",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="branch_delete")
     * @Rest\View()
     * @param Branch $branch
     * @return Response
     */
    public function deleteAction(Branch $branch)
    {

        if(!$branch){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.branch'));
        }

        $em = $this->getDoctrine()->getManager();

        /** @var $manager BranchManager */
        $manager = $branch->getBranchManager();
        if(!empty($manager)) {
            $manager->setBranch(null);
            $em->persist($manager);
        }
        $em->remove($branch);
        $em->flush();

        return new Response();
    }


    /**
     * @Security("is_granted('branch_live_data')")
     * @ApiDoc(
     *  section="Branch",
     *  description="Get data from branch.",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/{id}/get_live_data", name="branch_live_data", requirements={"id"="\d+"})
     * @Rest\View()
     * @param Branch $branch
     * @return Response
     */
    public function getLiveDataAction(Branch $branch)
    {
        $user = $this->getUser();
        $throw = true;
        if($user->hasRole(AbstractUser::ROLE_DEVICE)) {
            foreach ($branch->getStations() as $station) {
                /** @var $station BranchStation */
                if($station->getDevices()->contains($user)) {
                    $throw = false;
                }
            }
            if($throw) {
                throw new AccessDeniedHttpException($this->get('translator')->trans('device_not_allowed.see.this.branch'));
            }
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.branch'));
        }

        /** @var $service BranchStatus */
        $service = $this->get('service.reports.branch_status');

        return $service->getLiveData($branch);
    }

    /**
     * @Security("is_granted('branch_live_data')")
     * @ApiDoc(
     *     section="Branch",
     *     description="Get employees by shift",
     *     tags = {
     *      "Implemented" = "green"
     *     }
     * )
     * @Rest\Get("/{id}/employees_by_current_shift", name="branch_employees_by_current_shift")
     * @Rest\View()
     * @param Branch $branch
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function getEmployeesByCurrentShift(Branch $branch)
    {
        /** @var $user AbstractUser */
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_ADMIN) && !$user->getCompany()->getBranches()->contains($branch))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.company'));
        }

        /** @var $service BranchShiftManagement */
        $service = $this->get('service.branch_shift.branch_shift_management');

        return $service->getEmployeesCurrentShiftByBranch($branch);
    }

    /**
     * @Rest\RequestParam(name="employee_id", allowBlank=false)
     * @Security("is_granted('branch_attach_employee')")
     * @ApiDoc(
     *     section="Branch",
     *     description="Attach employee to branch",
     *     output="ApiBundle\Entity\Branch"
     * )
     * @Rest\Post("/{id}/attach_employee", name="branch_attach_employee")
     * @Rest\View()
     * @param Branch $branch
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function attachEmployee(Branch $branch, ParamFetcher $paramFetcher)
    {

        $id = $paramFetcher->get('employee_id');

        if($branch == null || $id == null) {
            throw new BadRequestHttpException('Branch or employee_id is invalid');
        }

        /** @var BranchService $branchService */
        $branchService = $this->get('service.branch_service');

        return $branchService->attachEmployeeToBranch($branch, $id);

    }

    /**
     * @Security("is_granted('branch_detach_employee')")
     * @Rest\QueryParam(name="employee_id", nullable=false)
     * @ApiDoc(
     *     section="Branch",
     *     description="detach employee to branch",
     *     output="ApiBundle\Entity\Branch"
     * )
     * @Rest\Delete("/{id}/detach_employee", name="branch_detach_employee")
     * @Rest\View()
     * @param Branch $branch
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function detachEmployee(Branch $branch, ParamFetcher $paramFetcher)
    {

        $id = $paramFetcher->get('employee_id');

        if($branch == null || $id == null) {
            throw new BadRequestHttpException('Branch or employee_id is invalid');
        }

        /** @var BranchService $branchService */
        $branchService = $this->get('service.branch_service');

        return $branchService->detachEmployeeToBranch($branch, $id);

    }

    /**
     * @Security("is_granted('branch_get_income')")
     * @ApiDoc(
     *  section="Branch",
     *  description="Get branches income",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/get_income", name="get_income")
     * @Rest\View()
     * @param Request $request
     * @return mixed
     */
    public function getIncomeAction(Request $request)
    {
        $user = $this->getUser();

        /** @var $service BranchService */
        $service = $this->get('service.branch_service');

        return $service->getStatisticIncome(
            $request->get('date_start'),
            $request->get('date_end'),
            $user->getCompany()
        );
    }

    /**
     * @Security("is_granted('branch_get_income')")
     * @ApiDoc(
     *  section="Branch",
     *  description="Get branch income",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/{id}/get_income", name="branch_get_income_by_branch")
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @return mixed
     */
    public function getIncomeByBranchAction(Request $request, Branch $branch)
    {
        if(!$branch){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.branch'));
        }

        /** @var $service CashierReportService */
        $service = $this->get('service.reports.cashier_report');

        return $service->getIncomeByBranch(
            $request->get('date_start'),
            $request->get('date_end'),
            $branch,
            $request->get('group')
        );
    }


    /**
     * @Security("is_granted('branch_get_income')")
     * @ApiDoc(
     *  section="Branch",
     *  description="Get branch income",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/{id}/get_income_statistic", name="branch_get_income_statistic_by_branch")
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @return mixed
     */
    public function getIncomeStatisticByBranchAction(Request $request, Branch $branch)
    {
        if(!$branch){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.branch'));
        }

        /** @var $service CashierReportService */
        $service = $this->get('service.reports.cashier_report');

        return $service->getIncomeStatisticByBranch(
            new \DateTime($request->get('date_start')),
            new \DateTime($request->get('date_end')),
            $branch,
            $request->get('group')
        );
    }

    /**
     * @Security("is_granted('branch_live_data')")
     * @Rest\QueryParam(name="branches", nullable=false)
     * @ApiDoc(
     *  section="Branch",
     *  description="Get branch income",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/roles_by_branches", name="branch_roles_by_branches")
     * @Rest\View()
     * @param ParamFetcher $paramFetcher
     * @return mixed
     */
    public function getRolesByBranches(ParamFetcher $paramFetcher)
    {
        $user = $this->getUser();

        $branch_ids = $paramFetcher->get('branches');
        $response = [];
        if($branch_ids) {
            foreach ($branch_ids as $branch_id) {
                /** @var $branch Branch */
                $branch = $this->getDoctrine()->getRepository('ApiBundle:Branch')->find($branch_id);
                if (!empty($branch)) {
                    if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
                        throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.branch'));
                    }
                    foreach ($branch->getStations() as $station) {
                        /** @var $station BranchStation*/
                        foreach ($station->getRoles() as $role) {
                            /** @var $role AbstractBranchStationRole */
                            $response[] = array(
                                'id' => $role->getId(),
                                'stations_id' => $role->getBranchStationId(),
                                'title' => $role->getRole(),
                                'color' => $role->getColor()
                            );
                        }
                    }
                }
            }
        }
        return $response;
    }

    /**
     * @Security("is_granted('branch_live_data')")
     * @Rest\QueryParam(name="date_start", nullable=false)
     * @Rest\QueryParam(name="date_end", nullable=false)
     * @ApiDoc(
     *  section="Branch",
     *  description="Get data from branch.",
     *  output="ApiBundle\Entity\Branch",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/{id}/get_assignments_and_notifications", name="branch_assignments_and_notifications", requirements={"id"="\d+"})
     * @Rest\View()
     * @param Branch $branch
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getAllAssignmentAndDeviceNotification(ParamFetcher $paramFetcher, Branch $branch)
    {
        $user = $this->getUser();
        $throw = true;
        if($user->hasRole(AbstractUser::ROLE_DEVICE)) {
            foreach ($branch->getStations() as $station) {
                /** @var $station BranchStation */
                if($station->getDevices()->contains($user)) {
                    $throw = false;
                }
            }
            if($throw) {
                throw new AccessDeniedHttpException($this->get('translator')->trans('device_not_allowed.see.this.branch'));
            }
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.branch'));
        }

        $date_start = new \DateTime($paramFetcher->get('date_start'));
        $date_end = new \DateTime($paramFetcher->get('date_end'));

        $offset = -intval($user->getCompany()->getTimeZone()->getOffset());

        $date_start = $date_start->modify("{$offset} hour");
        $date_end = $date_end->modify("{$offset} hour");

        /** @var $service BranchStatus */
        $service = $this->get('service.reports.branch_status');

        return $service->getAllAssignmentAndDeviceNotificationData($date_start, $date_end, $branch);
    }
}
