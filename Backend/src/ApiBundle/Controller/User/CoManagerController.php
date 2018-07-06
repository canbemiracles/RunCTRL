<?php

namespace ApiBundle\Controller\User;

use ApiBundle\Entity\Company;
use ApiBundle\Entity\File\AvatarFile;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\CoManager;
use ApiBundle\Form\User\Avatar\CoManagerAvatarType;
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
 * Comanager controller.
 *
 * @Route("companies/{company_id}/users/comanagers")
 */
class CoManagerController extends AbstractController
{
    /**
     * @Security("is_granted('user_comanager_index')")
     * @ApiDoc(
     *  section="[Users] CoManager",
     *  description="Lists all coManager entities.",
     *  output="ApiBundle\Entity\User\CoManager",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="user_comanager_index")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Company $company
     * @Rest\View()
     * @return array CoManager
     */
    public function indexAction(Company $company)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.co_managers'));
        }

        if($user->hasRole(AbstractUser::ROLE_CO_MANAGER)) {
            return $user;
        }

        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:User\CoManager')->findBy(['company' => $company->getId()]);
    }

    /**
     * @Security("is_granted('user_comanager_new')")
     * @ApiDoc(
     *  section="[Users] CoManager",
     *  description="Creates a new coManager entity.",
     *  input="ApiBundle\Form\User\CoManagerType",
     *  output="ApiBundle\Entity\User\CoManager",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="user_comanager_new")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Company $company
     * @return CoManager|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Company $company, Request $request)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.co_manager'));
        }

        $coManager = new Comanager();
        $form = $this->createForm('ApiBundle\Form\User\CoManagerType', $coManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $email = $coManager->getEmail();
            $existingAccount = $em->getRepository('ApiBundle:User\AbstractUser')->findOneBy(['email' => $email]);
            if($existingAccount !== null)
            {
                throw new BadRequestHttpException('This email is already in use');
            }

            $coManager->addGroup($this->getDoctrine()->getRepository('ApiBundle:User\Group')->findOneBy(['name' => 'co_manager']));
            $em->persist($coManager);
            $em->flush();

            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $coManager->setConfirmationToken($tokenGenerator->generateToken());
            $this->get('service.account_confirmation')->password_resetting($coManager);

            return $coManager;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('user_comanager_show')")
     * @ApiDoc(
     *  section="[Users] CoManager",
     *  description="Finds and displays a coManager entity.",
     *  output="ApiBundle\Entity\User\CoManager",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="user_comanager_show")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param CoManager $coManager
     * @param Company $company
     * @return CoManager
     */
    public function showAction(Company $company, CoManager $coManager)
    {
        $user = $this->getUser();

        if(!$coManager) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$company->getUsers()->contains($user) || !$company->getUsers()->contains($coManager))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.co_manager'));
        }

        return $coManager;
    }

    /**
     * @Security("is_granted('user_comanager_edit')")
     * @ApiDoc(
     *  section="[Users] CoManager",
     *  description="Displays a form to edit an existing coManager entity.",
     *  output="ApiBundle\Entity\User\CoManager",
     *  input="ApiBundle\Form\User\CoManagerType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="user_comanager_put")
     * @Rest\Patch("/{id}", name="user_comanager_patch")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Request $request
     * @param CoManager $coManager
     * @param Company $company
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function editAction(Company $company, Request $request, CoManager $coManager)
    {
        $user = $this->getUser();

        if(!$coManager) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$company->getUsers()->contains($user) || !$company->getUsers()->contains($coManager))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.co_manager'));
        }

        $editForm = $this->createForm('ApiBundle\Form\User\CoManagerType', $coManager, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            /** @var UserManagerInterface $userManager */
            $user_manager = $this->get('fos_user.user_manager');
            $user_manager->updateUser($coManager);
            $this->getDoctrine()->getManager()->flush();
            return new Response();
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('user_comanager_delete')")
     * @ApiDoc(
     *  section="[Users] CoManager",
     *  description="Deletes a coManager entity.",
     *  output="ApiBundle\Entity\User\CoManager",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="user_comanager_delete")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param CoManager $coManager
     * @param Company $company
     * @return Response
     */
    public function deleteAction(Company $company, CoManager $coManager)
    {
        $user = $this->getUser();

        if(!$coManager) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && (!$company->getUsers()->contains($user) || !$company->getUsers()->contains($coManager))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.co_manager'));
        }

        foreach ($coManager->getGroups() as $item) {
            $coManager->removeGroup($item);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($coManager);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('user_comanager_avatar')")
     * @ApiDoc(
     *     section="[Users] CoManager",
     *     description="Uploading user avatar",
     *     output="ApiBundle\Entity\User\CoManager",
     *     tags = {
     *          "Implemented" = "green",
     *     }
     * )
     * @Rest\Post("/{id}/avatars", name="user_comanager_avatar")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param CoManager $coManager
     * @param Company $company
     * @return CoManager|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadAvatarAction(Company $company, Request $request, CoManager $coManager)
    {
        $user = $this->getUser();
        if($user->hasRole(AbstractUser::ROLE_ADMIN)) {
            if(!$coManager->getCompany()->getUsers()->contains($user) || !$company->getUsers()->contains($coManager)) {
                throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
            }
        }
        else if($user->hasRole(AbstractUser::ROLE_CO_MANAGER) && $user !== $coManager) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }
        $form = $this->createForm(CoManagerAvatarType::class, $coManager, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            $uploadableManager->markEntityToUpload($coManager->getAvatar(), $coManager->getAvatar()->getPath());

            $this->getDoctrine()->getManager()->persist($coManager);
            $this->getDoctrine()->getManager()->flush();

            return $coManager;
        }

        return $this->getJsonErrorsResponse($form);
    }

    /**
     * @Security("is_granted('user_comanager_avatar')")
     * @ApiDoc(
     *     section="[Users] CoManager",
     *     description="Delete user CoManager",
     *     output="ApiBundle\Entity\User\CoManager",
     *     tags = {
     *          "Implemented" = "green",
     *     }
     * )
     * @Rest\Delete("/{id}/avatars", name="user_comanager_avatar_delete")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Company $company
     * @param CoManager $coManager
     * @return CoManager|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAvatarAction(Company $company, CoManager $coManager)
    {
        $user = $this->getUser();
        if($user->hasRole(AbstractUser::ROLE_ADMIN)) {
            if(!$coManager->getCompany()->getUsers()->contains($user) || !$company->getUsers()->contains($coManager)) {
                throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
            }
        }
        else if($user->hasRole(AbstractUser::ROLE_CO_MANAGER) && $user !== $coManager) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }

        /** @var $avatar AvatarFile*/
        $avatar = $coManager->getAvatar();
        if(!empty($coManager)) {
            $fs = new Filesystem();
            if($fs->exists($avatar->getPath())) {
                $fs->remove($avatar->getPath());
            }
            $coManager->setAvatar(null);
            $this->getDoctrine()->getManager()->persist($coManager);
            $this->getDoctrine()->getManager()->remove($avatar);
            $this->getDoctrine()->getManager()->flush();
        }

        return $coManager;
    }
}
