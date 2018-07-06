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
 * FamilyStatuses controller.
 *
 * @Route("family_statuses")
 */
class FamilyStatusesController extends AbstractController
{
    /**
     * @ApiDoc(
     *  section="FamilyStatuses",
     *  description="Lists all FamilyStatuses entities.",
     *  output="ApiBundle\Entity\FamilyStatuses",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="family_statuses_index")
     * @Rest\View()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('ApiBundle:FamilyStatuses')->findAll();
    }

}
