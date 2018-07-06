<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\BranchManager;
use ApiBundle\Entity\User\Device\Device;
use ApiBundle\Entity\User\Supervisor;
use ApiBundle\Form\EmployeeAvatarType;
use ApiBundle\Repository\EmployeeRepository;
use ApiBundle\Repository\User\AbstractUserRepository;
use ApiBundle\Service\Elastica\SearchService;
use ApiBundle\Service\Employee\EmployeeRoleHistory;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Knp\Component\Pager\Paginator;

/**
 * Employee controller.
 *
 * @Route("companies/{company_id}/employees")
 */
class EmployeeController extends AbstractController
{
    /**
     * @Security("is_granted('employee_index')")
     * @ApiDoc(
     *  section="Employee",
     *  description="Lists all employee entities.",
     *  output="ApiBundle\Entity\Employee",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\Get("/", name="employee_index")
     * @Rest\View()
     * @param Company $company
     * @param Request $request
     * @return Employee[]|JsonResponse
     */
    public function indexAction(Company $company, Request $request)
    {

        $user = $this->getUser();

        if (!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$company->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && $user instanceof Device && $user->getStation()->getBranch()->getCompany() !== $company) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.employees'));
        }

        $employees = $company->getEmployees();

        //Disabling pagination for devices
        if ($user->hasRole(AbstractUser::ROLE_DEVICE)) {
            return $employees;
        }

        $page = 1;

        if ($request->query->get('page') != null) {
            $page = $request->query->get('page');
            $request->query->remove('page');
        }

        if($page === "all") {
            return $employees;
        } else {
            $pager = $this->get('knp_paginator');
            return $pager->paginate($employees->toArray(), $page, $this->getParameter('page_range')['employees_per_page']);
        }
    }

    /**
     * @Security("is_granted('employee_new')")
     * @ApiDoc(
     *  section="Employee",
     *  description="Creates a new employee entity.",
     *  input="ApiBundle\Form\EmployeeType",
     *  output="ApiBundle\Entity\Employee",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\Post("/new", name="employee_new")
     * @Rest\View()
     * @param Request $request
     * @param Company $company
     * @return Employee|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Company $company)
    {
        $user = $this->getUser();

        if (!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.employee'));
        }

        $form = $this->createForm('ApiBundle\Form\EmployeeType', new Employee());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var $repository EmployeeRepository*/
            $repository = $em->getRepository('ApiBundle\Entity\Employee');
            if($repository->findOneBy(array('company' => $company, 'social_security_number' => $request->get('social_security_number'))) && !empty($request->get('social_security_number'))) {
                throw new BadRequestHttpException($this->get('translator')->trans('social_number_error'));
            }
            /** @var Employee $employee */
            $employee = $form->getData();
            $employee->setCompany($company);
            $em->persist($employee);
            $em->flush();

            return $employee;
        } else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('employee_new')")
     * @Rest\QueryParam(name="token", nullable=false)
     * @ApiDoc(
     *  section="[Users] BranchManager",
     *  description="Displays a form to edit an existing branchManager entity.",
     *  output="ApiBundle\Entity\User\BranchManager",
     *  input="ApiBundle\Form\User\BranchManagerType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Post("/invite_new", name="employee_invite_new")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Request $request
     * @param Company $company
     * @param ParamFetcher $paramFetcher
     * @param
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Employee
     */
    public function inviteNewAction(Company $company, Request $request, ParamFetcher $paramFetcher)
    {
        /** @var $user_repository AbstractUserRepository*/
        $user_repository = $this->getDoctrine()->getRepository("ApiBundle:User\AbstractUser");
        $token = $paramFetcher->get('token');
        $user = $user_repository->getUserByAccessToken($token);

        if(!$user instanceof BranchManager && !$user instanceof Supervisor) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.employee'));
        }

        $form = $this->createForm('ApiBundle\Form\EmployeeType', new Employee());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var $repository EmployeeRepository*/
            $repository = $em->getRepository('ApiBundle\Entity\Employee');
            if($repository->findOneBy(array('company' => $company, 'social_security_number' => $request->get('social_security_number'))) && !empty($request->get('social_security_number'))) {
                throw new BadRequestHttpException($this->get('translator')->trans('social_number_error'));
            }
            /** @var Employee $employee */
            $employee = $form->getData();
            $employee->setCompany($company);
            $em->persist($employee);
            $em->flush();

            return $employee;
        } else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('get_employees_by_branch')")
     * @Rest\QueryParam(name="branch", nullable=false)
     * @ApiDoc(
     *  section="Employee",
     *  description="Lists all employees by branch.",
     *  output="ApiBundle\Entity\Employee",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/get_employees", name="get_employees")
     * @Rest\View()
     * @param ParamFetcher $paramFetcher
     * @return Employee[]|JsonResponse
     */
    public function getEmployeesByBranch(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $branch Branch*/
        $branch = $em->getRepository('ApiBundle:Branch')->findOneBy(array('id' => (int) $paramFetcher->get('branch')));

        return $branch->getEmployees();
    }

    /**
     * @Security("is_granted('employee_show')")
     * @ApiDoc(
     *  section="Employee",
     *  description="Finds and displays a employee entity.",
     *  output="ApiBundle\Entity\Employee",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\Get("/{id}", name="employee_show")
     * @Rest\View()
     * @param Employee $employee
     * @param Company $company
     * @return Employee
     */
    public function showAction(Employee $employee, Company $company)
    {
        if (!$employee) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if (!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && $user->getStation()->getBranch()->getCompany() !== $company) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.employee'));
        }

        return $employee;
    }

    /**
     * @Security("is_granted('employee_edit')")
     * @ApiDoc(
     *  section="Employee",
     *  description="Displays a form to edit an existing employee entity.",
     *  output="ApiBundle\Entity\Employee",
     *  input="ApiBundle\Form\EmployeeType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="employee_put")
     * @Rest\Patch("/{id}", name="employee_patch")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Request $request
     * @param Employee $employee
     * @param Company $company
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Employee
     */
    public function editAction(Request $request, Employee $employee, Company $company)
    {

        $user = $this->getUser();

        if (!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.employee'));
        }

        if(!is_null($request->get("branches"))) {
            foreach ($employee->getBranches() as $branch) {
                $employee->removeBranch($branch);
            }
        }

        if(!is_null($request->get("roles"))) {
            foreach ($employee->getRoles() as $role) {
                $employee->removeRole($role);
            }
        }

        if(!is_null($request->get("branch_shifts"))) {
            foreach ($employee->getBranchShifts() as $shift) {
                $employee->removeBranchShift($shift);
            }
        }

        $editForm = $this->createForm('ApiBundle\Form\EmployeeType', $employee, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            /** @var $repository EmployeeRepository*/
            $repository = $this->getDoctrine()->getManager()->getRepository('ApiBundle\Entity\Employee');
            $empl = $repository->findOneBy(array('company' => $company, 'social_security_number' => $request->get('social_security_number')));
            if(!empty($empl) && $employee !== $empl && !empty($request->get('social_security_number'))) {
                throw new BadRequestHttpException($this->get('translator')->trans('social_number_error'));
            }
            $this->getDoctrine()->getManager()->flush();
            return $employee;
        } else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('employee_delete')")
     * @ApiDoc(
     *  section="Employee",
     *  description="Deletes a employee entity.",
     *  output="ApiBundle\Entity\Employee",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="employee_delete")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Employee $employee
     * @param Company $company
     * @return Response
     */
    public function deleteAction(Request $request, Employee $employee, Company $company)
    {
        if (!$employee) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if (!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.employee'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($employee);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('employee_avatar')")
     * @ApiDoc(
     *     section="Employee",
     *     description="Uploading employee avatar",
     *     output="ApiBundle\Entity\User\Employee",
     *     tags = {
     *          "Implemented" = "green"
     *     }
     * )
     * @Rest\Post("/{id}/avatars", name="employee_avatar")
     * @Rest\View()
     * @param Request $request
     * @param Employee $employee
     * @return Employee|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadAvatarAction(Request $request, Employee $employee)
    {
        $user = $this->getUser();

        if ($employee->getCompanyId() !== $user->getCompany()->getId()) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }

        $form = $this->createForm(EmployeeAvatarType::class, $employee, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            $uploadableManager->markEntityToUpload($employee->getAvatar(), $employee->getAvatar()->getPath());

            $this->getDoctrine()->getManager()->persist($employee);
            $this->getDoctrine()->getManager()->flush();

            return $employee;
        }

        return $this->getJsonErrorsResponse($form);
    }

    /**
     * @Security("is_granted('employee_avatar')")
     * @Rest\QueryParam(name="token", nullable=false)
     * @ApiDoc(
     *     section="Employee",
     *     description="Uploading employee avatar",
     *     output="ApiBundle\Entity\User\Employee",
     *     tags = {
     *          "Implemented" = "green"
     *     }
     * )
     * @Rest\Post("/{id}/invite_avatars", name="employee_avatar_invite")
     * @Rest\View()
     * @param Request $request
     * @param Employee $employee
     * @return Employee|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function inviteUploadAvatarAction(Request $request, Employee $employee, ParamFetcher $paramFetcher)
    {
        /** @var $user_repository AbstractUserRepository*/
        $user_repository = $this->getDoctrine()->getRepository("ApiBundle:User\AbstractUser");
        $token = $paramFetcher->get('token');
        $user = $user_repository->getUserByAccessToken($token);

        if (!$user instanceof BranchManager && !$user instanceof Supervisor || $employee->getCompanyId() !== $user->getCompany()->getId() ||
            $user instanceof BranchManager && $employee->getBranchManager() !== $user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }

        $form = $this->createForm(EmployeeAvatarType::class, $employee, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            $uploadableManager->markEntityToUpload($employee->getAvatar(), $employee->getAvatar()->getPath());

            $this->getDoctrine()->getManager()->persist($employee);
            $this->getDoctrine()->getManager()->flush();

            return $employee;
        }

        return $this->getJsonErrorsResponse($form);
    }


    /**
     * @Security("is_granted('employee_history')")
     * @ApiDoc(
     *  section="Employee",
     *  description="Lists all history records entities.",
     *  output="ApiBundle\Entity\Employee",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\Get("/{id}/history_roles", name="employee_history")
     * @Rest\View()
     * @param Company $company
     * @param Employee $employee
     * @param Request $request
     * @return Employee[]|JsonResponse
     */
    public function getHistoryRoleAction(Company $company, Employee $employee, Request $request)
    {

        $user = $this->getUser();

        if (!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.history_records'));
        }

        $page = 1;

        if ($request->query->get('page') != null) {
            $page = $request->query->get('page');
            $request->query->remove('page');
        }

        $pager = $this->get('knp_paginator');

        return $pager->paginate($employee->getRoles()->toArray(), $page, $this->getParameter('page_range')['history_roles_per_page']);
    }

    /**
     * @Security("is_granted('employee_history')")
     * @ApiDoc(
     *  section="Employee",
     *  description="Current month history records entities.",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\Get("/{id}/current_month_history", name="current_month_employee_history")
     * @Rest\View()
     * @param Company $company
     * @param Employee $employee
     * @param Request $request
     * @return Employee[]|JsonResponse
     */
    public function getHistoryRoleByCurrentMonth(Company $company, Employee $employee, Request $request)
    {
        $user = $this->getUser();

        if (!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.history_records'));
        }

        /** @var EmployeeRoleHistory $roleHistory */
        $roleHistory = $this->get('service.employee.role_history');

        $page = 1;

        if($request->query->get('page') != null) {
            $page = $request->query->get('page');
            $request->query->remove('page');
        }

        return $roleHistory->getCurrentMonthHistory($employee, $page);
    }

    /**
     * @Security("is_granted('employee_history')")
     * @ApiDoc(
     *  section="Employee",
     *  description="Current year history records entities.",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\Get("/{id}/current_year_history", name="current_year_history")
     * @Rest\View()
     * @param Company $company
     * @param Employee $employee
     * @return Employee[]|JsonResponse
     */
    public function getHistoryRoleByCurrentYear(Company $company, Employee $employee)
    {
        $user = $this->getUser();

        if (!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.history_records'));
        }

        /** @var EmployeeRoleHistory $roleHistory */
        $roleHistory = $this->get('service.employee.role_history');

        return $roleHistory->getCurrentYearHistory($employee);
    }

    /**
     * @Security("is_granted('search_employees')")
     * @ApiDoc(
     *  section="Employee",
     *  description="Lists all Employee entities.",
     *  output="ApiBundle\Entity\Employee",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Post("/search", name="search_employees")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Company $company
     * @param Request $request
     * @return array
     */
    public function searchEmployee(Company $company, Request $request)
    {
        /** @var $searchService SearchService*/
        $searchService = $this->get('service.elastica.search');
        return $searchService->search(
            "employee",
            $request->get('term'),
            $company
        );
    }
}
