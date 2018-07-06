<?php

namespace AssignmentsBundle\Controller\Assignment\Checklist;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\User\AbstractUser;
use AssignmentsBundle\Entity\Assignment\Checklist\Checklist;
use AssignmentsBundle\Service\Checklist\ChecklistHandler;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Checklist controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/assignments/checklists")
 */
class ChecklistController extends AbstractController
{
    /**
     * @Security("is_granted('assignments_checklist_index')")
     * @ApiDoc(
     *  section="[Assignments] Checklist",
     *  description="Lists all employee checklists.",
     *  output="AssignmentsBundle\Entity\Assignment\Checklist\Checklist",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/", name="assignments_checklist_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @return array
     */
    public function indexAction(Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('AssignmentsBundle:Assignment\Checklist\Checklist')->getAssignmentsByStation($station);
    }

    /**
     * @Security("is_granted('assignments_checklist_new')")
     * @ApiDoc(
     *  section="[Assignments] Checklist",
     *  description="Creates a new checklist for employee.",
     *  input="AssignmentsBundle\Form\Checklist\ChecklistType",
     *  output="AssignmentsBundle\Entity\Assignment\Checklist\Checklist",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\Post("/new", name="assignments_checklist_new")
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @param BranchStation $station
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.assignment'));
        }

        $checklist = new Checklist();
        $form = $this->createForm('AssignmentsBundle\Form\Assignment\Checklist\ChecklistType', $checklist);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $checklist->setStation($station);
            $em = $this->getDoctrine()->getManager();
            $em->persist($checklist);
            $em->flush();

            return $checklist;
        }
        else{
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('assignments_checklist_show')")
     * @ApiDoc(
     *  section="[Assignments] Checklist",
     *  description="Show a checklist by id for employee.",
     *  output="AssignmentsBundle\Entity\Assignment\Checklist\Checklist",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/{id}", name="assignments_checklist_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Checklist $checklist
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(Checklist $checklist, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.assignment'));
        }

        return $checklist;
    }

    /**
     * @Security("is_granted('assignments_checklist_edit')")
     * @ApiDoc(
     *  section="[Assignments] Checklist",
     *  description="Edits an existing checklist entity.",
     *  output="AssignmentsBundle\Entity\Assignment\Checklist\Checklist",
     *  input="AssignmentsBundle\Form\Checklist\ChecklistType",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="assignments_checklist_put")
     * @Rest\Patch("/{id}", name="assignments_checklist_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @param Request $request
     * @param Checklist $checklist
     * @param Branch $branch
     * @param BranchStation $station
     * @return Checklist|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, Checklist $checklist, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.assignment'));
        }

        if(!$checklist) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        foreach ($checklist->getRepeatMonths() as $repeatMonth)  {
            $checklist->removeRepeatMonth($repeatMonth);
        }
        foreach ($checklist->getRepeatMonthDays() as $repeatMonthDay)  {
            $checklist->removeRepeatMonthDay($repeatMonthDay);
        }
        foreach ($checklist->getRepeatWeekDays() as $repeatWeekDay)  {
            $checklist->removeRepeatWeekDay($repeatWeekDay);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($checklist);
        $em->flush();

        $editForm = $this->createForm('AssignmentsBundle\Form\Assignment\Checklist\ChecklistType', $checklist, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $checklist;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('assignments_checklist_delete')")
     * @ApiDoc(
     *  section="[Assignments] Checklist",
     *  description="Deletes a checklist entity.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Delete("/{id}", name="assignments_checklist_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param Checklist $checklist
     * @param Branch $branch
     * @param BranchStation $station
     * @return Response
     */
    public function deleteAction(Request $request, Checklist $checklist, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.assignment'));
        }

        if(!$checklist) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($checklist);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('assignments_checklist_answer')")
     * @ApiDoc(
     *  section="[Assignments] Checklist",
     *  description="Answer a Checklist.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\RequestParam(name="answer", allowBlank=false, requirements="\d+")
     * @Rest\View()
     * @Rest\Post("/{id}/answer", name="assignments_checklist_answer")
     * @param Request $request
     * @param Checklist $checklist
     * @param Branch $branch
     * @param BranchStation $station
     */
    public function answerAction(Request $request, Checklist $checklist, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$checklist){
            throw new NotFoundHttpException('Record not found!');
        }

        /** @var $handler ChecklistHandler */
        $handler = $this->get('service.assignments.checklist_handler');

        return $handler->handleAnswer($checklist, $request->request->get('answer'));
    }

    /**
     * @Security("is_granted('assignments_checklist_snooze')")
     * @ApiDoc(
     *  section="[Assignments] Checklist",
     *  description="snooze a checklist",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @Rest\Get("/{id}/snooze", name="assignments_checklist_snooze")
     * @param Request $request
     * @param Checklist $checklist
     * @param Branch $branch
     * @param BranchStation $station
     * @return mixed
     */
    public function snoozeAction(Request $request, Checklist $checklist, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$checklist) {
            throw new NotFoundHttpException('Record not found!');
        }

        /** @var $handler ChecklistHandler */
        $handler = $this->get('service.assignments.checklist_handler');

        return $handler->snooze($checklist);
    }
}
