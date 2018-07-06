<?php

namespace ApiBundle\Controller\Report;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Report\CashierReport;
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
use Symfony\Component\Translation\Translator;
use ApiBundle\Service\Currency\CurrencyService as CurrencyLayer;

/**
 * Cashier report controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/reports/cashier_reports")
 */
class CashierReportController extends AbstractController
{
    /**
     * @Security("is_granted('report_cashier_report_index')")
     * @ApiDoc(
     *  section="[Report] CashierReport",
     *  description="Lists all cashierReport entities.",
     *  output="ApiBundle\Entity\Report\CashierReport",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/", name="report_cashier_report_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @param Request $request
     * @return Collection|CashierReport
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

        return $pager->paginate($station->getCashierReports()->toArray(), $page, $this->getParameter('page_range')['cashier_reports_per_page']);
    }

    /**
     * @Security("is_granted('report_cashier_report_new')")
     * @ApiDoc(
     *  section="[Report] CashierReport",
     *  description="Creates a new cashierReport entity.",
     *  input="ApiBundle\Form\Report\CashierReportType",
     *  output="ApiBundle\Entity\Report\CashierReport",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Post("/new", name="report_cashier_report_new")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @param BranchStation $station
     * @return CashierReport|\Symfony\Component\HttpFoundation\JsonResponse
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

        $form = $this->createForm('ApiBundle\Form\Report\CashierReportType', new Cashierreport());
        $form->handleRequest($request);

        /** @var $service BranchShiftManagement */
        $service = $this->get('service.branch_shift.branch_shift_management');
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $shift = $service->getCurrentShiftByStation($station);
            /** @var $cashierReport CashierReport*/
            $cashierReport = $form->getData();
            $cashierReport->setBranchStation($station);
            $cashierReport->setBranchShift($shift);
            $em->persist($cashierReport);
            $em->flush();

            return $cashierReport;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('report_cashier_report_show')")
     * @ApiDoc(
     *  section="[Report] CashierReport",
     *  description="Finds and displays a cashierReport entity.",
     *  output="ApiBundle\Entity\Report\CashierReport",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="report_cashier_report_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param CashierReport $cashierReport
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(CashierReport $cashierReport, Branch $branch, BranchStation $station)
    {
        if(!$cashierReport) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('device_not_allowed.see.this.report'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user) || !$station->getCashierReports()->contains($cashierReport)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.report'));
        }

        return $cashierReport;
    }

    /**
     * @Security("is_granted('report_cashier_report_edit')")
     * @ApiDoc(
     *  section="[Report] CashierReport",
     *  description="Displays a form to edit an existing cashierReport entity.",
     *  output="ApiBundle\Entity\Report\CashierReport",
     *  input="ApiBundle\Form\Report\CashierReportType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="report_cashier_report_put")
     * @Rest\Patch("/{id}", name="report_cashier_report_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @param Request $request
     * @param CashierReport $cashierReport
     * @param Branch $branch
     * @return CashierReport|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, CashierReport $cashierReport, Branch $branch)
    {
        $user = $this->getUser();
        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.report'));
        }

        $editForm = $this->createForm('ApiBundle\Form\Report\CashierReportType', $cashierReport, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $cashierReport;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('report_cashier_report_delete')")
     * @ApiDoc(
     *  section="[Report] CashierReport",
     *  description="Deletes a cashierReport entity.",
     *  output="ApiBundle\Entity\Report\CashierReport",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="report_cashier_report_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param CashierReport $cashierReport
     * @param Branch $branch
     * @return Response
     */
    public function deleteAction(Request $request, CashierReport $cashierReport, Branch $branch)
    {
        if(!$cashierReport){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();
        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.report'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($cashierReport);
        $em->flush();

        return new Response();
    }
}
