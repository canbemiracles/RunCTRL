<?php
/**
 * Created by PhpStorm.
 * User: mail
 * Date: 11.12.2017
 * Time: 15:46
 */

namespace ApiBundle\Controller\Report;


use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Repository\Report\AbstractReportRepository;
use ApiBundle\Service\Elastica\SearchService;
use ApiBundle\Service\Report\CompanyStatusReport;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Report controller.
 *
 * @Route("companies/{company_id}/reports")
 */
class ReportController extends AbstractController
{
    /**
     * @Security("is_granted('company_reports')")
     * @Rest\QueryParam(name="type", nullable=true)
     * @Rest\QueryParam(name="all", nullable=true)
     * @Rest\QueryParam(name="managers", nullable=true)
     * @Rest\QueryParam(name="branches", nullable=true)
     * @Rest\QueryParam(name="regions", nullable=true)
     * @Rest\QueryParam(name="from_datetime", nullable=true)
     * @Rest\QueryParam(name="to_datetime", nullable=true)
     * @ApiDoc(
     *  section="[Report] All Reports",
     *  description="Lists all non archived Reports entities.",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/", name="company_reports")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Company $company
     * @param ParamFetcher $paramFetcher
     * @param Request $request
     * @return
     */
    public function indexAction(ParamFetcher $paramFetcher, Company $company, Request $request)
    {
        $type = $paramFetcher->get('type');
        $managers = $paramFetcher->get('managers');
        $branches = $paramFetcher->get('branches');
        $regions = $paramFetcher->get('regions');
        $from_datetime = $paramFetcher->get('from_datetime');
        $to_datetime= $paramFetcher->get('to_datetime');

        $user = $this->getUser();

        $em = $this->getDoctrine();

        if(!$company->getUsers()->contains($user))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.reports'));
        }

        $page = 1;

        if($request->query->get('page') != null) {
            $page = $request->query->get('page');
            $request->query->remove('page');
        }

        $pager = $this->get('knp_paginator');
        $all_reports = !is_null($paramFetcher->get('all'));

        /** @var $repository AbstractReportRepository*/
        $repository = $em->getRepository('ApiBundle:Report\AbstractReport');

        $managers = !empty($managers) ? $managers : null;
        $branches = !empty($branches) ? $branches : null;
        $regions = !empty($regions) ? $regions : null;

        $results = $repository->getCompanyReports($company, $type, $all_reports, $managers, $branches, $regions,
            $from_datetime, $to_datetime);

        return $pager->paginate($results, $page, 10);
    }

    /**
     * @Security("is_granted('company_archived_reports')")
     * @Rest\QueryParam(name="type", nullable=true)
     * @Rest\QueryParam(name="all", nullable=true)
     * @Rest\QueryParam(name="managers", nullable=true)
     * @Rest\QueryParam(name="branches", nullable=true)
     * @Rest\QueryParam(name="regions", nullable=true)
     * @ApiDoc(
     *  section="[Report] All Archived Reports",
     *  description="Lists all archived Reports entities.",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/archived", name="company_archived_reports")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Company $company
     * @param ParamFetcher $paramFetcher
     * @param Request $request
     * @return
     */
    public function archivedReportsAction(ParamFetcher $paramFetcher, Company $company, Request $request)
    {
        $type = $paramFetcher->get('type');
        $managers = $paramFetcher->get('managers');
        $branches = $paramFetcher->get('branches');
        $regions = $paramFetcher->get('regions');

        $user = $this->getUser();

        $em = $this->getDoctrine();

        if(!$company->getUsers()->contains($user))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.reports'));
        }

        $page = 1;

        if($request->query->get('page') != null) {
            $page = $request->query->get('page');
            $request->query->remove('page');
        }

        $pager = $this->get('knp_paginator');
        $all_reports = !is_null($paramFetcher->get('all'));

        /** @var $repository AbstractReportRepository*/
        $repository = $em->getRepository('ApiBundle:Report\AbstractReport');

        $managers = !empty($managers) ? $managers : null;
        $branches = !empty($branches) ? $branches : null;
        $regions = !empty($regions) ? $regions : null;

        $results = $repository->getArchivedCompanyReports($company, $type, $all_reports, $managers, $branches, $regions);

        return $pager->paginate($results, $page, 10);
    }

    /**
     * @Security("is_granted('search_reports')")
     * @ApiDoc(
     *  section="[Report] AbstractReport",
     *  description="Lists all reports entities.",
     *  output="ApiBundle\Entity\Report\AbstractReport",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Post("/search", name="search_reports")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Company $company
     * @param Request $request
     * @return array
     */
    public function searchReports(Company $company, Request $request)
    {
        /** @var $searchService SearchService*/
        $searchService = $this->get('service.elastica.search');
        if($request->get('branch')) {
            $branch = $this->getDoctrine()->getRepository('ApiBundle:Branch')->find($request->get('branch'));
        } else {
            $branch = null;
        }
        /** @var $response array*/
        return $searchService->search(
            $request->get('type'),
            $request->get('term'),
            $company,
            $branch
        );
    }

    /**
     * @Security("is_granted('list_report_today')")
     * @Rest\QueryParam(name="station")
     * @ApiDoc(
     *  section="[Report] AbstractReport",
     *  description="Lists all reports today group by type.",
     *  output="ApiBundle\Entity\Report\AbstractReport",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/get_list_reports_today", name="get_list_reports_today")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Company $company
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function getListReportsToday(Company $company, ParamFetcher $paramFetcher)
    {
        $user = $this->getUser();

        /** @var BranchStation $station */
        $station = $this->getDoctrine()->getRepository("ApiBundle:BranchStation")->findOneBy(['id' => $paramFetcher->get('station')]);

        if(empty($station)) {
            throw new BadRequestHttpException($this->get('translator')->trans('shift_management.station_not_found'));
        }

        if($user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user))
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('device_not_allowed.see.these.reports'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$station->getBranch()->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.reports'));
        }

        if(!$station->getBranch()->getCompanyId() === $company->getId()) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.reports'));
        }

        /** @var $service CompanyStatusReport */
        $service = $this->get('service.reports.company_status_report');

        return $service->getReportsTodayByStation($company, $station);
    }

}