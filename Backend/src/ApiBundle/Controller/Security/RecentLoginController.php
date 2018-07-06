<?php

namespace ApiBundle\Controller\Security;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Security\RecentLogin;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Repository\Security\RecentLoginRepository;
use Doctrine\Common\Collections\Collection;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Recentlogin controller.
 *
 * @Route("/users/{user_id}/recent_logins/")
 */
class RecentLoginController extends AbstractController
{
    /**
     * @Security("is_granted('security_recent_login')")
     * @ApiDoc(
     *  section="[Security] Recent Logins",
     *  description="Lists all recent logins of user.",
     *  output="ApiBundle\Entity\Security\RecentLogin",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="security_recent_login")
     * @ParamConverter("user", options={"mapping": {"user_id" : "id"}})
     * @Rest\View()
     * @param AbstractUser $user
     * @return array
     */
    public function indexAction(AbstractUser $user, Request $request)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if($user !== $currentUser)
        {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.this'));
        }
        $em = $this->getDoctrine()->getManager();

        $page = 1;

        if($request->query->get('page') != null) {
            $page = $request->query->get('page');
            $request->query->remove('page');
        }

        $pager = $this->get('knp_paginator');

        /** @var $repository RecentLoginRepository*/
        $repository = $em->getRepository('ApiBundle:Security\RecentLogin');

        /** @var $recentLogins array RecentLogin */
        $recentLogins = $repository->findBy(['user' => $user], ['date' => 'DESC']);

        return $pager->paginate($recentLogins, $page, 10);
    }

}
