<?php

namespace ApiBundle\Controller\User;

use ApiBundle\Entity\Company;
use ApiBundle\Entity\File\AvatarFile;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\Admin;
use ApiBundle\Form\User\Avatar\AdminAvatarType;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Admin controller.
 *
 * @Route("users/admins")
 */
class AdminController extends AbstractController
{
    /**
     * @Security("is_granted('user_admin_index')")
     * @ApiDoc(
     *  section="[Users] Admin",
     *  description="Lists all admin entities.",
     *  output="ApiBundle\Entity\User\Admin",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="user_admin_index")
     * @Rest\View()
     * @return array Admin
     */
    public function indexAction()
    {
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_DEVICE)) {
            return $user;
        }

        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:User\Admin')->findAll();
    }

    /**
     * @ApiDoc(
     *  section="[Users] Admin",
     *  description="Create admin entity.",
     *  input="ApiBundle\Form\User\AdminType",
     *  output="ApiBundle\Entity\User\Admin",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="user_admin_new")
     * @Rest\View()
     * @param Request $request
     * @return Admin|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $admin = new Admin();
        $form = $this->createForm('ApiBundle\Form\User\AdminType', $admin);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $email = $admin->getEmail();
            $existingAccount = $em->getRepository('ApiBundle:User\AbstractUser')->findOneBy(['email' => $email]);
            if($existingAccount !== null)
            {
                throw new BadRequestHttpException('This email is already in use');
            }
            
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $admin->setConfirmationToken($tokenGenerator->generateToken());
            $this->get('fos_user.mailer')->sendConfirmationEmailMessage($admin);

            $admin->addGroup($this->getDoctrine()->getRepository('ApiBundle:User\Group')->findOneBy(['name' => 'admin']));
            $em->persist($admin);
            $em->flush();

            return $admin;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('user_admin_show')")
     * @ApiDoc(
     *  section="[Users] Admin",
     *  description="Finds and displays a admin entity.",
     *  output="ApiBundle\Entity\User\Admin",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="user_admin_show")
     * @Rest\View()
     * @param Admin $admin
     * @return Admin
     */
    public function showAction(Admin $admin)
    {
        if(!$admin) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }
        $user = $this->getUser();

        if($user->hasRole(AbstractUser::ROLE_ADMIN) && $user !== $admin) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.admin'));
        }
        return $admin;
    }

    /**
     * @Security("is_granted('user_admin_edit')")
     * @ApiDoc(
     *  section="[Users] Admin",
     *  description="Displays a form to edit an existing admin entity.",
     *  output="ApiBundle\Entity\User\Admin",
     *  input="ApiBundle\Form\User\AdminType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="user_admin_put")
     * @Rest\Patch("/{id}", name="user_admin_patch")
     * @param Request $request
     * @param Admin $admin
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Admin
     */
    public function editAction(Request $request, Admin $admin)
    {
        $user = $this->getUser();
        if($user->hasRole(AbstractUser::ROLE_ADMIN) && $user !== $admin) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.admin'));
        }

        $editForm = $this->createForm('ApiBundle\Form\User\AdminType', $admin, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            /** @var UserManagerInterface $userManager */
            $user_manager = $this->get('fos_user.user_manager');
            $user_manager->updateUser($admin);
            $this->getDoctrine()->getManager()->flush();
            return $admin;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('user_admin_delete')")
     * @ApiDoc(
     *  section="[Users] Admin",
     *  description="Deletes a branch entity",
     *  output="ApiBundle\Entity\User\Admin",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="user_admin_delete")
     * @Rest\View()
     * @param Admin $admin
     * @return Response
     */
    public function deleteAction(Admin $admin)
    {
        if(!$admin){
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }


        foreach ($admin->getGroups() as $item) {
            $admin->removeGroup($item);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($admin);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('user_admin_avatar')")
     * @ApiDoc(
     *     section="[Users] Admin",
     *     description="Uploading user avatar",
     *     output="ApiBundle\Entity\User\Admin",
     *     tags = {
     *          "Implemented" = "green",
     *     }
     * )
     * @Rest\Post("/{id}/avatars", name="user_admin_avatar")
     * @Rest\View()
     * @param Request $request
     * @param Admin $admin
     * @return Admin|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadAvatarAction(Request $request, Admin $admin)
    {
        $user = $this->getUser();
        if($user->hasRole(AbstractUser::ROLE_ADMIN) && $user !== $admin) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }
        $form = $this->createForm(AdminAvatarType::class, $admin, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            $uploadableManager->markEntityToUpload($admin->getAvatar(), $admin->getAvatar()->getPath());

            $this->getDoctrine()->getManager()->persist($admin);
            $this->getDoctrine()->getManager()->flush();

            return $admin;
        }

        return $this->getJsonErrorsResponse($form);
    }

    /**
     * @Security("is_granted('user_admin_avatar')")
     * @ApiDoc(
     *     section="[Users] Admin",
     *     description="Delete user avatar",
     *     output="ApiBundle\Entity\User\Admin",
     *     tags = {
     *          "Implemented" = "green",
     *     }
     * )
     * @Rest\Delete("/{id}/avatars", name="user_admin_avatar_delete")
     * @Rest\View()
     * @param Admin $admin
     * @return Admin|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAvatarAction(Admin $admin)
    {
        $user = $this->getUser();
        if($user->hasRole(AbstractUser::ROLE_ADMIN) && $user !== $admin) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.change_avatar'));
        }

        /** @var $avatar AvatarFile*/
        $avatar = $admin->getAvatar();
        if(!empty($avatar)) {
            $fs = new Filesystem();
            if($fs->exists($avatar->getPath())) {
                $fs->remove($avatar->getPath());
            }
            $admin->setAvatar(null);
            $this->getDoctrine()->getManager()->persist($admin);
            $this->getDoctrine()->getManager()->remove($avatar);
            $this->getDoctrine()->getManager()->flush();
        }

        return $admin;
    }

    /**
     * @ApiDoc(
     *  section="[Users] Admin",
     *  description="resending Message",
     *  output="ApiBundle\Entity\User\Admin",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/resend_message", name="user_admin_resend_message")
     * @Rest\View()
     * @param Request $request
     * @return Admin|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function resendingMessageAction(Request $request)
    {
        if($request->get('email')) {
            $em = $this->getDoctrine()->getManager();
            /** @var $admin Admin*/
            $admin = $em->getRepository('ApiBundle:User\Admin')->findOneBy(['email' => $request->get('email')]);
            if($admin === null) {
               return $this->get('translator')->trans('not_found_email');
            } elseif($admin->getConfirmationToken() !== null) {
               return $this->get('translator')->trans('email_already_verified');
            }
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $admin->setConfirmationToken($tokenGenerator->generateToken());
            $mailer = $this->get('fos_user.mailer');
            $mailer->sendConfirmationEmailMessage($admin);
            $em->persist($admin);
            $em->flush();
            return $admin;
        } else {
            throw new BadRequestHttpException($this->get('translator')->trans('not_email'));
        }
    }
}
