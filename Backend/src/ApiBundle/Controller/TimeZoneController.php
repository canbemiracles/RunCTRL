<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\TimeZone;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Timezone controller.
 *
 * @Route("time_zones")
 */
class TimeZoneController extends AbstractController
{
    /**
     * @Security("is_granted('subcategory_index')")
     * @ApiDoc(
     *  section="TimeZone",
     *  description="Lists all timeZone entities.",
     *  output="ApiBundle\Entity\TimeZone",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="time_zone_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:TimeZone')->findAll();
    }

    /**
     * @Security("is_granted('time_zone_new')")
     * @ApiDoc(
     *  section="TimeZone",
     *  description="Creates a new timeZone entity.",
     *  input="ApiBundle\Form\TimeZoneType",
     *  output="ApiBundle\Entity\TimeZone",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="time_zone_new")
     * @Rest\View()
     * @param Request $request
     * @return TimeZone|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $timeZone = new Timezone();
        $form = $this->createForm('ApiBundle\Form\TimeZoneType', $timeZone);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($timeZone);
            $em->flush();

            return $timeZone;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('time_zone_show')")
     * @ApiDoc(
     *  section="TimeZone",
     *  description="Finds and displays a timeZone entity.",
     *  output="ApiBundle\Entity\TimeZone",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="time_zone_show")
     * @Rest\View()
     * @param TimeZone $timeZone
     * @return TimeZone
     */
    public function showAction(TimeZone $timeZone)
    {
        if(!$timeZone){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }
        return $timeZone;
    }

    /**
     * @Security("is_granted('time_zone_edit')")
     * @ApiDoc(
     *  section="TimeZone",
     *  description="Displays a form to edit an existing timeZone entity.",
     *  output="ApiBundle\Entity\TimeZone",
     *  input="ApiBundle\Form\TimeZoneType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="timezone_put")
     * @Rest\Patch("/{id}", name="time_zone_patch")
     * @param Request $request
     * @param TimeZone $timeZone
     * @return TimeZone|\Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function editAction(Request $request, TimeZone $timeZone)
    {
        $editForm = $this->createForm('ApiBundle\Form\TimeZoneType', $timeZone, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $timeZone;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('time_zone_delete')")
     * @ApiDoc(
     *  section="TimeZone",
     *  description="Deletes a timeZone entity.",
     *  output="ApiBundle\Entity\TimeZone",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="time_zone_delete")
     * @Rest\View()
     * @param TimeZone $timeZone
     * @return Response
     */
    public function deleteAction(TimeZone $timeZone)
    {
        if(!$timeZone){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($timeZone);
        $em->flush();

        return new Response();
    }
}
