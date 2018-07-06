<?php

namespace ApiBundle\Controller\Report;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Report\CommodityReport;
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
 * Commodity report controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/reports/commodity_reports")
 */
class CommodityReportController extends AbstractController
{
    /**
     * @Security("is_granted('report_commodity_report_index')")
     * @ApiDoc(
     *  section="[Report] CommodityReport",
     *  description="Lists all commodityReport entities.",
     *  output="ApiBundle\Entity\Report\CommodityReport",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="report_commodity_report_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @param Request $request
     * @return Collection|CommodityReport
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

        return $pager->paginate($station->getCommodityReports()->toArray(), $page, $this->getParameter('page_range')['commodity_reports_per_page']);
    }

    /**
     * @Security("is_granted('report_commodity_report_new')")
     * @ApiDoc(
     *  section="[Report] CommodityReport",
     *  description="Creates a new commodityReport entity.",
     *  input="ApiBundle\Form\Report\CommodityReportType",
     *  output="ApiBundle\Entity\Report\CommodityReport",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="report_commodity_report_new")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @param BranchStation $station
     * @return CommodityReport|\Symfony\Component\HttpFoundation\JsonResponse
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

        $form = $this->createForm('ApiBundle\Form\Report\CommodityReportType', new Commodityreport());
        $form->handleRequest($request);

        /** @var $service BranchShiftManagement */
        $service = $this->get('service.branch_shift.branch_shift_management');
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $shift = $service->getCurrentShiftByStation($station);
            /** @var $commodityReport CommodityReport*/
            $commodityReport = $form->getData();
            $commodityReport->setBranchStation($station);
            $commodityReport->setBranchShift($shift);
            $em->persist($commodityReport);
            $em->flush();

            return $commodityReport;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('report_commodity_report_show')")
     * @ApiDoc(
     *  section="[Report] CommodityReport",
     *  description="Finds and displays a commodityReport entity.",
     *  output="ApiBundle\Entity\Report\CommodityReport",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="report_commodity_report_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param CommodityReport $commodityReport
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(CommodityReport $commodityReport, Branch $branch, BranchStation $station)
    {
        if(!$commodityReport){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('device_not_allowed.see.this.report'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user) || !$station->getCommodityReports()->contains($commodityReport)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.report'));
        }

        return $commodityReport;
    }

    /**
     * @Security("is_granted('report_commodity_report_edit')")
     * @ApiDoc(
     *  section="[Report] CommodityReport",
     *  description="Displays a form to edit an existing commodityReport entity.",
     *  output="ApiBundle\Entity\Report\CommodityReport",
     *  input="ApiBundle\Form\Report\CommodityReportType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="report_commodity_report_put")
     * @Rest\Patch("/{id}", name="report_commodity_report_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @param Request $request
     * @param CommodityReport $commodityReport
     * @param Branch $branch
     * @return CommodityReport|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, CommodityReport $commodityReport, Branch $branch)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.report'));
        }

        $editForm = $this->createForm('ApiBundle\Form\Report\CommodityReportType', $commodityReport, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $commodityReport;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('report_commodity_report_delete')")
     * @ApiDoc(
     *  section="[Report] CommodityReport",
     *  description="Deletes a commodityReport entity.",
     *  output="ApiBundle\Entity\Report\CommodityReport",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="report_commodity_report_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param CommodityReport $commodityReport
     * @param Branch $branch
     * @return Response
     */
    public function deleteAction(Request $request, CommodityReport $commodityReport, Branch $branch)
    {
        if(!$commodityReport){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.report'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($commodityReport);
        $em->flush();

        return new Response();
    }
}
