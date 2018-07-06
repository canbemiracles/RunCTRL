<?php

namespace ApiBundle\Controller\User;

use ApiBundle\Entity\Company;
use ApiBundle\Entity\File\AvatarFile;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\Supervisor;
use ApiBundle\Form\User\Avatar\SupervisorAvatarType;
use ApiBundle\Repository\User\AbstractUserRepository;
use ApiBundle\Service\User\AccountConfirmation;
use FOS\RestBundle\Request\ParamFetcher;
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

/**
 * Supervisor controller.
 *
 * @Route("companies/{company_id}/users/supervisors")
 */
class SupervisorController extends AbstractController
{
    /**
     * @Security("is_granted('user_supervisor_index')")
     * @ApiDoc(
     *  section="[Users] Supervisor",
     *  description="Lists all supervisor entities.",
     *  output="ApiBundle\Entity\User\Supervisor",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="user_supervisor_index")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Company $company
     * @Rest\View()
     * @return array Supervisor
     */
    public function indexAction(Company $company)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.supervisors'));
        }

        if($user->hasRole(AbstractUser::ROLE_SUPERVISOR)) {
            return $user;
        }

        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:User\Supervisor')->findBy(['company' => $company->getId()]);
    }

    /**
     * @Rest\QueryParam(name="branch", nullable=false)
     * @Security("is_granted('user_supervisor_new')")
     * @ApiDoc(
     *  section="[Users] Supervisor",
     *  description="Creates a new supervisor entity.",
     *  input="ApiBundle\Form\User\SupervisorType",
     *  output="ApiBundle\Entity\User\Supervisor",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="user_supervisor_new")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Company $company
     * @param ParamFetcher $paramFetcher
     * @return Supervisor|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Company $company, Request $request, ParamFetcher $paramFetcher)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.supervisor'));
        }

        $supervisor = new Supervisor();
        $form = $this->createForm('ApiBundle\Form\User\SupervisorType', $supervisor);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $email = $supervisor->getEmail();
            $existingAccount = $em->getRepository('ApiBundle:User\AbstractUser')->findOneBy(['email' => $email]);
            if($existingAccount !== null)
            {
                throw new BadRequestHttpException('This email is already in use');
            }

            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $supervisor->setConfirmationToken($tokenGenerator->generateToken());

            $supervisor->addGroup($this->getDoctrine()->getRepository('ApiBundle:User\Group')->findOneBy(['name' => 'supervisor']));
            $em->persist($supervisor);
            $em->flush();

            /** @var AccountConfirmation $accountConfirmationService */
            $accountConfirmationService = $this->get('service.account_confirmation');
            $accountConfirmationService->sendConfirmationEmailInvite($email, $paramFetcher->get('branch'));

            return $supervisor;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('user_supervisor_show')")
     * @ApiDoc(
     *  section="[Users] Supervisor",
     *  description="Finds and displays a supervisor entity.",
     *  output="ApiBundle\Entity\User\Supervisor",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="user_supervisor_show")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Supervisor $supervisor
     * @param Company $company
     * @return Supervisor
     */
    public function showAction(Company $company, Supervisor $supervisor)
    {
        $user = $this->getUser();

        if(!$supervisor) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$company->getUsers()->contains($user) || !$company->getUsers()->contains($supervisor))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.supervisor'));
        }

        return $supervisor;
    }

    /**
     * @Security("is_granted('user_supervisor_edit')")
     * @Rest\QueryParam(name="token", nullable=false)
     * @ApiDoc(
     *  section="[Users] Supervisor",
     *  description="Displays a form to edit an existing supervisor entity.",
     *  output="ApiBundle\Entity\User\Supervisor",
     *  input="ApiBundle\Form\User\SupervisorType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/invite_edit", name="user_supervisor_invite_put")
     * @Rest\Patch("/invite_edit", name="user_supervisor_invite_patch")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Request $request
     * @param Company $company
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Supervisor
     */
    public function inviteEditAction(Company $company, Request $request, ParamFetcher $paramFetcher)
    {
        /** @var $user_repository AbstractUserRepository*/
        $user_repository = $this->getDoctrine()->getRepository("ApiBundle:User\AbstractUser");
        $token = $paramFetcher->get('token');
        $supervisor = $user_repository->getUserByAccessToken($token);

        if(!$supervisor instanceof Supervisor) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.supervisor'));
        }

        $editForm = $this->createForm('ApiBundle\Form\User\SupervisorType', $supervisor, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $supervisor;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('user_supervisor_edit')")
     * @ApiDoc(
     *  section="[Users] Supervisor",
     *  description="Displays a form to edit an existing supervisor entity.",
     *  output="ApiBundle\Entity\User\Supervisor",
     *  input="ApiBundle\Form\User\SupervisorType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="user_supervisor_put")
     * @Rest\Patch("/{id}", name="user_supervisor_patch")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Request $request
     * @param Supervisor $supervisor
     * @param Company $company
     * @return Supervisor | \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function editAction(Company $company, Request $request, Supervisor $supervisor)
    {
        $user = $this->getUser();

        if(!$supervisor) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$company->getUsers()->contains($user) || !$company->getUsers()->contains($supervisor))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.supervisor'));
        }

        $editForm = $this->createForm('ApiBundle\Form\User\SupervisorType', $supervisor, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            /** @var UserManagerInterface $userManager */
            $user_manager = $this->get('fos_user.user_manager');
            $user_manager->updateUser($supervisor);
            $this->getDoctrine()->getManager()->flush();
            return $supervisor;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('user_supervisor_delete')")
     * @ApiDoc(
     *  section="[Users] Supervisor",
     *  description="Deletes a supervisor entity.",
     *  output="ApiBundle\Entity\User\Supervisor",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="user_supervisor_delete")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Supervisor $supervisor
     * @param Company $company
     * @return Response
     */
    public function deleteAction(Company $company, Supervisor $supervisor)
    {
        $user = $this->getUser();

        if(!$supervisor) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$company->getUsers()->contains($user) || !$company->getUsers()->contains($supervisor))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.supervisor'));
        }
        foreach ($supervisor->getGroups() as $item) {
            $supervisor->removeGroup($item);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($supervisor);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('user_supervisor_avatar')")
     * @ApiDoc(
     *     section="[Users] Supervisor",
     *     description="Uploading user avatar",
     *     output="ApiBundle\Entity\User\Supervisor",
     *     tags = {
     *          "Implemented" = "green",
     *     }
     * )
     * @Rest\Post("/{id}/avatars", name="user_supervisor_avatar")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Supervisor $supervisor
     * @param Company $company
     * @return Supervisor|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadAvatarAction(Company $company, Request $request, Supervisor $supervisor)
    {
        $user = $this->getUser();
        if($user->hasRole(AbstractUser::ROLE_ADMIN)) {
            if(!$supervisor->getCompany()->getUsers()->contains($user) || !$company->getUsers()->contains($supervisor)) {
                throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
            }
        }
        else if($user->hasRole(AbstractUser::ROLE_SUPERVISOR) && $user !== $supervisor) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }
        $form = $this->createForm(SupervisorAvatarType::class, $supervisor, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            $uploadableManager->markEntityToUpload($supervisor->getAvatar(), $supervisor->getAvatar()->getPath());

            $this->getDoctrine()->getManager()->persist($supervisor);
            $this->getDoctrine()->getManager()->flush();

            return $supervisor;
        }

        return $this->getJsonErrorsResponse($form);
    }

    /**
     * @Security("is_granted('user_supervisor_avatar')")
     * @ApiDoc(
     *     section="[Users] Supervisor",
     *     description="Delete user Supervisor",
     *     output="ApiBundle\Entity\User\Supervisor",
     *     tags = {
     *          "Implemented" = "green",
     *     }
     * )
     * @Rest\Delete("/{id}/avatars", name="user_supervisor_avatar_delete")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Company $company
     * @param Supervisor $supervisor
     * @return Supervisor|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAvatarAction(Company $company, Supervisor $supervisor)
    {
        $user = $this->getUser();
        if($user->hasRole(AbstractUser::ROLE_ADMIN)) {
            if(!$supervisor->getCompany()->getUsers()->contains($user) || !$company->getUsers()->contains($supervisor)) {
                throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
            }
        }
        else if($user->hasRole(AbstractUser::ROLE_SUPERVISOR) && $user !== $supervisor) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }

        /** @var $avatar AvatarFile*/
        $avatar = $supervisor->getAvatar();
        if(!empty($supervisor)) {
            $fs = new Filesystem();
            if($fs->exists($avatar->getPath())) {
                $fs->remove($avatar->getPath());
            }
            $supervisor->setAvatar(null);
            $this->getDoctrine()->getManager()->persist($supervisor);
            $this->getDoctrine()->getManager()->remove($avatar);
            $this->getDoctrine()->getManager()->flush();
        }

        return $supervisor;
    }

    /**
     * @Security("is_granted('user_supervisor_avatar')")
     * @Rest\QueryParam(name="token", nullable=false)
     * @ApiDoc(
     *     section="[Users] Supervisor",
     *     description="Uploading user avatar",
     *     output="ApiBundle\Entity\User\Supervisor",
     *     tags = {
     *          "Implemented" = "green",
     *     }
     * )
     * @Rest\Post("/{id}/invite_avatars", name="user_supervisor_avatar_invite")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Supervisor $supervisor
     * @param Company $company
     * @param ParamFetcher $paramFetcher
     * @return Supervisor|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function inviteUploadAvatarAction(Company $company, Request $request, Supervisor $supervisor, ParamFetcher $paramFetcher)
    {
        /** @var $user_repository AbstractUserRepository*/
        $user_repository = $this->getDoctrine()->getRepository("ApiBundle:User\AbstractUser");
        $token = $paramFetcher->get('token');
        $user = $user_repository->getUserByAccessToken($token);

        if (!$user instanceof Supervisor || !$company->getUsers()->contains($supervisor) || $user !== $supervisor) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }

        $form = $this->createForm(SupervisorAvatarType::class, $supervisor, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            $uploadableManager->markEntityToUpload($supervisor->getAvatar(), $supervisor->getAvatar()->getPath());

            $this->getDoctrine()->getManager()->persist($supervisor);
            $this->getDoctrine()->getManager()->flush();

            return $supervisor;
        }

        return $this->getJsonErrorsResponse($form);
    }
}
