<?php

namespace AssignmentsBundle\Controller\Assignment;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\User\AbstractUser;
use AssignmentsBundle\Entity\Assignment\StandardTask;
use AssignmentsBundle\Service\Manager\AssignmentManager;
use AssignmentsBundle\Service\Task\StandardTaskHandler;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Assignment controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/assignments")
 */
class AssignmentController extends AbstractController
{
    /**
     * @Security("is_granted('list_assignments')")
     * @Rest\QueryParam(name="shift")
     * @Rest\QueryParam(name="future")
     * @ApiDoc(
     *  section="[Assignments] assignments",
     *  description="Lists all assignments.",
     *  output="AssignmentsBundle\Entity\Assignment\AbstractAssignment",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/roles/{id}/list_assignments", name="list_assignments")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @param AbstractBranchStationRole $role
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function listAssignmentsAction(Branch $branch, BranchStation $station, AbstractBranchStationRole $role, ParamFetcher $paramFetcher)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch || !$station->getRoles()->contains($role)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }
        $em = $this->getDoctrine()->getManager();

        /** @var $shift BranchShift */
        $shift = $em->getRepository('ApiBundle:BranchShift')->findOneBy(['id' => $paramFetcher->get('shift')]);

        if(empty($shift)) {
            throw new NotFoundHttpException($this->get('translator')->trans('shift_management.shift_not_found'));
        }

        /** @var $service AssignmentManager */
        $service = $this->get('service.assignments.manager');

        $future = !empty($paramFetcher->get('future')) && $paramFetcher->get('future');

        return $service->getListAssignmentsByRole($shift, $role, $future);
    }

    /**
     * @Security("is_granted('list_assignments')")
     * @Rest\QueryParam(name="shift")
     * @Rest\QueryParam(name="future")
     * @ApiDoc(
     *  section="[Assignments] assignments",
     *  description="Lists all assignments.",
     *  output="AssignmentsBundle\Entity\Assignment\AbstractAssignment",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/roles/{id}/list_all_assignments", name="list_all_assignments")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @param AbstractBranchStationRole $role
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function listAllAssignmentsAction(Branch $branch, BranchStation $station, AbstractBranchStationRole $role, ParamFetcher $paramFetcher)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch || !$station->getRoles()->contains($role)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }
        $em = $this->getDoctrine()->getManager();

        /** @var $shift BranchShift */
        $shift = $em->getRepository('ApiBundle:BranchShift')->findOneBy(['id' => $paramFetcher->get('shift')]);

        /** @var $service AssignmentManager */
        $service = $this->get('service.assignments.manager');

        $future = !empty($paramFetcher->get('future')) && $paramFetcher->get('future');

        return $service->getAllAssignmentsByRole($shift, $role, $future);
    }
}
