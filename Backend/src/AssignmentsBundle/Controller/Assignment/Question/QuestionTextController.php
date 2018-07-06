<?php

namespace AssignmentsBundle\Controller\Assignment\Question;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\User\AbstractUser;
use AssignmentsBundle\Entity\Assignment\Question\QuestionText;
use AssignmentsBundle\Repository\Assignment\Question\QuestionRangeRepository;
use AssignmentsBundle\Repository\Assignment\Question\QuestionTextRepository;
use AssignmentsBundle\Service\Question\QuestionTextHandler;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Question text controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/assignments/questions/text")
 */
class QuestionTextController extends AbstractController
{
    /**
     * @Security("is_granted('assignments_questions_text_index')")
     * @ApiDoc(
     *  section="[Assignments] Question Text",
     *  description="Lists all employee questions text.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionText",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/", name="assignments_questions_text_index")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Branch $branch
     * @param BranchStation $station
     * @return array
     * @internal param Employee $employee
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

        /** @var $repository QuestionTextRepository */
        $repository = $em->getRepository('AssignmentsBundle:Assignment\Question\QuestionText');

        return $repository->getAssignmentsByStation($station);
    }

    /**
     * @Security("is_granted('assignments_questions_text_new')")
     * @ApiDoc(
     *  section="[Assignments] Question Text",
     *  description="Creates a new question text for employee.",
     *  input="AssignmentsBundle\Form\Question\QuestionTextType",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionText",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\Post("/new", name="assignments_questions_text_new")
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

        $questionText = new Questiontext();
        $form = $this->createForm('AssignmentsBundle\Form\Assignment\Question\QuestionTextType', $questionText);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $questionText->setStation($station);
            $em = $this->getDoctrine()->getManager();
            $em->persist($questionText);
            $em->flush();

            return $questionText;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('assignments_questions_text_show')")
     * @ApiDoc(
     *  section="[Assignments] Question Text",
     *  description="Finds and displays a question text entity.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionText",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/{id}", name="assignments_questions_text_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param QuestionText $questionText
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(QuestionText $questionText, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.assignment'));
        }

        return $questionText;

    }

    /**
     * @Security("is_granted('assignments_questions_text_edit')")
     * @ApiDoc(
     *  section="[Assignments] Question Text",
     *  description="Edits an existing question text entity.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionText",
     *  input="AssignmentsBundle\Form\Question\QuestionTextType",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="assignments_questions_text_put")
     * @Rest\Patch("/{id}", name="assignments_questions_text_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @param Request $request
     * @param QuestionText $questionText
     * @param Branch $branch
     * @param BranchStation $station
     * @return QuestionText|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, QuestionText $questionText, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.assignment'));
        }

        if(!$questionText) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        foreach ($questionText->getRepeatMonths() as $repeatMonth)  {
            $questionText->removeRepeatMonth($repeatMonth);
        }
        foreach ($questionText->getRepeatMonthDays() as $repeatMonthDay)  {
            $questionText->removeRepeatMonthDay($repeatMonthDay);
        }
        foreach ($questionText->getRepeatWeekDays() as $repeatWeekDay)  {
            $questionText->removeRepeatWeekDay($repeatWeekDay);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($questionText);
        $em->flush();

        $editForm = $this->createForm('AssignmentsBundle\Form\Assignment\Question\QuestionTextType', $questionText, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $questionText;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('assignments_questions_text_delete')")
     * @ApiDoc(
     *  section="[Assignments] Question Text",
     *  description="Deletes a question text entity.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Delete("/{id}", name="assignments_questions_text_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param QuestionText $questionText
     * @param Branch $branch
     * @param BranchStation $station
     * @return Response
     */
    public function deleteAction(Request $request, QuestionText $questionText, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.assignment'));
        }

        if(!$questionText) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($questionText);
        $em->flush();

        return new Response();

    }

    /**
     * @Security("is_granted('assignments_questions_text_answer')")
     * @ApiDoc(
     *  section="[Assignments] Question Text",
     *  description="Answer a question text.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\RequestParam(name="answer", allowBlank=false)
     * @Rest\View()
     * @Rest\Post("/{id}/answer", name="assignments_questions_text_answer")
     * @param Request $request
     * @param QuestionText $questionText
     * @param Branch $branch
     * @param BranchStation $station
     * @return mixed
     */
    public function answerAction(Request $request, QuestionText $questionText, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$questionText) {
            throw new NotFoundHttpException('Record not found!');
        }

        /** @var $handler QuestionTextHandler */
        $handler = $this->get('service.assignments.question_text_handler');

        return $handler->handleAnswer($questionText, $request->request->get('answer'));
    }

    /**
     * @Security("is_granted('assignments_questions_text_snooze')")
     * @ApiDoc(
     *  section="[Assignments] Question Text",
     *  description="snooze a question",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @Rest\Get("/{id}/snooze", name="assignments_questions_text_snooze")
     * @param Request $request
     * @param QuestionText $questionText
     * @param Branch $branch
     * @param BranchStation $station
     * @return mixed
     */
    public function snoozeAction(Request $request, QuestionText $questionText, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$questionText) {
            throw new NotFoundHttpException('Record not found!');
        }

        /** @var $handler QuestionTextHandler */
        $handler = $this->get('service.assignments.question_text_handler');

        return $handler->snooze($questionText);
    }
}
