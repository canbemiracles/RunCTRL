<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Service\Branch\BranchService;
use ApiBundle\Service\BranchShift\BranchShiftManagement;
use ApiBundle\Service\BranchStationRole\BranchStationRoleManagement;
use ApiBundle\Service\Report\CompanyStatusReport;
use ApiBundle\Service\Subscription\TrialManager;
use Doctrine\Common\Collections\Collection;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Company controller.
 *
 * @Route("companies")
 */
class CompanyController extends AbstractController
{
    /**
     * @Security("is_granted('company_index')")
     * @ApiDoc(
     *  section="Company",
     *  description="Lists all company entities.",
     *  output="ApiBundle\Entity\Company",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="company_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $user = $this->getUser();
        if($user->hasRole(AbstractUser::ROLE_ADMIN)) {
            return $user->getCompany();
        }

        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:Company')->findAll();
    }

    /**
     * @Security("is_granted('company_new')")
     * @ApiDoc(
     *  section="Company",
     *  description="Creates a new company entity.",
     *  input="ApiBundle\Form\CompanyType",
     *  output="ApiBundle\Entity\Company",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="company_new")
     * @Rest\View()
     * @param Request $request
     * @return Company|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        /** @var $user AbstractUser */
        $user = $this->getUser();

        $company = new Company();
        $form = $this->createForm('ApiBundle\Form\CompanyType', $company);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $user->setCompany($company);
            $em->persist($user);
            $em->flush();

            return $company;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('company_show')")
     * @ApiDoc(
     *  section="Company",
     *  description="Get all stations of company.",
     *  output="ApiBundle\Entity\Company",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}/stations", name="company_stations")
     * @Rest\View()
     * @param Company $company
     * @return array BranchStation
     */
    public function getStationsAction(Company $company)
    {
        if(!$company){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.company'));
        }
        $stations = [];
        foreach ($company->getBranches() as $branch) {
            /** @var $branch Branch */
            foreach ($branch->getStations() as $station) {
                /** @var $station BranchStation*/
                $stations[] = $station;
            }
        }
        return $stations;
    }

    /**
     * @Security("is_granted('company_show')")
     * @ApiDoc(
     *  section="Company",
     *  description="Get all roles of company.",
     *  output="ApiBundle\Entity\Company",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}/roles", name="company_roles")
     * @Rest\View()
     * @param Company $company
     * @return array AbstractBranchStationRole
     */
    public function getRolesAction(Company $company)
    {
        if(!$company){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.company'));
        }
        $roles = [];
        foreach ($company->getBranches() as $branch) {
            /** @var $branch Branch */
            foreach ($branch->getStations() as $station) {
                /** @var $station BranchStation*/
                foreach ($station->getRoles() as $role) {
                    /** @var $role AbstractBranchStationRole */
                    $roles[] = $role;
                }
            }
        }
        return $roles;
    }

    /**
     * @Security("is_granted('company_show')")
     * @ApiDoc(
     *  section="Company",
     *  description="Finds and displays a company entity.",
     *  output="ApiBundle\Entity\Company",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="company_show")
     * @Rest\View()
     * @param Company $company
     * @return Company
     */
    public function showAction(Company $company)
    {
        if(!$company){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.company'));
        }
        return $company;
    }

    /**
     * @Security("is_granted('company_edit')")
     * @ApiDoc(
     *  section="Company",
     *  description="Displays a form to edit an existing company entity.",
     *  output="ApiBundle\Entity\Company",
     *  input="ApiBundle\Form\CompanyType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="company_put")
     * @Rest\Patch("/{id}", name="company_patch")
     * @param Request $request
     * @param Company $company
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Company
     */
    public function editAction(Request $request, Company $company)
    {

        if(!$company){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.company'));
        }

        if(!empty($request->get('weekends'))) {
            foreach ($company->getWeekends() as $weekend) {
                $company->removeWeekend($weekend);
            }
        }

        $editForm = $this->createForm('ApiBundle\Form\CompanyType', $company, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $company;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('company_delete')")
     * @ApiDoc(
     *  section="Company",
     *  description="Deletes a company entity.",
     *  output="ApiBundle\Entity\Company",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="company_delete")
     * @Rest\View()
     * @param Company $company
     * @return Response
     */
    public function deleteAction(Company $company)
    {
        if(!$company){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.company'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($company);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('company_live_data')")
     * @ApiDoc(
     *  section="Company",
     *  description="Get data from company.",
     *  output="ApiBundle\Entity\Company",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/{id}/get_live_data", name="get_live_data")
     * @Rest\View()
     * @param Company $company
     * @return Response
     */
    public function getLiveDataAction(Company $company)
    {
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_DEVICE) && !$company->getBranches()->getStations()->getDevices()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('device_not_allowed.see.this.company'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.company'));
        }

        /** @var $service CompanyStatusReport*/
        $service = $this->get('service.reports.company_status_report');

        return $service->getLiveData($company);
    }

    /**
     * @Security("is_granted('company_activate_trial')")
     * @ApiDoc(
     *     section="Company",
     *     description="Activates trial for company",
     *     tags = {
     *      "Implemented" = "green"
     *     }
     * )
     * @Rest\Get("/{id}/activate_trial", name="company_activate_trial")
     * @Rest\View()
     * @param Company $company
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function activateTrialAction(Company $company)
    {
        /** @var $user AbstractUser */
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_ADMIN) && $user->getCompany() !== $company)
        {
            throw new AccessDeniedHttpException('You are not allowed to make changes to this company');
        }

        /** @var $trialManager TrialManager*/
        $trialManager = $this->get('service.trial_manager');

        return $trialManager->createTrialPeriod($company);
    }

    /**
     * @Rest\RequestParam(name="role", allowBlank=false)
     * @Security("is_granted('company_show')")
     * @ApiDoc(
     *     section="Company",
     *     description="Searches for roles in company by term",
     *     tags = {
     *      "Implemented" = "green"
     *     }
     * )
     * @Rest\Post("/{id}/search_roles", name="company_search_roles")
     * @Rest\View()
     * @param Company $company
     * @return array
     */
    public function findRolesAction(Company $company, ParamFetcher $paramFetcher)
    {
        /** @var $user AbstractUser */
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_ADMIN) && $user->getCompany() !== $company)
        {
            throw new AccessDeniedHttpException('You are not allowed to make changes to this company');
        }

        /** @var BranchStationRoleManagement $roleManagement */
        $roleManagement = $this->get('service.branch_station_role.branch_station_role_management');

        return $roleManagement->searchRoles($company, $paramFetcher->get('role'));
    }

    /**
     * @Security("is_granted('company_show')")
     * @ApiDoc(
     *     section="Company",
     *     description="Get regions",
     *     tags = {
     *      "Implemented" = "green"
     *     }
     * )
     * @Rest\Get("/{id}/regions", name="company_regions")
     * @Rest\View()
     * @param Company $company
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function getRegionsAction(Company $company)
    {
        /** @var $user AbstractUser */
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_ADMIN) && $user->getCompany() !== $company)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.company'));
        }

        /** @var $service BranchService*/
        $service = $this->get('service.branch_service');

        return $service->getRegionsBranchesByCompany($company);
    }

    /**
     * @Security("is_granted('company_show')")
     * @ApiDoc(
     *     section="Company",
     *     description="Get regions",
     *     tags = {
     *      "Implemented" = "green"
     *     }
     * )
     * @Rest\Get("/{id}/employees_by_current_shift", name="company_employees_by_current_shift")
     * @Rest\View()
     * @param Company $company
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function getEmployeesByCurrentShift(Company $company)
    {
        /** @var $user AbstractUser */
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_ADMIN) && $user->getCompany() !== $company)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.company'));
        }

        /** @var $service BranchShiftManagement */
        $service = $this->get('service.branch_shift.branch_shift_management');

        return $service->getEmployeesCurrentShiftByCompany($company);
    }

}
