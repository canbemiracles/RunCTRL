<?php

namespace ApiBundle\Controller\Report;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Report\CashierReport;
use ApiBundle\Entity\Report\CashierReportGroup;
use ApiBundle\Entity\User\AbstractUser;
use FOS\RestBundle\Request\ParamFetcher;
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

/**
 * Cashier report group controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/reports/cashier_report_groups")
 */
class CashierReportGroupController extends AbstractController
{

    /**
     * @Security("is_granted('report_cashier_report_new')")
     * @ApiDoc(
     *  section="[Report] CashierReportGroup",
     *  description="Creates a new CashierReportGroup entity.",
     *  input="ApiBundle\Form\Report\CashierReportGroupType",
     *  output="ApiBundle\Entity\Report\CashierReportGroup",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Post("/new", name="cashier_report_group_new")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @param BranchStation $station
     * @return CashierReportGroup|\Symfony\Component\HttpFoundation\JsonResponse
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

        $cashier_report_group = new CashierReportGroup();

        $form = $this->createForm('ApiBundle\Form\Report\CashierReportGroupType', $cashier_report_group);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cashier_report_group);
            $em->flush();

            return $cashier_report_group;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Rest\QueryParam(name="group")
     * @Security("is_granted('report_cashier_report_index')")
     * @ApiDoc(
     *  section="[Report] CashierReportGroup",
     *  description="Get reports by group",
     *  output="ApiBundle\Entity\Report\CashierReportGroup",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/get_reports", name="get_reports_by_group")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @param BranchStation $station
     * @param ParamFetcher $paramFetcher
     * @param
     * @return mixed
     */
    public function getReportsByGroupAction(Request $request, Branch $branch, BranchStation $station, ParamFetcher $paramFetcher)
    {
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('device_not_allowed.see.these.report'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.report'));
        }

        /** @var $service \ApiBundle\Service\Report\CashierReport */
        $service = $this->get('service.reports.cashier_report');

        return $service->getIncomeTodayByGroup(
            $user->getCompany(),
            $station,
            $paramFetcher->get('group')
        );
    }
}
