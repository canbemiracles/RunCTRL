<?php

namespace ApiBundle\Controller\Report;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\Report\EndOfShiftReport;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\User\AbstractUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Doctrine\Common\Collections\Collection;

/**
 * EndOfShiftReport report controller.
 *
 * @Route("branches/{branch_id}/branch_shifts/{shift_id}/reports/end_of_shift_reports")
 */
class EndOfShiftReportController extends AbstractController
{
    /**
     * @Security("is_granted('end_of_shift_report_index')")
     * @ApiDoc(
     *  section="[Report] EndOfShiftReport",
     *  description="Lists all EndOfShiftReport entities.",
     *  output="ApiBundle\Entity\Report\EndOfShiftReport",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="end_of_shift_report_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("branch_shift", options={"mapping": {"shift_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchShift $branch_shift
     * @param Request $request
     * @return Collection|EndOfShiftReport
     */
    public function indexAction(Branch $branch, BranchShift $branch_shift, Request $request)
    {
        $user = $this->getUser();

        if (!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user) && !$branch->getShifts()->contains($branch_shift)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.reports'));
        }

        $page = 1;

        if ($request->query->get('page') != null) {
            $page = $request->query->get('page');
            $request->query->remove('page');
        }

        $pager = $this->get('knp_paginator');

        return $pager->paginate($branch_shift->getEndOfShiftReports()->toArray(), $page, $this->getParameter('page_range')['end_of_shift_reports_per_page']);
    }
}