<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\TimeFormat;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * TimeFormat controller.
 *
 * @Route("time_formats")
 */

class TimeFormatController extends AbstractController
{
    /**
     * @Security("is_granted('time_format_index')")
     * @ApiDoc(
     *  section="TimeFormat",
     *  description="Lists all TimeFormat entities.",
     *  output="ApiBundle\Entity\TimeFormat",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="time_format_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:TimeFormat')->findAll();
    }

    /**
     * @Security("is_granted('time_format_new')")
     * @ApiDoc(
     *  section="TimeFormat",
     *  description="Creates a new TimeFormat entity.",
     *  input="ApiBundle\Form\TimeFormatType",
     *  output="ApiBundle\Entity\TimeFormat",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="time_format_new")
     * @Rest\View()
     * @param Request $request
     * @return TimeFormat|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $time_format = new TimeFormat();
        $form = $this->createForm('ApiBundle\Form\TimeFormatType', $time_format);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($time_format);
            $em->flush();

            return $time_format;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('time_format_show')")
     * @ApiDoc(
     *  section="TimeFormat",
     *  description="Finds and displays a TimeFormat entity.",
     *  output="ApiBundle\Entity\TimeFormat",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="time_format_show")
     * @Rest\View()
     * @param TimeFormat $time_format
     * @return TimeFormat
     */
    public function showAction(TimeFormat $time_format)
    {
        if(!$time_format){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        return $time_format;
    }

    /**
     * @Security("is_granted('time_format_edit')")
     * @ApiDoc(
     *  section="TimeFormat",
     *  description="Displays a form to edit an existing TimeFormat entity.",
     *  output="ApiBundle\Entity\TimeFormat",
     *  input="ApiBundle\Form\TimeFormatType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="time_format_put")
     * @Rest\Patch("/{id}", name="time_format_patch")
     * @param Request $request
     * @param TimeFormat $time_format
     * @return \Symfony\Component\HttpFoundation\JsonResponse|TimeFormat
     */
    public function editAction(Request $request, TimeFormat $time_format)
    {
        $editForm = $this->createForm('ApiBundle\Form\TimeFormatType', $time_format, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $time_format;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('time_format_delete')")
     * @ApiDoc(
     *  section="TimeFormat",
     *  description="Deletes a TimeFormat entity.",
     *  output="ApiBundle\Entity\TimeFormat",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="time_format_delete")
     * @Rest\View()
     * @param TimeFormat $time_format
     * @return Response
     */
    public function deleteAction(TimeFormat $time_format)
    {
        if(!$time_format){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($time_format);
        $em->flush();

        return new Response();
    }
}