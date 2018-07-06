<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\ShiftDay;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Shiftday controller.
 *
 * @Route("shift_days")
 */
class ShiftDayController extends AbstractController
{
    /**
     * @Security("is_granted('shift_day_index')")
     * @ApiDoc(
     *  section="ShiftDay",
     *  description="Lists all shiftDay entities.",
     *  output="ApiBundle\Entity\ShiftDay",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="shift_day_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:ShiftDay')->findAll();
    }

    /**
     * @Security("is_granted('shift_day_new')")
     * @ApiDoc(
     *  section="ShiftDay",
     *  description="Creates a new shiftDay entity.",
     *  input="ApiBundle\Form\ShiftDayType",
     *  output="ApiBundle\Entity\ShiftDay",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="shift_day_new")
     * @Rest\View()
     * @param Request $request
     * @return ShiftDay|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $shiftDay = new ShiftDay();
        $form = $this->createForm('ApiBundle\Form\ShiftDayType', $shiftDay);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shiftDay);
            $em->flush();

            return $shiftDay;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('shift_day_show')")
     * @ApiDoc(
     *  section="ShiftDay",
     *  description="Finds and displays a shiftDay entity.",
     *  output="ApiBundle\Entity\ShiftDay",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="shift_day_show")
     * @Rest\View()
     * @param ShiftDay $shiftDay
     * @return ShiftDay
     */
    public function showAction(ShiftDay $shiftDay)
    {
        if(!$shiftDay) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }
        return $shiftDay;
    }

    /**
     * @Security("is_granted('shift_day_edit')")
     * @ApiDoc(
     *  section="ShiftDay",
     *  description="Displays a form to edit an existing shiftDay entity.",
     *  output="ApiBundle\Entity\ShiftDay",
     *  input="ApiBundle\Form\ShiftDayType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="shift_day_put")
     * @Rest\Patch("/{id}", name="shift_day_patch")
     * @param Request $request
     * @param ShiftDay $shiftDay
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function editAction(Request $request, ShiftDay $shiftDay)
    {
        $editForm = $this->createForm('ApiBundle\Form\ShiftDayType', $shiftDay, ['method' => $request->getMethod()]);
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
     * @Security("is_granted('shift_day_delete')")
     * @ApiDoc(
     *  section="ShiftDay",
     *  description="Deletes a shiftDay entity.",
     *  output="ApiBundle\Entity\ShiftDay",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="shift_day_delete")
     * @Rest\View()
     * @param ShiftDay $shiftDay
     * @return Response
     */
    public function deleteAction(ShiftDay $shiftDay)
    {
        if(!$shiftDay){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($shiftDay);
        $em->flush();

        return new Response();
    }

}
