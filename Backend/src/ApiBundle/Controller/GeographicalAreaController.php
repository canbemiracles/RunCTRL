<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\GeographicalArea;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Geographicalarea controller.
 *
 * @Route("geographical_areas")
 */
class GeographicalAreaController extends AbstractController
{
    /**
     * @Security("is_granted('geographic_area_index')")
     * @ApiDoc(
     *  section="GeographicalArea",
     *  description="Lists all geographicalArea entities.",
     *  output="ApiBundle\Entity\GeographicalArea",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="geographic_area_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:GeographicalArea')->findAll();
    }

    /**
     * @Security("is_granted('geographical_area_new')")
     * @ApiDoc(
     *  section="GeographicalArea",
     *  description="Creates a new geographicalArea entity.",
     *  input="ApiBundle\Form\GeographicalAreaType",
     *  output="ApiBundle\Entity\GeographicalArea",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="geographical_area_new")
     * @Rest\View()
     * @param Request $request
     * @return GeographicalArea|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $geographicalArea = new Geographicalarea();
        $form = $this->createForm('ApiBundle\Form\GeographicalAreaType', $geographicalArea);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($geographicalArea);
            $em->flush();

            return $geographicalArea;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('geographical_area_show')")
     * @ApiDoc(
     *  section="GeographicalArea",
     *  description="Finds and displays a geographicalArea entity.",
     *  output="ApiBundle\Entity\GeographicalArea",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="geographical_area_show")
     * @Rest\View()
     * @param GeographicalArea $geographicalArea
     * @return GeographicalArea
     */
    public function showAction(GeographicalArea $geographicalArea)
    {
        if(!$geographicalArea){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }
        return $geographicalArea;
    }

    /**
     * @Security("is_granted('geographical_area_edit')")
     * @ApiDoc(
     *  section="GeographicalArea",
     *  description="Displays a form to edit an existing geographicalArea entity.",
     *  output="ApiBundle\Entity\GeographicalArea",
     *  input="ApiBundle\Form\GeographicalAreaType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="geographical_area_put")
     * @Rest\Patch("/{id}", name="geographical_area_patch")
     * @param Request $request
     * @param GeographicalArea $geographicalArea
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function editAction(Request $request, GeographicalArea $geographicalArea)
    {
        $editForm = $this->createForm('ApiBundle\Form\GeographicalAreaType', $geographicalArea, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new Response();
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('geographical_area_delete')")
     * @ApiDoc(
     *  section="GeographicalArea",
     *  description="Deletes a geographicalArea entity.",
     *  output="ApiBundle\Entity\GeographicalArea",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="geographical_area_delete")
     * @Rest\View()
     * @param GeographicalArea $geographicalArea
     * @return Response
     */
    public function deleteAction(GeographicalArea $geographicalArea)
    {
        if(!$geographicalArea){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($geographicalArea);
        $em->flush();

        return new Response();
    }
}
