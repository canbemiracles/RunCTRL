<?php

namespace ApiBundle\Controller\User;

use ApiBundle\Entity\Company;
use ApiBundle\Entity\File\AvatarFile;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\BranchManager;
use ApiBundle\Form\User\Avatar\BranchManagerAvatarType;
use ApiBundle\Repository\User\AbstractUserRepository;
use ApiBundle\Service\User\AccountConfirmation;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Branchmanager controller.
 *
 * @Route("companies/{company_id}/users/branch_managers")
 */
class BranchManagerController extends AbstractController
{
    /**
     * @Security("is_granted('user_branch_manager_index')")
     * @ApiDoc(
     *  section="[Users] BranchManager",
     *  description="Lists all branchManager entities.",
     *  output="ApiBundle\Entity\User\BranchManager",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="user_branch_manager_index")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Company $company
     * @Rest\View()
     * @return array BranchManager
     */
    public function indexAction(Company $company)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.branch_managers'));
        }

        if($user->hasRole(AbstractUser::ROLE_BRANCH_MANAGER)) {
            return $user;
        }

        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:User\BranchManager')->findBy(['company' => $company->getId()]);
    }

    /**
     * @Rest\QueryParam(name="branch", nullable=false)
     * @Security("is_granted('user_branch_manager_new')")
     * @ApiDoc(
     *  section="[Users] BranchManager",
     *  description="Creates a new branchManager entity.",
     *  input="ApiBundle\Form\User\BranchManagerType",
     *  output="ApiBundle\Entity\User\BranchManager",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="user_branch_manager_new")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param ParamFetcher $paramFetcher
     * @param Company $company
     * @return BranchManager|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Company $company, Request $request, ParamFetcher $paramFetcher)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.branch_manager'));
        }

        $branchManager = new Branchmanager();
        $form = $this->createForm('ApiBundle\Form\User\BranchManagerType', $branchManager);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $email = $branchManager->getEmail();
            $existingAccount = $em->getRepository('ApiBundle:User\AbstractUser')->findOneBy(['email' => $email]);
            if($existingAccount !== null)
            {
                throw new BadRequestHttpException('This email is already in use');
            }

            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $branchManager->setConfirmationToken($tokenGenerator->generateToken());

            $branchManager->addGroup($this->getDoctrine()->getRepository('ApiBundle:User\Group')->findOneBy(['name' => 'branch_manager']));
            $em->persist($branchManager);
            $em->flush();

            /** @var AccountConfirmation $accountConfirmationService */
            $accountConfirmationService = $this->get('service.account_confirmation');
            $accountConfirmationService->sendConfirmationEmailInvite($email, $paramFetcher->get('branch'));

            return $branchManager;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('user_branch_manager_show')")
     * @ApiDoc(
     *  section="[Users] BranchManager",
     *  description="Finds and displays a branchManager entity.",
     *  output="ApiBundle\Entity\User\BranchManager",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="user_branch_manager_show")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param BranchManager $branchManager
     * @param Company $company
     * @return BranchManager
     */
    public function showAction(Company $company, BranchManager $branchManager)
    {
        $user = $this->getUser();

        if(!$branchManager) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$company->getUsers()->contains($user) || !$company->getUsers()->contains($branchManager))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.branch_manager'));
        }

        return $branchManager;
    }

    /**
     * @Security("is_granted('user_branch_manager_edit')")
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
     * @Rest\Put("/invite_edit", name="user_branch_manager_invite_put")
     * @Rest\Patch("/invite_edit", name="user_branch_manager_invite_patch")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Request $request
     * @param Company $company
     * @param ParamFetcher $paramFetcher
     * @param
     * @return \Symfony\Component\HttpFoundation\JsonResponse|BranchManager
     */
    public function inviteEditAction(Company $company, Request $request, ParamFetcher $paramFetcher)
    {
        /** @var $user_repository AbstractUserRepository*/
        $user_repository = $this->getDoctrine()->getRepository("ApiBundle:User\AbstractUser");
        $token = $paramFetcher->get('token');
        $branchManager = $user_repository->getUserByAccessToken($token);

        if(!$branchManager instanceof BranchManager) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.branch_manager'));
        }

        $editForm = $this->createForm('ApiBundle\Form\User\BranchManagerType', $branchManager, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $branchManager;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('user_branch_manager_edit')")
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
     * @Rest\Put("/{id}", name="user_branch_manager_put")
     * @Rest\Patch("/{id}", name="user_branch_manager_patch")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Request $request
     * @param Company $company
     * @param BranchManager $branchManager
     * @return \Symfony\Component\HttpFoundation\JsonResponse|BranchManager
     */
    public function editAction(Company $company, Request $request, BranchManager $branchManager)
    {
        $user = $this->getUser();

        if(!$branchManager) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$company->getUsers()->contains($user) || !$company->getUsers()->contains($branchManager)
                || $user->hasRole(AbstractUser::ROLE_BRANCH_MANAGER) && $user !== $branchManager)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.branch_manager'));
        }

        $editForm = $this->createForm('ApiBundle\Form\User\BranchManagerType', $branchManager, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            /** @var UserManagerInterface $userManager */
            $user_manager = $this->get('fos_user.user_manager');
            $user_manager->updateUser($branchManager);
            $this->get('service.branch_service')->attachBranchManagerToBranch($branchManager->getBranch(), $branchManager);
            $this->getDoctrine()->getManager()->flush();
            return $branchManager;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('user_branch_manager_delete')")
     * @ApiDoc(
     *  section="[Users] BranchManager",
     *  description="Deletes a branchManager entity.",
     *  output="ApiBundle\Entity\User\BranchManager",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="user_branch_manager_delete")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param BranchManager $branchManager
     * @param Company $company
     * @return Response
     */
    public function deleteAction(Company $company, BranchManager $branchManager)
    {
        $user = $this->getUser();

        if(!$branchManager) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$company->getUsers()->contains($user) || !$company->getUsers()->contains($branchManager))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.branch_manager'));
        }

        foreach ($branchManager->getGroups() as $item) {
            $branchManager->removeGroup($item);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($branchManager);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('user_branch_manager_avatar')")
     * @ApiDoc(
     *     section="[Users] BranchManager",
     *     description="Uploading user avatar",
     *     output="ApiBundle\Entity\User\BranchManager",
     *     tags = {
     *          "Implemented" = "green",
     *     }
     * )
     * @Rest\Post("/{id}/avatars", name="user_branch_manager_avatar")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Company $company
     * @param BranchManager $branchManager
     * @return BranchManager|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadAvatarAction(Company $company, Request $request, BranchManager $branchManager)
    {
        $user = $this->getUser();
        if($user->hasRole(AbstractUser::ROLE_ADMIN)) {
            if(!$branchManager->getCompany()->getUsers()->contains($user) || !$company->getUsers()->contains($branchManager)) {
                throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
            }
        }
        else if($user->hasRole(AbstractUser::ROLE_BRANCH_MANAGER) && $user !== $branchManager) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }
        $form = $this->createForm(BranchManagerAvatarType::class, $branchManager, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            $uploadableManager->markEntityToUpload($branchManager->getAvatar(), $branchManager->getAvatar()->getPath());

            $this->getDoctrine()->getManager()->persist($branchManager);
            $this->getDoctrine()->getManager()->flush();

            return $branchManager;
        }

        return $this->getJsonErrorsResponse($form);
    }

    /**
     * @Security("is_granted('user_branch_manager_avatar')")
     * @ApiDoc(
     *     section="[Users] BranchManager",
     *     description="Delete user manager",
     *     output="ApiBundle\Entity\User\BranchManager",
     *     tags = {
     *          "Implemented" = "green",
     *     }
     * )
     * @Rest\Delete("/{id}/avatars", name="user_branch_manager_avatar_delete")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Company $company
     * @param BranchManager $branchManager
     * @return BranchManager|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAvatarAction(Company $company, BranchManager $branchManager)
    {
        $user = $this->getUser();
        if($user->hasRole(AbstractUser::ROLE_ADMIN)) {
            if(!$branchManager->getCompany()->getUsers()->contains($user) || !$company->getUsers()->contains($branchManager)) {
                throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
            }
        }
        else if($user->hasRole(AbstractUser::ROLE_BRANCH_MANAGER) && $user !== $branchManager) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }

        /** @var $avatar AvatarFile*/
        $avatar = $branchManager->getAvatar();
        if(!empty($branchManager)) {
            $fs = new Filesystem();
            if($fs->exists($avatar->getPath())) {
                $fs->remove($avatar->getPath());
            }
            $branchManager->setAvatar(null);
            $this->getDoctrine()->getManager()->persist($branchManager);
            $this->getDoctrine()->getManager()->remove($avatar);
            $this->getDoctrine()->getManager()->flush();
        }

        return $branchManager;
    }

    /**
     * @Security("is_granted('user_branch_manager_avatar')")
     * @Rest\QueryParam(name="token", nullable=false)
     * @ApiDoc(
     *     section="[Users] BranchManager",
     *     description="Uploading user avatar",
     *     output="ApiBundle\Entity\User\BranchManager",
     *     tags = {
     *          "Implemented" = "green",
     *     }
     * )
     * @Rest\Post("/{id}/invite_avatars", name="user_branch_manager_avatar_invite")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Company $company
     * @param BranchManager $branchManager
     * @param ParamFetcher $paramFetcher
     * @return BranchManager|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function inviteUploadAvatarAction(Company $company, Request $request, BranchManager $branchManager, ParamFetcher $paramFetcher)
    {
        /** @var $user_repository AbstractUserRepository*/
        $user_repository = $this->getDoctrine()->getRepository("ApiBundle:User\AbstractUser");
        $token = $paramFetcher->get('token');
        $user = $user_repository->getUserByAccessToken($token);

        if (!$user instanceof BranchManager || !$company->getUsers()->contains($branchManager) || $user !== $branchManager) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }

        $form = $this->createForm(BranchManagerAvatarType::class, $branchManager, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            $uploadableManager->markEntityToUpload($branchManager->getAvatar(), $branchManager->getAvatar()->getPath());

            $this->getDoctrine()->getManager()->persist($branchManager);
            $this->getDoctrine()->getManager()->flush();

            return $branchManager;
        }

        return $this->getJsonErrorsResponse($form);
    }
}
