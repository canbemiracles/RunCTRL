<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\TimeZone;
use ApiBundle\Entity\UTC;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * UTC controller.
 *
 * @Route("utc")
 */
class UTCController extends AbstractController
{
    /**
     * @Security("is_granted('utc_index')")
     * @ApiDoc(
     *  section="UTC",
     *  description="Lists all utc entities.",
     *  output="ApiBundle\Entity\TimeZone",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="utc_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:UTC')->findAll();
    }

    /**
     * @Security("is_granted('utc_new')")
     * @ApiDoc(
     *  section="UTC",
     *  description="Creates a new utc entity.",
     *  input="ApiBundle\Form\UTCType",
     *  output="ApiBundle\Entity\UTC",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="utc_new")
     * @Rest\View()
     * @param Request $request
     * @return UTC|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $utc = new UTC();
        $form = $this->createForm('ApiBundle\Form\UTCType', $utc);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($utc);
            $em->flush();

            return $utc;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('utc_show')")
     * @ApiDoc(
     *  section="UTC",
     *  description="Finds and displays a utc entity.",
     *  output="ApiBundle\Entity\UTC",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="utc_show")
     * @Rest\View()
     * @param UTC $utc
     * @return UTC
     */
    public function showAction(UTC $utc)
    {
        if(!$utc){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }
        return $utc;
    }

    /**
     * @Security("is_granted('utc_edit')")
     * @ApiDoc(
     *  section="UTC",
     *  description="Displays a form to edit an existing utc entity.",
     *  output="ApiBundle\Entity\UTC",
     *  input="ApiBundle\Form\UTCType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="utc_put")
     * @Rest\Patch("/{id}", name="utc_patch")
     * @param Request $request
     * @param UTC $utc
     * @return UTC|\Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function editAction(Request $request, UTC $utc)
    {
        $editForm = $this->createForm('ApiBundle\Form\UTCType', $utc, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $utc;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('utc_delete')")
     * @ApiDoc(
     *  section="UTC",
     *  description="Deletes a utc entity.",
     *  output="ApiBundle\Entity\UTC",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="utc_delete")
     * @Rest\View()
     * @param UTC $utc
     * @return Response
     */
    public function deleteAction(UTC $utc)
    {
        if(!$utc){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($utc);
        $em->flush();

        return new Response();
    }
}
