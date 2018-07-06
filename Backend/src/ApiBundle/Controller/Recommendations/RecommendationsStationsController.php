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
 * RecommendationsStations controller.
 *
 * @Route("industry_categories/{category_id}/subcategories/{subcategory_id}/recommendations_stations")
 */

class RecommendationsStationsController extends AbstractController
{
    /**
     * @Security("is_granted('recommendations_stations_index')")
     * @ApiDoc(
     *  section="[Recommendations] RecommendationsStations",
     *  description="Lists all RecommendationsStations entities.",
     *  output="ApiBundle\Entity\Recommendations\RecommendationsStations",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="recommendations_stations_index")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @ParamConverter("subcategory", options={"mapping": {"subcategory_id" : "id"}})
     * @param IndustryCategory $industry_category
     * @param Subcategory $subcategory
     * @Rest\View()
     * @return array|Collection Subcategory
     */
    public function indexAction(IndustryCategory $industry_category, Subcategory $subcategory)
    {
        if(!$industry_category->getSubcategories()->contains($subcategory)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.recommendations_station'));
        }

        return $subcategory->getRecommendationsStations();
    }

    /**
     * @Security("is_granted('recommendations_stations_index')")
     * @ApiDoc(
     *  section="[Recommendations] RecommendationsStations",
     *  description="Lists all roles.",
     *  output="ApiBundle\Entity\Recommendations\RecommendationsStations",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/all_roles", name="recommendations_stations_roles")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @ParamConverter("subcategory", options={"mapping": {"subcategory_id" : "id"}})
     * @param IndustryCategory $industry_category
     * @param Subcategory $subcategory
     * @Rest\View()
     * @return mixed
     */
    public function getAllRolesAction(IndustryCategory $industry_category, Subcategory $subcategory)
    {
        if(!$industry_category->getSubcategories()->contains($subcategory)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.recommendations_station'));
        }
        $response = [];
        foreach ($subcategory->getRecommendationsStations() as $station) {
            /** @var $station RecommendationsStations*/
            foreach ($station->getRecommendationsRoles() as $role) {
                /** @var $role RecommendationsRoles*/
                $response[] = $role;
            }
        }
        return $response;
    }

    /**
     * @Security("is_granted('recommendations_stations_new')")
     * @ApiDoc(
     *  section="[Recommendations] RecommendationsStations",
     *  description="Creates a new RecommendationsStation entity.",
     *  input="ApiBundle\Form\Recommendations\RecommendationsStationsType",
     *  output="ApiBundle\Entity\Recommendations\RecommendationsStations",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="recommendations_stations_new")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @ParamConverter("subcategory", options={"mapping": {"subcategory_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param IndustryCategory $industry_category
     * @param Subcategory $subcategory
     * @return RecommendationsStations|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, IndustryCategory $industry_category, Subcategory $subcategory)
    {
        if(!$industry_category->getSubcategories()->contains($subcategory)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.new.recommendations_station'));
        }

        $form = $this->createForm('ApiBundle\Form\Recommendations\RecommendationsStationsType',  new RecommendationsStations());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $recommendations_station = $form->getData();
            $recommendations_station->setSubcategory($subcategory);
            $em->persist($recommendations_station);
            $em->flush();

            return $recommendations_station;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('recommendations_stations_show')")
     * @ApiDoc(
     *  section="[Recommendations] RecommendationsStations",
     *  description="Finds and displays a RecommendationsStations entity.",
     *  output="ApiBundle\Entity\Recommendations\RecommendationsStations",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="recommendations_stations_show")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @ParamConverter("subcategory", options={"mapping": {"subcategory_id" : "id"}})
     * @Rest\View()
     * @param Subcategory $subcategory
     * @param IndustryCategory $industry_category
     * @param RecommendationsStations $recommendations_station
     * @return RecommendationsStations
     */
    public function showAction(Subcategory $subcategory, IndustryCategory $industry_category, RecommendationsStations $recommendations_station)
    {
        if(!$recommendations_station){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        if(!$industry_category->getSubcategories()->contains($subcategory) || !$subcategory->getRecommendationsStations()->contains($recommendations_station)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.recommendations_station'));
        }

        return $recommendations_station;
    }

    /**
     * @Security("is_granted('recommendations_stations_edit')")
     * @ApiDoc(
     *  section="[Recommendations] RecommendationsStations",
     *  description="Displays a form to edit an existing RecommendationsStations entity.",
     *  output="ApiBundle\Entity\Recommendations\RecommendationsStations",
     *  input="ApiBundle\Form\Recommendations\RecommendationsStationsType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="recommendations_stations_put")
     * @Rest\Patch("/{id}", name="recommendations_stations_patch")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @ParamConverter("subcategory", options={"mapping": {"subcategory_id" : "id"}})
     * @param Request $request
     * @param Subcategory $subcategory
     * @param IndustryCategory $industry_category
     * @param RecommendationsStations $recommendations_station
     * @return \Symfony\Component\HttpFoundation\JsonResponse|RecommendationsStations
     */
    public function editAction(Request $request, Subcategory $subcategory, IndustryCategory $industry_category, RecommendationsStations $recommendations_station)
    {
        if(!$recommendations_station){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        if(!$industry_category->getSubcategories()->contains($subcategory) || !$subcategory->getRecommendationsStations()->contains($recommendations_station)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.recommendations_station'));
        }

        $editForm = $this->createForm('ApiBundle\Form\Recommendations\RecommendationsStationsType', $recommendations_station, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $recommendations_station;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('recommendations_stations_delete')")
     * @ApiDoc(
     *  section="[Recommendations] RecommendationsStations",
     *  description="Deletes a RecommendationsStations entity.",
     *  output="ApiBundle\Entity\Recommendations\RecommendationsStations",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="recommendations_stations_delete")
     * @ParamConverter("industry_category", options={"mapping": {"category_id" : "id"}})
     * @ParamConverter("subcategory", options={"mapping": {"subcategory_id" : "id"}})
     * @Rest\View()
     * @param Subcategory $subcategory
     * @param IndustryCategory $industry_category
     * @param RecommendationsStations $recommendations_station
     * @return Response
     */
    public function deleteAction(Subcategory $subcategory, IndustryCategory $industry_category, RecommendationsStations $recommendations_station)
    {
        if(!$recommendations_station){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        if(!$industry_category->getSubcategories()->contains($subcategory) || !$subcategory->getRecommendationsStations()->contains($recommendations_station)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.recommendations_station'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($recommendations_station);
        $em->flush();

        return new Response();
    }
}