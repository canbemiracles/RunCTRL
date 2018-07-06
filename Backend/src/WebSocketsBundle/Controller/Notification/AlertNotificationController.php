<?php

namespace WebSocketsBundle\Controller\Notification;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\Notification\CustomNotification;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Service\AdminPanel\UsersManagementService;
use Doctrine\Common\Collections\Collection;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use WebSocketsBundle\Entity\Notification\AlertNotification;
use WebSocketsBundle\Entity\Notification\AnnouncementNotification;
use WebSocketsBundle\Service\NotificationService;

/**
 * AlertNotification controller.
 *
 * @Route("alert_notifications")
 */

class AlertNotificationController extends AbstractController
{
    /**
     * @Security("is_granted('alert_notifications_index')")
     * @Rest\QueryParam(name="user_id", nullable=false)
     * @ApiDoc(
     *  section="AlertNotification",
     *  description="Lists all AlertNotification entities.",
     *  output="WebSocketsBundle\Entity\Notification\AlertNotification",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="alert_notifications_index")
     * @ParamConverter("user", options={"mapping": {"user_id" : "id"}})
     * @Rest\View()
     * @param ParamFetcher $paramFetcher
     * @return array|Collection AlertNotification
     */
    public function indexAction(ParamFetcher $paramFetcher)
    {
        $user_id = $paramFetcher->get('user_id');

        /** @var $cur_user AbstractUser*/
        $cur_user = $this->getDoctrine()->getRepository('ApiBundle:User\AbstractUser')->find($user_id);

        /** @var $user AbstractUser*/
        $user = $this->getUser();

        if(!$user->hasRole('ROLE_OWNER') && !$user === $cur_user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.custom_notifications'));
        }

        return $cur_user->getAlerts();
    }


    /**
     * @Security("is_granted('alert_notification_show')")
     * @ApiDoc(
     *  section="AlertNotification",
     *  description="Finds and displays a AlertNotification entity.",
     *  output="WebSocketsBundle\Entity\Notification\AlertNotification",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="alert_notification_show")
     * @Rest\View()
     * @param AlertNotification $alert_notification
     * @return object
     */
    public function showAction(AlertNotification $alert_notification)
    {
        if(!$alert_notification){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $cur_user = $this->getUser();

        if(!$cur_user->hasRole('ROLE_OWNER') && $alert_notification->getUser() !== $cur_user) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.custom_notifications'));
        }

        return $alert_notification;
    }

}
