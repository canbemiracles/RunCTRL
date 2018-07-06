<?php

namespace ApiBundle\Controller\Security;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Security\WhiteCountry;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Repository\Security\WhiteCountryRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * WhiteCountry controller.
 *
 * @Route("/users/{user_id}/white_country")
 */
class WhiteCountryController extends AbstractController
{
    /**
     * @Security("is_granted('white_country_index')")
     * @ApiDoc(
     *  section="[Security] White Country",
     *  description="Lists all white countries of user.",
     *  output="ApiBundle\Entity\Security\WhiteCountry",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="white_country_index")
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

        /** @var $repository WhiteCountryRepository*/
        $repository = $em->getRepository(WhiteCountry::class);

        /** @var $recentLogins array RecentLogin */
        $recentLogins = $repository->findBy(['user' => $user]);

        return $pager->paginate($recentLogins, $page, 10);
    }

    /**
     * @Security("is_granted('white_country_new')")
     * @ApiDoc(
     *  section="[Security] White Country",
     *  description="Creates a new white country.",
     *  input="ApiBundle\Form\Security\WhiteCountryType",
     *  output="ApiBundle\Entity\Security\WhiteCountry",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="white_country_new")
     * @Rest\View()
     * @param Request $request
     * @return WhiteCountry|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {

        /** @var AbstractUser */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $whiteCountry = new WhiteCountry();

        $form = $this->createForm('ApiBundle\Form\Security\WhiteCountryType', $whiteCountry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($whiteCountry);
            $em->flush();

            return $whiteCountry;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('white_country_delete')")
     * @ApiDoc(
     *  section="[Security] White Country",
     *  description="Deletes a white country.",
     *  output="ApiBundle\Entity\Security\WhiteCountry",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="white_country_delete")
     * @Rest\View()
     * @param WhiteCountry $whiteCountry
     * @return Response
     */
    public function deleteAction(WhiteCountry $whiteCountry)
    {

        if(!$whiteCountry){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && $whiteCountry->getUser() !== $user) {
            throw new AccessDeniedHttpException("You are not allowed to delete this record");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($whiteCountry);
        $em->flush();

        return new Response();
    }

}
