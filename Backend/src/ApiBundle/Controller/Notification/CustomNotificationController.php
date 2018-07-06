<?php

namespace ApiBundle\Controller\Notification;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\Notification\CustomNotification;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Service\AdminPanel\UsersManagementService;
use Doctrine\Common\Collections\Collection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * CustomNotification controller.
 *
 * @Route("company/{company_id}/custom_notifications")
 */

class CustomNotificationController extends AbstractController
{
    /**
     * @Security("is_granted('custom_notification_index')")
     * @ApiDoc(
     *  section="[Notification] CustomNotification",
     *  description="Lists all CustomNotification entities.",
     *  output="ApiBundle\Entity\Notification\CustomNotification",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="custom_notifications_index")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Company $company
     * @return array|Collection CustomNotification
     */
    public function indexAction(Company $company)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.custom_notifications'));
        }

        return $company->getCustomNotifications();
    }

    /**
     * @Security("is_granted('custom_notification_new')")
     * @ApiDoc(
     *  section="[Notification] CustomNotification",
     *  description="Creates a new CustomNotification entity.",
     *  input="ApiBundle\Form\Notification\CustomNotificationType",
     *  output="ApiBundle\Entity\Notification\CustomNotification",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\Post("/new", name="custom_notification_new")
     * @Rest\View()
     * @param Request $request
     * @param Company $company
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Company $company)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.custom_notifications'));
        }

        $form = $this->createForm('ApiBundle\Form\Notification\CustomNotificationType',  new CustomNotification());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $customNotification = $form->getData();
            $customNotification->setCompany($company);
            $em->persist($customNotification);
            $em->flush();

            return $customNotification;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }


    /**
     * @Security("is_granted('custom_notification_show')")
     * @ApiDoc(
     *  section="[Notification] CustomNotification",
     *  description="Finds and displays a CustomNotification entity.",
     *  output="ApiBundle\Entity\Notification\CustomNotification",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="custom_notification_show")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param CustomNotification $custom_notification
     * @param Company $company
     * @return object
     */
    public function showAction(CustomNotification $custom_notification, Company $company)
    {
        if(!$custom_notification){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.custom_notifications'));
        }

        return $custom_notification;
    }

    /**
     * @Security("is_granted('custom_notification_edit')")
     * @ApiDoc(
     *  section="[Notification] CustomNotification",
     *  description="Displays a form to edit an existing custom_notification entity.",
     *  output="ApiBundle\Entity\Notification\CustomNotification",
     *  input="ApiBundle\Form\Notification\CustomNotificationType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="custom_notification_put")
     * @Rest\Patch("/{id}", name="custom_notification_patch")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Request $request
     * @param CustomNotification $custom_notification
     * @param Company $company
     * @return CustomNotification | \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function editAction(Request $request, CustomNotification $custom_notification, Company $company)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.custom_notifications'));
        }

        $editForm = $this->createForm('ApiBundle\Form\Notification\CustomNotificationType', $custom_notification, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $custom_notification;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('custom_notification_delete')")
     * @ApiDoc(
     *  section="[Notification] CustomNotification",
     *  description="Deletes a CustomNotification entity.",
     *  output="ApiBundle\Entity\Notification\CustomNotification",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="custom_notification_delete")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param CustomNotification $custom_notification
     * @param Company $company
     * @return Response
     */
    public function deleteAction(Request $request, CustomNotification $custom_notification, Company $company)
    {
        if(!$custom_notification){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.custom_notifications'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($custom_notification);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('custom_notification_choose_users')")
     * @ApiDoc(
     *  section="[Notification] CustomNotification",
     *  description="",
     *  output="ApiBundle\Entity\Notification\CustomNotification",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/choose_users", name="custom_notification_choose_users")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param CustomNotification $custom_notification
     * @param Company $company
     * @return Response
     */
    public function chooseUsersAction(Request $request, CustomNotification $custom_notification, Company $company)
    {
        if(!$custom_notification){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.custom_notifications'));
        }

        /** @var $service UsersManagementService*/
        $service = $this->get('service.admin_panel.users_management');
        return $service->choose_users($request->get('custom_notification'), $request->get('users'));

    }

}
