<?php

namespace AssignmentsBundle\Controller\Assignment\Question;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\User\AbstractUser;
use AssignmentsBundle\Entity\Assignment\Question\QuestionYesNo;
use AssignmentsBundle\Repository\Assignment\Question\QuestionYesNoRepository;
use AssignmentsBundle\Service\Question\QuestionYesNoHandler;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zxing\NotFoundException;

/**
 * Question (Yes|NO) controller.
 * @Route("branches/{branch_id}/stations/{station_id}/assignments/questions/yes_no")
 */
class QuestionYesNoController extends AbstractController
{
    /**
     * @Security("is_granted('assignments_questions_yes_no_index')")
     * @ApiDoc(
     *  section="[Assignments] Question (YES|NO)",
     *  description="Lists all employee questions (yes|no).",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionYewNo",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/", name="assignments_questions_yes|no_index")
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

        /** @var $repository QuestionYesNoRepository */
        $repository = $em->getRepository('AssignmentsBundle:Assignment\Question\QuestionYesNo');

        return $repository->getAssignmentsByStation($station);
    }

    /**
     * @Security("is_granted('assignments_questions_yes_no_new')")
     * @ApiDoc(
     *  section="[Assignments] Question (YES|NO)",
     *  description="Creates a new question (yes|no) for employee.",
     *  input="AssignmentsBundle\Form\Question\QuestionsYesNoType",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionYewNo",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\Post("/new", name="assignments_questions_yes|no_new")
     * @Rest\View()
     * @param Request $request
     * @param Branch $branch
     * @param BranchStation $station
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse
     * @internal param Employee $employee
     */
    public function newAction(Request $request, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.assignment'));
        }

        $questionYesNo = new Questionyesno();
        $form = $this->createForm('AssignmentsBundle\Form\Assignment\Question\QuestionYesNoType', $questionYesNo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $questionYesNo->setStation($station);
            $em = $this->getDoctrine()->getManager();
            $em->persist($questionYesNo);
            $em->flush();

            return $questionYesNo;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('assignments_questions_yes_no_show')")
     * @ApiDoc(
     *  section="[Assignments] Question (YES|NO)",
     *  description="Finds and displays a question (yes|no) entity.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionYewNo",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/{id}", name="assignments_questions_yes|no_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param QuestionYesNo $questionYesNo
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(QuestionYesNo $questionYesNo, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.assignment'));
        }

        return $questionYesNo;
    }

    /**
     * @Security("is_granted('assignments_questions_yes_no_edit')")
     * @ApiDoc(
     *  section="[Assignments] Question (YES|NO)",
     *  description="Edits an existing question (yes|no) entity.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionYewNo",
     *  input="AssignmentsBundle\Form\Question\QuestionsYesNoType",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="assignments_questions_yes|no_put")
     * @Rest\Patch("/{id}", name="assignments_questions_yes|no_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @param Request $request
     * @param QuestionYesNo $questionYesNo
     * @param Branch $branch
     * @param BranchStation $station
     * @return QuestionYesNo|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, QuestionYesNo $questionYesNo, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.assignment'));
        }

        if(!$questionYesNo)
        {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        foreach ($questionYesNo->getRepeatMonths() as $repeatMonth)  {
            $questionYesNo->removeRepeatMonth($repeatMonth);
        }
        foreach ($questionYesNo->getRepeatMonthDays() as $repeatMonthDay)  {
            $questionYesNo->removeRepeatMonthDay($repeatMonthDay);
        }
        foreach ($questionYesNo->getRepeatWeekDays() as $repeatWeekDay)  {
            $questionYesNo->removeRepeatWeekDay($repeatWeekDay);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($questionYesNo);
        $em->flush();

        $editForm = $this->createForm('AssignmentsBundle\Form\Assignment\Question\QuestionYesNoType', $questionYesNo, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $questionYesNo;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('assignments_questions_yes_no_delete')")
     * @ApiDoc(
     *  section="[Assignments] Question (YES|NO)",
     *  description="Deletes a question (yes|no) entity.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Delete("/{id}", name="assignments_questions_yes|no_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param QuestionYesNo $questionYesNo
     * @param Branch $branch
     * @param BranchStation $station
     * @return Response
     */
    public function deleteAction(Request $request, QuestionYesNo $questionYesNo, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.assignment'));
        }

        if(!$questionYesNo) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($questionYesNo);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('assignments_questions_yes_no_answer')")
     * @ApiDoc(
     *  section="[Assignments] Question (YES|NO)",
     *  description="Answer a question (yes=1|no=0).",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\RequestParam(name="answer", allowBlank=false, requirements="(1|0)")
     * @Rest\View()
     * @Rest\Post("/{id}/answer", name="assignments_questions_yes|no_answer")
     * @param Request $request
     * @param QuestionYesNo $questionYesNo
     * @param Branch $branch
     * @param BranchStation $station
     */
    public function answerAction(Request $request, QuestionYesNo $questionYesNo, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$questionYesNo){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        /** @var $handler QuestionYesNoHandler */
        $handler = $this->get('service.assignments.question_yes_no_handler');

        return $handler->handleAnswer($questionYesNo, $request->request->get('answer'));
    }

    /**
     * @Security("is_granted('assignments_questions_yes_no_snooze')")
     * @ApiDoc(
     *  section="[Assignments] Question (YES|NO)",
     *  description="snooze a question",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @Rest\Get("/{id}/snooze", name="assignments_questions_yes|no_snooze")
     * @param Request $request
     * @param QuestionYesNo $questionYesNo
     * @param Branch $branch
     * @param BranchStation $station
     * @return mixed
     */
    public function snoozeAction(Request $request, QuestionYesNo $questionYesNo, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$questionYesNo) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        /** @var $handler QuestionYesNoHandler */
        $handler = $this->get('service.assignments.question_yes_no_handler');

        return $handler->snooze($questionYesNo);
    }
}
