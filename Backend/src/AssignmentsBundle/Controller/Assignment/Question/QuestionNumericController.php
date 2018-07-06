<?php

namespace AssignmentsBundle\Controller\Assignment\Question;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\User\AbstractUser;
use AssignmentsBundle\Entity\Assignment\Question\QuestionNumeric;
use AssignmentsBundle\Repository\Assignment\Question\QuestionNumericRepository;
use AssignmentsBundle\Service\Question\QuestionNumericHandler;
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
 * Question numeric controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/assignments/questions/numeric")
 */
class QuestionNumericController extends AbstractController
{
    /**
     * @Security("is_granted('assignments_questions_numeric_index')")
     * @ApiDoc(
     *  section="[Assignments] Question Numeric",
     *  description="Lists all employee questions numeric.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionText",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/", name="assignments_questions_numeric_index")
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

        /** @var $repository QuestionNumericRepository */
        $repository = $em->getRepository('AssignmentsBundle:Assignment\Question\QuestionNumeric');

        return $repository->getAssignmentsByStation($station);
    }

    /**
     * @Security("is_granted('assignments_questions_numeric_new')")
     * @ApiDoc(
     *  section="[Assignments] Question Numeric",
     *  description="Creates a new question numeric for employee.",
     *  input="AssignmentsBundle\Form\Question\QuestionNumericType",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionNumeric",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\Post("/new", name="assignments_questions_numeric_new")
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

        $questionNumeric = new Questionnumeric();
        $form = $this->createForm('AssignmentsBundle\Form\Assignment\Question\QuestionNumericType', $questionNumeric);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $questionNumeric->setStation($station);
            $em = $this->getDoctrine()->getManager();
            $em->persist($questionNumeric);
            $em->flush();

            return $questionNumeric;
        }
        else{
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('assignments_questions_numeric_show')")
     * @ApiDoc(
     *  section="[Assignments] Question Numeric",
     *  description="Finds and displays a question numeric entity.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionNumeric",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/{id}", name="assignments_questions_numeric_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param QuestionNumeric $questionNumeric
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(QuestionNumeric $questionNumeric, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.assignment'));
        }

        return $questionNumeric;

    }

    /**
     * @Security("is_granted('assignments_questions_numeric_edit')")
     * @ApiDoc(
     *  section="[Assignments] Question Numeric",
     *  description="Edits an existing question numeric entity.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionNumeric",
     *  input="AssignmentsBundle\Form\Question\QuestionNumericType",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="assignments_questions_numeric_put")
     * @Rest\Patch("/{id}", name="assignments_questions_numeric_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @ParamConverter("role", options={"mapping": {"role_id" : "id"}})
     * @param Request $request
     * @param QuestionNumeric $questionNumeric
     * @param Branch $branch
     * @param BranchStation $station
     * @return QuestionNumeric|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, QuestionNumeric $questionNumeric, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.assignment'));
        }

        foreach ($questionNumeric->getRepeatMonths() as $repeatMonth)  {
            $questionNumeric->removeRepeatMonth($repeatMonth);
        }
        foreach ($questionNumeric->getRepeatMonthDays() as $repeatMonthDay)  {
            $questionNumeric->removeRepeatMonthDay($repeatMonthDay);
        }
        foreach ($questionNumeric->getRepeatWeekDays() as $repeatWeekDay)  {
            $questionNumeric->removeRepeatWeekDay($repeatWeekDay);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($questionNumeric);
        $em->flush();

        $editForm = $this->createForm('AssignmentsBundle\Form\Assignment\Question\QuestionNumericType', $questionNumeric, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $questionNumeric;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('assignments_questions_numeric_delete')")
     * @ApiDoc(
     *  section="[Assignments] Question Numeric",
     *  description="Deletes a question numeric entity.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Delete("/{id}", name="assignments_questions_numeric_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param QuestionNumeric $questionNumeric
     * @param Branch $branch
     * @param BranchStation $station
     * @return Response
     */
    public function deleteAction(Request $request, QuestionNumeric $questionNumeric, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.assignment'));
        }

        if(!$questionNumeric){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($questionNumeric);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('assignments_questions_numeric_answer')")
     * @ApiDoc(
     *  section="[Assignments] Question Numeric",
     *  description="Answer a question numeric.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\RequestParam(name="answer", allowBlank=false)
     * @Rest\View()
     * @Rest\Post("/{id}/answer", name="assignments_questions_numeric_answer")
     * @param Request $request
     * @param QuestionNumeric $questionNumeric
     * @param Branch $branch
     * @param BranchStation $station
     */
    public function answerAction(Request $request, QuestionNumeric $questionNumeric, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$questionNumeric){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        /** @var $handler QuestionNumericHandler */
        $handler = $this->get('service.assignments.question_numeric_handler');

        return $handler->handleAnswer($questionNumeric, $request->request->get('answer'));
    }

    /**
     * @Security("is_granted('assignments_questions_numeric_snooze')")
     * @ApiDoc(
     *  section="[Assignments] Question Numeric",
     *  description="snooze a question",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @Rest\Get("/{id}/snooze", name="assignments_questions_numeric_snooze")
     * @param Request $request
     * @param QuestionNumeric $questionNumeric
     * @param Branch $branch
     * @param BranchStation $station
     * @return mixed
     */
    public function snoozeAction(Request $request, QuestionNumeric $questionNumeric, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$questionNumeric) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        /** @var $handler QuestionNumericHandler */
        $handler = $this->get('service.assignments.question_numeric_handler');

        return $handler->snooze($questionNumeric);
    }
}
