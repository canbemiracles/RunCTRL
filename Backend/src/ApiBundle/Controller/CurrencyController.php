<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Currency;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Currency controller.
 *
 * @Route("currencies")
 */
class CurrencyController extends AbstractController
{
    /**
     * @Security("is_granted('currency_index')")
     * @ApiDoc(
     *  section="Currency",
     *  description="Lists all currency entities.",
     *  output="ApiBundle\Entity\Currency",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="currency_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:Currency')->findAll();
    }

    /**
     * @Security("is_granted('currency_new')")
     * @ApiDoc(
     *  section="Currency",
     *  description="Creates a new currency entity.",
     *  input="ApiBundle\Form\CurrencyType",
     *  output="ApiBundle\Entity\Currency",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="currency_new")
     * @Rest\View()
     * @param Request $request
     * @return Currency|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $currency = new Currency();
        $form = $this->createForm('ApiBundle\Form\CurrencyType', $currency);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($currency);
            $em->flush();

            return $currency;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('currency_show')")
     * @ApiDoc(
     *  section="Currency",
     *  description="Finds and displays a currency entity.",
     *  output="ApiBundle\Entity\Currency",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="currency_show")
     * @Rest\View()
     * @param Currency $currency
     * @return Currency
     */
    public function showAction(Currency $currency)
    {
        if(!$currency) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        return $currency;
    }

    /**
     * @Security("is_granted('currency_edit')")
     * @ApiDoc(
     *  section="Currency",
     *  description="Displays a form to edit an existing currency entity.",
     *  output="ApiBundle\Entity\Currency",
     *  input="ApiBundle\Form\CurrencyType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="currency_put")
     * @Rest\Patch("/{id}", name="currency_patch")
     * @param Request $request
     * @param Currency $currency
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function editAction(Request $request, Currency $currency)
    {
        $editForm = $this->createForm('ApiBundle\Form\CurrencyType', $currency, ['method' => $request->getMethod()]);
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
     * @Security("is_granted('currency_delete')")
     * @ApiDoc(
     *  section="Currency",
     *  description="Deletes a currency entity.",
     *  output="ApiBundle\Entity\Currency",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="currency_delete")
     * @Rest\View()
     * @param Currency $currency
     * @return Response
     */
    public function deleteAction(Currency $currency)
    {
        if(!$currency){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($currency);
        $em->flush();

        return new Response();
    }

}
