<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\DateFormat;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * DateFormat controller.
 *
 * @Route("date_formats")
 */

class DateFormatController extends AbstractController
{
    /**
     * @Security("is_granted('date_format_index')")
     * @ApiDoc(
     *  section="DateFormat",
     *  description="Lists all dateFormat entities.",
     *  output="ApiBundle\Entity\DateFormat",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="date_format_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:DateFormat')->findAll();
    }

    /**
     * @Security("is_granted('date_format_new')")
     * @ApiDoc(
     *  section="DateFormat",
     *  description="Creates a new DateFormat entity.",
     *  input="ApiBundle\Form\DateFormatType",
     *  output="ApiBundle\Entity\DateFormat",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="date_format_new")
     * @Rest\View()
     * @param Request $request
     * @return DateFormat|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $date_format = new DateFormat();
        $form = $this->createForm('ApiBundle\Form\DateFormatType', $date_format);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($date_format);
            $em->flush();

            return $date_format;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('date_format_show')")
     * @ApiDoc(
     *  section="DateFormat",
     *  description="Finds and displays a DateFormat entity.",
     *  output="ApiBundle\Entity\DateFormat",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="date_format_show")
     * @Rest\View()
     * @param DateFormat $date_format
     * @return DateFormat
     */
    public function showAction(DateFormat $date_format)
    {
        if(!$date_format){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        return $date_format;
    }

    /**
     * @Security("is_granted('date_format_edit')")
     * @ApiDoc(
     *  section="DateFormat",
     *  description="Displays a form to edit an existing DateFormat entity.",
     *  output="ApiBundle\Entity\DateFormat",
     *  input="ApiBundle\Form\DateFormatType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="date_format_put")
     * @Rest\Patch("/{id}", name="date_format_patch")
     * @param Request $request
     * @param DateFormat $date_format
     * @return \Symfony\Component\HttpFoundation\JsonResponse|DateFormat
     */
    public function editAction(Request $request, DateFormat $date_format)
    {
        $editForm = $this->createForm('ApiBundle\Form\DateFormatType', $date_format, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $date_format;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('date_format_delete')")
     * @ApiDoc(
     *  section="DateFormat",
     *  description="Deletes a DateFormat entity.",
     *  output="ApiBundle\Entity\DateFormat",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="date_format_delete")
     * @Rest\View()
     * @param DateFormat $date_format
     * @return Response
     */
    public function deleteAction(DateFormat $date_format)
    {
        if(!$date_format){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($date_format);
        $em->flush();

        return new Response();
    }
}