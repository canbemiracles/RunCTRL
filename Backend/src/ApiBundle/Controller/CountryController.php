<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Country;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Country controller.
 *
 * @Route("countries")
 */
class CountryController extends AbstractController
{
    /**
     * @ApiDoc(
     *  section="Country",
     *  description="Lists all country entities.",
     *  output="ApiBundle\Entity\Country",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="country_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:Country')->findAll();
    }

    /**
     * @Security("is_granted('country_new')")
     * @ApiDoc(
     *  section="Country",
     *  description="Creates a new country entity.",
     *  input="ApiBundle\Form\CountryType",
     *  output="ApiBundle\Entity\Country",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="country_new")
     * @Rest\View()
     * @param Request $request
     * @return Country|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $country = new Country();
        $form = $this->createForm('ApiBundle\Form\CountryType', $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($country);
            $em->flush();

            return $country;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('country_show')")
     * @ApiDoc(
     *  section="Country",
     *  description="Finds and displays a country entity.",
     *  output="ApiBundle\Entity\Country",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="country_show")
     * @Rest\View()
     * @param Country $country
     * @return Country
     */
    public function showAction(Country $country)
    {
        if(!$country){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }
        return $country;
    }

    /**
     * @Security("is_granted('country_edit')")
     * @ApiDoc(
     *  section="Country",
     *  description="Displays a form to edit an existing country entity.",
     *  output="ApiBundle\Entity\Country",
     *  input="ApiBundle\Form\CountryType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="country_put")
     * @Rest\Patch("/{id}", name="country_patch")
     * @param Request $request
     * @param Country $country
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Country
     */
    public function editAction(Request $request, Country $country)
    {
        $editForm = $this->createForm('ApiBundle\Form\CountryType', $country, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $country;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('country_delete')")
     * @ApiDoc(
     *  section="Country",
     *  description="Deletes a country entity.",
     *  output="ApiBundle\Entity\Country",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="country_delete")
     * @Rest\View()
     * @param Country $country
     * @return Response
     */
    public function deleteAction(Country $country)
    {
        if(!$country){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($country);
        $em->flush();

        return new Response();
    }
}
