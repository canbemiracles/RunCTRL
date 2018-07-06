<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\Report\EndOfShiftReport;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Service\Branch\BranchService;
use ApiBundle\Service\BranchShift\BranchShiftManagement;
use Doctrine\Common\Collections\Collection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use FOS\RestBundle\Request\ParamFetcher;

/**
 * Branchshift controller.
 *
 * @Route("branches/{branch_id}/shifts")
 */
class BranchShiftController extends AbstractController
{
    /**
     * @Security("is_granted('branch_shift_index')")
     * @ApiDoc(
     *  section="BranchShift",
     *  description="Lists all branchShift entities.",
     *  output="ApiBundle\Entity\BranchShift",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="shift_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @return array|Collection BranchShift
     */
    public function indexAction(Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.shifts'));
        }

        return $branch->getShifts();
    }

    /**
     * @Security("is_granted('branch_shift_new')")
     * @ApiDoc(
     *  section="BranchShift",
     *  description="Creates a new branchShift entity.",
     *  input="ApiBundle\Form\BranchShiftType",
     *  output="ApiBundle\Entity\BranchShift",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\Post("/new", name="branchshift_new")
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.shift'));
        }

        $form = $this->createForm('ApiBundle\Form\BranchShiftType',  new Branchshift());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $branchShift = $form->getData();
            $branchShift->setBranch($branch);
            $em->persist($branchShift);
            $em->flush();

            return $branchShift;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('get_current_shift')")
     * @Rest\QueryParam(name="station", nullable=false)
     * @ApiDoc(
     *  section="BranchShift",
     *  description="Report generation.",
     *  output="ApiBundle\Entity\BranchShift",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/get_current_shift", name="get_current_shift")
     * @param ParamFetcher $paramFetcher
     * @Rest\View()
     * @return mixed
     */
    public function getCurrentShiftByStationAction(ParamFetcher $paramFetcher)
    {
        /** @var $service BranchShiftManagement */
        $service  = $this->get('service.branch_shift.branch_shift_management');
        return $service->getCurrentShiftByStation($paramFetcher->get('station'));
    }


    /**
     * @Security("is_granted('branch_shift_index')")
     * @ApiDoc(
     *  section="BranchShift",
     *  description="",
     *  output="ApiBundle\Entity\BranchShift",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/get_employees", name="shifts_get_employees")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @param Branch $branch
     * @Rest\View()
     * @return mixed
     */
    public function getEmployeesGroupByShift(Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.shifts'));
        }

        /** @var $service BranchService */
        $service = $this->get('service.branch_service');

        return $service->getEmployeesGroupByShift($branch);
    }


    /**
     * @Security("is_granted('branch_shift_show')")
     * @ApiDoc(
     *  section="BranchShift",
     *  description="Finds and displays a branchShift entity.",
     *  output="ApiBundle\Entity\BranchShift",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="branchshift_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param BranchShift $shift
     * @param Branch $branch
     * @return object
     */
    public function showAction(BranchShift $shift, Branch $branch)
    {
        if(!$shift){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user) || !$branch->getShifts()->contains($shift)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.shift'));
        }

        return $shift;
    }

    /**
     * @Security("is_granted('branch_shift_edit')")
     * @ApiDoc(
     *  section="BranchShift",
     *  description="Displays a form to edit an existing branchShift entity.",
     *  output="ApiBundle\Entity\BranchShift",
     *  input="ApiBundle\Form\BranchShiftType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="branchshift_put")
     * @Rest\Patch("/{id}", name="branchshift_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @param Request $request
     * @param BranchShift $branchShift
     * @param Branch $branch
     * @return \Symfony\Component\HttpFoundation\JsonResponse|BranchShift
     */
    public function editAction(Request $request, BranchShift $branchShift, Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.shift'));
        }

        $editForm = $this->createForm('ApiBundle\Form\BranchShiftType', $branchShift, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $branchShift;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('branch_shift_delete')")
     * @ApiDoc(
     *  section="BranchShift",
     *  description="Deletes a branchShift entity.",
     *  output="ApiBundle\Entity\BranchShift",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="branchshift_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param BranchShift $branchShift
     * @param Branch $branch
     * @return Response
     */
    public function deleteAction(Request $request, BranchShift $branchShift, Branch $branch)
    {
        if(!$branchShift){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.shift'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($branchShift);
        $em->flush();

        return new Response();
    }


    /**
     * @Security("is_granted('branch_shift_open_employee')")
     * @ApiDoc(
     *  section="BranchShift",
     *  description="Open shift by employee.",
     *  output="ApiBundle\Entity\BranchShift",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/open_shift_employee", name="branch_shift_open_employee")
     * @Rest\View()
     * @param Request $request
     * @return BranchShift $branchShift
     */
    public function openShiftEmployeeAction(Request $request) {
        /** @var $service BranchShiftManagement*/
        $service = $this->get('service.branch_shift.branch_shift_management');
        return $service->openShiftByEmployee($this->getUser(),
            $request->get('branch_station'),
            $request->get('employee'),
            $request->get('role')
        );
    }

    /**
     * @Security("is_granted('branch_shift_logout')")
     * @ApiDoc(
     *  section="BranchShift",
     *  description="Close shift by employee.",
     *  output="ApiBundle\Entity\BranchShift",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/logout", name="branch_shift_logout")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @return BranchShift $branchShift
     */
    public function logout(Request $request, Branch $branch)
    {
        /** @var $service BranchShiftManagement*/
        $service = $this->get('service.branch_shift.branch_shift_management');
        return $service->logoutShiftByEmployee(
            $request->get('employee'),
            $request->get('shift'),
            $branch,
            $request->get('branch_station'),
            $request->get('role')
        );
    }

    /**
     * @Security("is_granted('branch_shift_open_branch_manager')")
     * @ApiDoc(
     *  section="BranchShift",
     *  description="Open shift by manager.",
     *  output="ApiBundle\Entity\BranchShift",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/open_shift_branch_manager", name="branch_shift_open_branch_manager")
     * @Rest\View()
     * @param Request $request
     * @return BranchShift $branchShift
     */
    public function openShiftBranchManagerAction(Request $request) {
        /** @var $service BranchShiftManagement*/
        $service = $this->get('service.branch_shift.branch_shift_management');
        return $service->openShiftByBranchManager($this->getUser(), $request->get('branch_station'));
    }

    /**
     * @Security("is_granted('branch_shift_logout_branch_manager')")
     * @ApiDoc(
     *  section="BranchShift",
     *  description="Close shift by manager.",
     *  output="ApiBundle\Entity\BranchShift",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/logout_branch_manager", name="branch_shift_logout_branch_manager")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @return BranchShift $branchShift
     */
    public function logoutBranchManager(Request $request, Branch $branch)
    {
        /** @var $service BranchShiftManagement*/
        $service = $this->get('service.branch_shift.branch_shift_management');
        return $service->logoutShiftByBranchManager(
            $this->getUser(),
            $request->get('shift'),
            $branch
        );
    }

    /**
     * @Security("is_granted('branch_shift_accept_open_assignment')")
     * @ApiDoc(
     *  section="BranchShift",
     *  description="Accept or not accept tasks of previous employee.",
     *  output="ApiBundle\Entity\BranchShift",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/accept_open_assignment", name="branch_shift_accept_open_assignment")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @return BranchShift $branchShift
     */
    public function accept_open_assignment(Request $request)
    {
        return $this->get('service.branch_shift.branch_shift_management')->accept_open_assignment(
            $request->get('employee'),
            $request->get('shift'),
            $request->get('accept')
        );
    }

    /**
     * @Security("is_granted('branch_shift_generate_end_of_shift_report')")
     * @ApiDoc(
     *  section="BranchShift",
     *  description="Report generation.",
     *  output="ApiBundle\Entity\BranchShift",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/{id}/report_generate/{id_report}", name="generate_end_of_shift_report")
     * @ParamConverter("endOfShiftReport", options={"mapping": {"id_report" : "id"}})
     * @Rest\View()
     * @param BranchShift $shift
     * @param EndOfShiftReport $endOfShiftReport
     * @return mixed
     */
    public function generateEndOfShiftReport(BranchShift $shift, EndOfShiftReport $endOfShiftReport) {
        $user = $this->getUser();
        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$shift->getBranch()->getCompany()->getUsers()->contains($user)
            || !$shift->getEndOfShiftReports()->contains($endOfShiftReport))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.generate.report'));
        }

        return $this->get('service.reports.end_of_shift_report')->generateEndOfShiftReport($endOfShiftReport);
    }

}
