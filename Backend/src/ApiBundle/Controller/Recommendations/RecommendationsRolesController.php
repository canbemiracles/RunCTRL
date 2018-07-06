<?php

namespace ApiBundle\Controller\Recommendations;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\IndustryCategory;
use ApiBundle\Entity\Recommendations\RecommendationsRoles;
use ApiBundle\Entity\Subcategory;
use ApiBundle\Entity\Recommendations\RecommendationsStations;
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
 * RecommendationsRoles controller.
 *
 * @Route("industry_categories/{category_id}/subcategories/{subcategory_id}/recommendations_stations/{recommendations_station_id}/recommendations_roles")
 */

class RecommendationsRolesController extends AbstractController
{
    /**
     * @Security("is_granted('recommendations_roles_index')")
     * @ApiDoc(
     *  section="[Recommendations] RecommendationsRoles",
     *  description="Lists all RecommendationsRoles entities.",
     *  output="ApiBundle\Entity\Recommendations\RecommendationsRoles",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="recommendations_roles_index")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @ParamConverter("subcategory", options={"mapping": {"subcategory_id" : "id"}})
     * @ParamConverter("recommendations_station", options={"mapping": {"recommendations_station_id" : "id"}})
     * @param IndustryCategory $industry_category
     * @param Subcategory $subcategory
     * @param RecommendationsStations $recommendations_station
     * @Rest\View()
     * @return array|Collection Subcategory
     */
    public function indexAction(IndustryCategory $industry_category, Subcategory $subcategory, RecommendationsStations $recommendations_station)
    {
        if(!$industry_category->getSubcategories()->contains($subcategory) || !$subcategory->getRecommendationsStations()->contains($recommendations_station)) {

            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.recommendations_roles'));
        }

        return $recommendations_station->getRecommendationsRoles();
    }

    /**
     * @Security("is_granted('recommendations_roles_new')")
     * @ApiDoc(
     *  section="[Recommendations] RecommendationsRoles",
     *  description="Creates a new RecommendationsRoles entity.",
     *  input="ApiBundle\Form\Recommendations\RecommendationsRolesType",
     *  output="ApiBundle\Entity\Recommendations\RecommendationsRoles",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="recommendations_roles_new")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @ParamConverter("subcategory", options={"mapping": {"subcategory_id" : "id"}})
     * @ParamConverter("recommendations_station", options={"mapping": {"recommendations_station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param IndustryCategory $industry_category
     * @param Subcategory $subcategory
     * @param RecommendationsStations $recommendations_station
     * @return RecommendationsStations|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, IndustryCategory $industry_category, Subcategory $subcategory, RecommendationsStations $recommendations_station)
    {
        $form = $this->createForm('ApiBundle\Form\Recommendations\RecommendationsRolesType',  new RecommendationsRoles());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $recommendations_role = $form->getData();
            $recommendations_role->setRecommendationsStation($recommendations_station);
            $em->persist($recommendations_role);
            $em->flush();

            return $recommendations_role;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('recommendations_roles_show')")
     * @ApiDoc(
     *  section="[Recommendations] RecommendationsRoles",
     *  description="Finds and displays a RecommendationsRoles entity.",
     *  output="ApiBundle\Entity\Recommendations\RecommendationsRoles",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="recommendations_roles_show")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @ParamConverter("subcategory", options={"mapping": {"subcategory_id" : "id"}})
     * @ParamConverter("recommendations_station", options={"mapping": {"recommendations_station_id" : "id"}})
     * @Rest\View()
     * @param Subcategory $subcategory
     * @param IndustryCategory $industry_category
     * @param RecommendationsStations $recommendations_station
     * @param RecommendationsRoles $recommendations_role
     * @return RecommendationsRoles
     */
    public function showAction(Subcategory $subcategory, IndustryCategory $industry_category, RecommendationsStations $recommendations_station, RecommendationsRoles $recommendations_role)
    {
        if(!$recommendations_role){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        if(!$industry_category->getSubcategories()->contains($subcategory) || !$subcategory->getRecommendationsStations()->contains($recommendations_station) ||
            !$recommendations_station->getRecommendationsRoles()->contains($recommendations_role)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.recommendations_role'));
        }

        return $recommendations_role;
    }

    /**
     * @Security("is_granted('recommendations_roles_edit')")
     * @ApiDoc(
     *  section="[Recommendations] RecommendationsRoles",
     *  description="Displays a form to edit an existing RecommendationsRoles entity.",
     *  output="ApiBundle\Entity\Recommendations\RecommendationsRoles",
     *  input="ApiBundle\Form\Recommendations\RecommendationsRolesType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="recommendations_roles_put")
     * @Rest\Patch("/{id}", name="recommendations_roles_patch")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @ParamConverter("subcategory", options={"mapping": {"subcategory_id" : "id"}})
     * @ParamConverter("recommendations_station", options={"mapping": {"recommendations_station_id" : "id"}})
     * @param Request $request
     * @param Subcategory $subcategory
     * @param IndustryCategory $industry_category
     * @param RecommendationsStations $recommendations_station
     * @param RecommendationsRoles $recommendations_role
     * @return \Symfony\Component\HttpFoundation\JsonResponse|RecommendationsRoles
     */
    public function editAction(Request $request, Subcategory $subcategory, IndustryCategory $industry_category, RecommendationsStations $recommendations_station, RecommendationsRoles $recommendations_role)
    {
        if(!$recommendations_role){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        if(!$industry_category->getSubcategories()->contains($subcategory) || !$subcategory->getRecommendationsStations()->contains($recommendations_station) ||
            !$recommendations_station->getRecommendationsRoles()->contains($recommendations_role)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.recommendations_role'));
        }

        $editForm = $this->createForm('ApiBundle\Form\Recommendations\RecommendationsRolesType', $recommendations_role, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $recommendations_role;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('recommendations_roles_delete')")
     * @ApiDoc(
     *  section="[Recommendations] RecommendationsRoles",
     *  description="Deletes a RecommendationsRoles entity.",
     *  output="ApiBundle\Entity\Recommendations\RecommendationsRoles",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="recommendations_roles_delete")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @ParamConverter("subcategory", options={"mapping": {"subcategory_id" : "id"}})
     * @ParamConverter("recommendations_station", options={"mapping": {"recommendations_station_id" : "id"}})
     * @Rest\View()
     * @param Subcategory $subcategory
     * @param IndustryCategory $industry_category
     * @param RecommendationsStations $recommendations_station
     * @param RecommendationsRoles $recommendations_role
     * @return Response
     */
    public function deleteAction(Subcategory $subcategory, IndustryCategory $industry_category, RecommendationsStations $recommendations_station, RecommendationsRoles $recommendations_role)
    {
        if(!$recommendations_role){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        if(!$industry_category->getSubcategories()->contains($subcategory) || !$subcategory->getRecommendationsStations()->contains($recommendations_station) ||
            !$recommendations_station->getRecommendationsRoles()->contains($recommendations_role)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.recommendations_role'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($recommendations_role);
        $em->flush();

        return new Response();
    }
}