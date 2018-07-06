<?php

namespace ApiBundle\Controller\User;

use ApiBundle\Entity\Company;
use ApiBundle\Entity\File\AvatarFile;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\OfficeEmployee;
use ApiBundle\Form\User\Avatar\OfficeEmployeeAvatarType;
use FOS\UserBundle\Model\UserManagerInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Officeemployee controller.
 *
 * @Route("companies/{company_id}/users/office_employees")
 */
class OfficeEmployeeController extends AbstractController
{
    /**
     * @Security("is_granted('user_office_employee_index')")
     * @ApiDoc(
     *  section="[Users] OfficeEmployee",
     *  description="Lists all officeEmployee entities.",
     *  output="ApiBundle\Entity\User\OfficeEmployee",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="user_office_employee_index")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Company $company
     * @Rest\View()
     * @return array OfficeEmployee
     */
    public function indexAction(Company $company)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.office_employees'));
        }

        if($user->hasRole(AbstractUser::ROLE_OFFICE_EMPLOYEE)) {
            return $user;
        }

        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:User\OfficeEmployee')->findBy(['company' => $company->getId()]);
    }

    /**
     * @Security("is_granted('user_office_employee_new')")
     * @ApiDoc(
     *  section="[Users] OfficeEmployee",
     *  description="Creates a new officeEmployee entity.",
     *  input="ApiBundle\Form\User\OfficeEmployeeType",
     *  output="ApiBundle\Entity\User\OfficeEmployee",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Post("/new", name="user_office_employee_new")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Company $company
     * @return OfficeEmployee|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Company $company, Request $request)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.office_employee'));
        }

        $officeEmployee = new Officeemployee();
        $form = $this->createForm('ApiBundle\Form\User\OfficeEmployeeType', $officeEmployee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $email = $officeEmployee->getEmail();
            $existingAccount = $em->getRepository('ApiBundle:User\AbstractUser')->findOneBy(['email' => $email]);
            if($existingAccount !== null)
            {
                throw new BadRequestHttpException('This email is already in use');
            }

            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $officeEmployee->setConfirmationToken($tokenGenerator->generateToken());
            $this->get('service.account_confirmation')->password_resetting($officeEmployee);

            $officeEmployee->addGroup($this->getDoctrine()->getRepository('ApiBundle:User\Group')->findOneBy(['name' => 'office_employee']));
            $em->persist($officeEmployee);
            $em->flush();

            return $officeEmployee;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('user_office_employee_show')")
     * @ApiDoc(
     *  section="[Users] OfficeEmployee",
     *  description="Finds and displays a officeEmployee entity.",
     *  output="ApiBundle\Entity\User\OfficeEmployee",
     *  input="ApiBundle\Form\User\OfficeEmployeeType",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="user_office_employee_show")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param OfficeEmployee $officeEmployee
     * @param Company $company
     * @return OfficeEmployee
     */
    public function showAction(Company $company, OfficeEmployee $officeEmployee)
    {
        $user = $this->getUser();

        if(!$officeEmployee) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$company->getUsers()->contains($user) || !$company->getUsers()->contains($officeEmployee))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.office_employee'));
        }
        return $officeEmployee;
    }

    /**
     * @Security("is_granted('user_office_employee_edit')")
     * @ApiDoc(
     *  section="[Users] OfficeEmployee",
     *  description="Displays a form to edit an existing officeEmployee entity.",
     *  output="ApiBundle\Entity\User\OfficeEmployee",
     *  input="ApiBundle\Form\User\OfficeEmployeeType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="user_office_employee_put")
     * @Rest\Patch("/{id}", name="user_office_employee_patch")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Request $request
     * @param OfficeEmployee $officeEmployee
     * @param Company $company
     * @return \Symfony\Component\HttpFoundation\JsonResponse|OfficeEmployee
     */
    public function editAction(Company $company, Request $request, OfficeEmployee $officeEmployee)
    {
        $user = $this->getUser();

        if(!$officeEmployee) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$company->getUsers()->contains($user) || !$company->getUsers()->contains($officeEmployee))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.office_employee'));
        }

        $editForm = $this->createForm('ApiBundle\Form\User\OfficeEmployeeType', $officeEmployee, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            /** @var UserManagerInterface $userManager */
            $user_manager = $this->get('fos_user.user_manager');
            $user_manager->updateUser($officeEmployee);
            $this->getDoctrine()->getManager()->flush();
            return $officeEmployee;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('user_office_employee_delete')")
     * @ApiDoc(
     *  section="[Users] OfficeEmployee",
     *  description="Deletes a officeEmployee entity.",
     *  output="ApiBundle\Entity\User\OfficeEmployee",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="user_office_employee_delete")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param OfficeEmployee $officeEmployee
     * @param Company $company
     * @return Response
     */
    public function deleteAction(Company $company, OfficeEmployee $officeEmployee)
    {
        $user = $this->getUser();

        if(!$officeEmployee) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$company->getUsers()->contains($user) || !$company->getUsers()->contains($officeEmployee))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.office_employee'));
        }

        foreach ($officeEmployee->getGroups() as $item) {
            $officeEmployee->removeGroup($item);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($officeEmployee);
        $em->flush();

        return new Response();
    }


    /**
     * @Security("is_granted('user_office_employee_avatar')")
     * @ApiDoc(
     *     section="[Users] OfficeEmployee",
     *     description="Uploading user avatar",
     *     output="ApiBundle\Entity\User\OfficeEmployee",
     *     tags = {
     *          "Implemented" = "green",
     *     }
     * )
     * @Rest\Post("/{id}/avatars", name="user_office_employee_avatar")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param OfficeEmployee $officeEmployee
     * @param Company $company
     * @return OfficeEmployee|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadAvatarAction(Company $company, Request $request, OfficeEmployee $officeEmployee)
    {
        $user = $this->getUser();
        if($user->hasRole(AbstractUser::ROLE_ADMIN)) {
            if(!$officeEmployee->getCompany()->getUsers()->contains($user) || !$company->getUsers()->contains($officeEmployee)) {
                throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
            }
        }
        else if($user->hasRole(AbstractUser::ROLE_OFFICE_EMPLOYEE) && $user !== $officeEmployee) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }
        $form = $this->createForm(OfficeEmployeeAvatarType::class, $officeEmployee, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            $uploadableManager->markEntityToUpload($officeEmployee->getAvatar(), $officeEmployee->getAvatar()->getPath());

            $this->getDoctrine()->getManager()->persist($officeEmployee);
            $this->getDoctrine()->getManager()->flush();

            return $officeEmployee;
        }

        return $this->getJsonErrorsResponse($form);
    }

    /**
     * @Security("is_granted('user_office_employee_avatar')")
     * @ApiDoc(
     *     section="[Users] OfficeEmployee",
     *     description="Delete user OfficeEmployee",
     *     output="ApiBundle\Entity\User\OfficeEmployee",
     *     tags = {
     *          "Implemented" = "green",
     *     }
     * )
     * @Rest\Delete("/{id}/avatars", name="user_office_employee_avatar_delete")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Company $company
     * @param OfficeEmployee $officeEmployee
     * @return OfficeEmployee|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAvatarAction(Company $company, OfficeEmployee $officeEmployee)
    {
        $user = $this->getUser();
        if($user->hasRole(AbstractUser::ROLE_ADMIN)) {
            if(!$officeEmployee->getCompany()->getUsers()->contains($user) || !$company->getUsers()->contains($officeEmployee)) {
                throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
            }
        }
        else if($user->hasRole(AbstractUser::ROLE_OFFICE_EMPLOYEE) && $user !== $officeEmployee) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }

        /** @var $avatar AvatarFile*/
        $avatar = $officeEmployee->getAvatar();
        if(!empty($officeEmployee)) {
            $fs = new Filesystem();
            if($fs->exists($avatar->getPath())) {
                $fs->remove($avatar->getPath());
            }
            $officeEmployee->setAvatar(null);
            $this->getDoctrine()->getManager()->persist($officeEmployee);
            $this->getDoctrine()->getManager()->remove($avatar);
            $this->getDoctrine()->getManager()->flush();
        }

        return $officeEmployee;
    }
}
