<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\IndustryCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * IndustryCategory controller.
 *
 * @Route("industry_categories")
 */

class IndustryCategoryController extends AbstractController
{
    /**
     * @Security("is_granted('industry_category_index')")
     * @ApiDoc(
     *  section="IndustryCategory",
     *  description="Lists all IndustryCategory entities.",
     *  output="ApiBundle\Entity\IndustryCategory",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="industry_category_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:IndustryCategory')->findAll();
    }

    /**
     * @Security("is_granted('industry_category_new')")
     * @ApiDoc(
     *  section="IndustryCategory",
     *  description="Creates a new IndustryCategory entity.",
     *  input="ApiBundle\Form\IndustryCategoryType",
     *  output="ApiBundle\Entity\IndustryCategory",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="industry_category_new")
     * @Rest\View()
     * @param Request $request
     * @return IndustryCategory|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $industry_category = new IndustryCategory();
        $form = $this->createForm('ApiBundle\Form\IndustryCategoryType', $industry_category);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($industry_category);
            $em->flush();

            return $industry_category;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('industry_category_show')")
     * @ApiDoc(
     *  section="IndustryCategory",
     *  description="Finds and displays a IndustryCategory entity.",
     *  output="ApiBundle\Entity\IndustryCategory",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="industry_category_show")
     * @Rest\View()
     * @param IndustryCategory $industry_category
     * @return IndustryCategory
     */
    public function showAction(IndustryCategory $industry_category)
    {
        if(!$industry_category){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        return $industry_category;
    }

    /**
     * @Security("is_granted('industry_category_edit')")
     * @ApiDoc(
     *  section="IndustryCategory",
     *  description="Displays a form to edit an existing IndustryCategory entity.",
     *  output="ApiBundle\Entity\IndustryCategory",
     *  input="ApiBundle\Form\IndustryCategoryType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="industry_category_put")
     * @Rest\Patch("/{id}", name="industry_category_patch")
     * @param Request $request
     * @param IndustryCategory $industry_category
     * @return \Symfony\Component\HttpFoundation\JsonResponse|IndustryCategory
     */
    public function editAction(Request $request, IndustryCategory $industry_category)
    {
        $editForm = $this->createForm('ApiBundle\Form\IndustryCategoryType', $industry_category, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $industry_category;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('industry_category_delete')")
     * @ApiDoc(
     *  section="IndustryCategory",
     *  description="Deletes a IndustryCategory entity.",
     *  output="ApiBundle\Entity\IndustryCategory",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="industry_category_delete")
     * @Rest\View()
     * @param IndustryCategory $industry_category
     * @return Response
     */
    public function deleteAction(IndustryCategory $industry_category)
    {
        if(!$industry_category){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($industry_category);
        $em->flush();

        return new Response();
    }
}