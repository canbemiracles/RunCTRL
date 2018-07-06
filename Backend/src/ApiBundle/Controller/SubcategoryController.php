<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\IndustryCategory;
use ApiBundle\Entity\Subcategory;
use Doctrine\Common\Collections\Collection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Subcategory controller.
 *
 * @Route("industry_categories/{category_id}/subcategories")
 */

class SubcategoryController extends AbstractController
{
    /**
     * @Security("is_granted('subcategory_index')")
     * @ApiDoc(
     *  section="Subcategory",
     *  description="Lists all Subcategory entities.",
     *  output="ApiBundle\Entity\Subcategory",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="subcategory_index")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @param IndustryCategory $industry_category
     * @Rest\View()
     * @return array|Collection Subcategory
     */
    public function indexAction(IndustryCategory $industry_category)
    {
        return $industry_category->getSubcategories();
    }

    /**
     * @Security("is_granted('subcategory_new')")
     * @ApiDoc(
     *  section="Subcategory",
     *  description="Creates a new Subcategory entity.",
     *  input="ApiBundle\Form\SubcategoryType",
     *  output="ApiBundle\Entity\Subcategory",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="subcategory_new")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param IndustryCategory $industry_category
     * @return Subcategory|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, IndustryCategory $industry_category)
    {
        $form = $this->createForm('ApiBundle\Form\SubcategoryType',  new Subcategory());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $subcategory = $form->getData();
            $subcategory->setCategory($industry_category);
            $em->persist($subcategory);
            $em->flush();

            return $subcategory;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('subcategory_show')")
     * @ApiDoc(
     *  section="Subcategory",
     *  description="Finds and displays a Subcategory entity.",
     *  output="ApiBundle\Entity\Subcategory",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="subcategory_show")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @Rest\View()
     * @param Subcategory $subcategory
     * @param IndustryCategory $industry_category
     * @return Subcategory
     */
    public function showAction(Subcategory $subcategory, IndustryCategory $industry_category)
    {
        if(!$subcategory){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        if(!$industry_category->getSubcategories()->contains($subcategory)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.subcategory'));
        }

        return $subcategory;
    }

    /**
     * @Security("is_granted('subcategory_edit')")
     * @ApiDoc(
     *  section="Subcategory",
     *  description="Displays a form to edit an existing Subcategory entity.",
     *  output="ApiBundle\Entity\Subcategory",
     *  input="ApiBundle\Form\SubcategoryType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="subcategory_put")
     * @Rest\Patch("/{id}", name="subcategory_patch")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @param Request $request
     * @param Subcategory $subcategory
     * @param IndustryCategory $industry_category
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Subcategory
     */
    public function editAction(Request $request, Subcategory $subcategory, IndustryCategory $industry_category)
    {
        if(!$subcategory){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        if(!$industry_category->getSubcategories()->contains($subcategory)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.subcategory'));
        }

        $editForm = $this->createForm('ApiBundle\Form\SubcategoryType', $subcategory, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $subcategory;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('subcategory_delete')")
     * @ApiDoc(
     *  section="Subcategory",
     *  description="Deletes a Subcategory entity.",
     *  output="ApiBundle\Entity\Subcategory",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="subcategory_delete")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @Rest\View()
     * @param Subcategory $subcategory
     * @param IndustryCategory $industry_category
     * @return Response
     */
    public function deleteAction(Subcategory $subcategory, IndustryCategory $industry_category)
    {
        if(!$subcategory){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        if(!$industry_category->getSubcategories()->contains($subcategory)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.subcategory'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($subcategory);
        $em->flush();

        return new Response();
    }
}