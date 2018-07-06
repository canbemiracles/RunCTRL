<?php

namespace ApiBundle\Controller\Report;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Report\ProblemReport;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Service\BranchShift\BranchShiftManagement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
 * Problem report controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/reports/problem_reports")
 */
class ProblemReportController extends AbstractController
{
    /**
     * @Security("is_granted('report_problem_report_index')")
     * @ApiDoc(
     *  section="[Report] ProblemReport",
     *  description="Lists all problemReport entities.",
     *  output="ApiBundle\Entity\Report\ProblemReport",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="report_problem_report_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @param Request $request
     * @return Collection|ProblemReport
     */
    public function indexAction(Branch $branch, BranchStation $station, Request $request)
    {
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('device_not_allowed.see.these.reports'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.reports'));
        }

        $page = 1;

        if($request->query->get('page') != null) {
            $page = $request->query->get('page');
            $request->query->remove('page');
        }

        $pager = $this->get('knp_paginator');

        return $pager->paginate($station->getProblemReports()->toArray(), $page, $this->getParameter('page_range')['problem_reports_per_page']);
    }

    /**
     * @Security("is_granted('report_problem_report_new')")
     * @ApiDoc(
     *  section="[Report] ProblemReport",
     *  description="Creates a new problemReport entity.",
     *  input="ApiBundle\Form\Report\ProblemReportType",
     *  output="ApiBundle\Entity\Report\ProblemReport",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="report_problem_report_new")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @param BranchStation $station
     * @return ProblemReport|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('device_not_allowed.create.report'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.report'));
        }

        $form = $this->createForm('ApiBundle\Form\Report\ProblemReportType', new Problemreport());
        $form->handleRequest($request);

        /** @var $service BranchShiftManagement */
        $service = $this->get('service.branch_shift.branch_shift_management');
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $shift = $service->getCurrentShiftByStation($station);
            $problemReport = $form->getData();
            $problemReport->setBranchStation($station);
            $problemReport->setBranchShift($shift);
            $em->persist($problemReport);
            $em->flush();

            return $problemReport;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('report_problem_report_show')")
     * @ApiDoc(
     *  section="[Report] ProblemReport",
     *  description="Finds and displays a problemReport entity.",
     *  output="ApiBundle\Entity\Report\ProblemReport",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="report_problem_report_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param ProblemReport $problemReport
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(ProblemReport $problemReport, Branch $branch, BranchStation $station)
    {
        if(!$problemReport) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('device_not_allowed.see.this.report'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user) || !$station->getProblemReports()->contains($problemReport)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.report'));
        }

        return $problemReport;
    }

    /**
     * @Security("is_granted('report_problem_report_edit')")
     * @ApiDoc(
     *  section="[Report] ProblemReport",
     *  description="Displays a form to edit an existing problemReport entity.",
     *  output="ApiBundle\Entity\Report\ProblemReport",
     *  input="ApiBundle\Form\Report\ProblemReportType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="report_problem_report_put")
     * @Rest\Patch("/{id}", name="report_problem_report_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @param Request $request
     * @param ProblemReport $problemReport
     * @param Branch $branch
     * @return ProblemReport|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, ProblemReport $problemReport, Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.report'));
        }

        $editForm = $this->createForm('ApiBundle\Form\Report\ProblemReportType', $problemReport, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $problemReport;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('report_problem_report_delete')")
     * @ApiDoc(
     *  section="[Report] ProblemReport",
     *  description="Remove ProblemReport by id",
     *  output="ApiBundle\Entity\Report\ProblemReport",
     *  tags = {
     *     "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="report_problem_report_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param ProblemReport $problemReport
     * @param Branch $branch
     * @return Response
     */
    public function deleteAction(Request $request, ProblemReport $problemReport, Branch $branch)
    {
        if(!$problemReport){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.report'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($problemReport);
        $em->flush();

        return new Response();
    }
}
