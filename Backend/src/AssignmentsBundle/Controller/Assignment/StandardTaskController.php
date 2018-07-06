<?php

namespace AssignmentsBundle\Controller\Assignment;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\User\AbstractUser;
use AssignmentsBundle\Entity\Assignment\StandardTask;
use AssignmentsBundle\Service\Task\StandardTaskHandler;
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
 * Standardtask controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/assignments/tasks")
 */
class StandardTaskController extends AbstractController
{
    /**
     * @Security("is_granted('assignments_standard_task_index')")
     * @ApiDoc(
     *  section="[Assignments] Standard Task",
     *  description="Lists all standard tasks.",
     *  output="AssignmentsBundle\Entity\Assignment\StandardTask",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/", name="assignments_standard_task_index")
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

        return $em->getRepository('AssignmentsBundle:Assignment\StandardTask')->getAssignmentsByStation($station);
    }


    /**
     * @Security("is_granted('assignments_standard_task_new')")
     * @ApiDoc(
     *  section="[Assignments] Standard Task",
     *  description="Creates a new standard task for a role.",
     *  input="AssignmentsBundle\Form\Assignment\StandardTaskType",
     *  output="AssignmentsBundle\Entity\Assignment\StandardTask",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\Post("/new", name="assignments_standard_task_new")
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

        $standardTask = new Standardtask();
        $form = $this->createForm('AssignmentsBundle\Form\Assignment\StandardTaskType', $standardTask);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $standardTask->setStation($station);
            $em = $this->getDoctrine()->getManager();
            $em->persist($standardTask);
            $em->flush();

            return $standardTask;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('assignments_standard_task_show')")
     * @ApiDoc(
     *  section="[Assignments] Standard Task",
     *  description="Finds and displays a standard task entity.",
     *  output="AssignmentsBundle\Entity\Assignment\StandardTask",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/{id}", name="assignments_standard_task_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param StandardTask $standardTask
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(StandardTask $standardTask, Branch $branch, BranchStation $station)
    {

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.assignment'));
        }

        return $standardTask;
    }

    /**
     * @Security("is_granted('assignments_standard_task_edit')")
     * @ApiDoc(
     *  section="[Assignments] Standard Task",
     *  description="Edits an existing standard task entity.",
     *  output="AssignmentsBundle\Entity\Assignment\StandardTask",
     *  input="AssignmentsBundle\Form\Assignment\StandardTaskType",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="assignments_standard_task_put")
     * @Rest\Patch("/{id}", name="assignments_standard_task_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @param Request $request
     * @param StandardTask $standardTask
     * @param Branch $branch
     * @param BranchStation $station
     * @return StandardTask|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, StandardTask $standardTask, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.assignment'));
        }

        if(!$standardTask)
        {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        foreach ($standardTask->getRepeatMonths() as $repeatMonth)  {
            $standardTask->removeRepeatMonth($repeatMonth);
        }
        foreach ($standardTask->getRepeatMonthDays() as $repeatMonthDay)  {
            $standardTask->removeRepeatMonthDay($repeatMonthDay);
        }
        foreach ($standardTask->getRepeatWeekDays() as $repeatWeekDay)  {
            $standardTask->removeRepeatWeekDay($repeatWeekDay);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($standardTask);
        $em->flush();

        $editForm = $this->createForm('AssignmentsBundle\Form\Assignment\StandardTaskType', $standardTask, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $standardTask;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('assignments_standard_task_delete')")
     * @ApiDoc(
     *  section="[Assignments] Standard Task",
     *  description="Deletes a standard task entity.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Delete("/{id}", name="assignments_standard_task_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param StandardTask $standardTask
     * @param Branch $branch
     * @param BranchStation $station
     * @return Response
     */
    public function deleteAction(Request $request, StandardTask $standardTask, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.assignment'));
        }

        if(!$standardTask){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($standardTask);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('assignments_standard_task_working_on_it')")
     * @ApiDoc(
     *  section="[Assignments] Standard Task",
     *  description="Employee working on this task.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/{id}/working_on_it", name="assignments_standard_task_working_on_it")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param StandardTask $standardTask
     * @param Branch $branch
     * @param BranchStation $station
     * @return Response
     */
    public function workingOnItAction(Request $request, StandardTask $standardTask, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.assignment'));
        }

        if(!$standardTask) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        /** @var $handler StandardTaskHandler */
        $handler = $this->get('service.assignments.standard_task_handler');

        return $handler->workingOnIt($standardTask);
    }

    /**
     * @Security("is_granted('assignments_standard_task_snooze')")
     * @ApiDoc(
     *  section="[Assignments] Standard Task",
     *  description="snooze a standard task",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @Rest\Get("/{id}/snooze", name="assignments_standard_task_snooze")
     * @param Request $request
     * @param StandardTask $standardTask
     * @param Branch $branch
     * @param BranchStation $station
     * @return Response
     */
    public function snoozeAction(Request $request, StandardTask $standardTask, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$standardTask){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        /** @var $handler StandardTaskHandler */
        $handler = $this->get('service.assignments.standard_task_handler');

        return $handler->snooze($standardTask);
    }

    /**
     * @Security("is_granted('assignments_standard_task_answer')")
     * @ApiDoc(
     *  section="[Assignments] Standard Task",
     *  description="Answer a standard question.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\RequestParam(name="answer", allowBlank=false, requirements="(1|0)")
     * @Rest\View()
     * @Rest\Post("/{id}/answer", name="assignments_standard_task_answer")
     * @param Request $request
     * @param StandardTask $standardTask
     * @param Branch $branch
     * @param BranchStation $station
     * @return Response
     */
    public function answerAction(Request $request, StandardTask $standardTask, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$standardTask){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        /** @var $handler StandardTaskHandler */
        $handler = $this->get('service.assignments.standard_task_handler');

        return $handler->handleAnswer($standardTask, $request->request->get('answer'));
    }
}
