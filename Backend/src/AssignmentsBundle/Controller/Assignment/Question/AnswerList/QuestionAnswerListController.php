<?php

namespace AssignmentsBundle\Controller\Assignment\Question\AnswerList;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\User\AbstractUser;
use AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionAnswerList;
use AssignmentsBundle\Repository\Assignment\Question\AnswerList\QuestionAnswerListRepository;
use AssignmentsBundle\Service\Question\QuestionAnswerListHandler;
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
 * Question answer list controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/assignments/questions/answer_lists")
 */
class QuestionAnswerListController extends AbstractController
{
    /**
     * @Security("is_granted('assignments_questions_answer_list_index')")
     * @ApiDoc(
     *  section="[Assignments] Question Answer List",
     *  description="Lists all employee questions answer list.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\AnswerList\QuestionAnswerList",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/", name="assignments_questions_answer_list_index")
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

        /** @var $repository QuestionAnswerListRepository */
        $repository = $em->getRepository('AssignmentsBundle:Assignment\Question\AnswerList\QuestionAnswerList');

        return $repository->getAssignmentsByStation($station);
    }

    /**
     *
     * @Security("is_granted('assignments_questions_answer_list_new')")
     *
     * @ApiDoc(
     *  section="[Assignments] Question Answer List",
     *  description="Creates a new question answer list for employee.",
     *  input="AssignmentsBundle\Form\Question\AnswerList\QuestionAnswerListType",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\AnswerList\QuestionAnswerList",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\Post("/new", name="assignments_questions_answer_list_new")
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

        $questionAnswerList = new Questionanswerlist();
        $form = $this->createForm('AssignmentsBundle\Form\Assignment\Question\AnswerList\QuestionAnswerListType', $questionAnswerList);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $questionAnswerList->setStation($station);
            $em = $this->getDoctrine()->getManager();
            $em->persist($questionAnswerList);
            $em->flush();

            return $questionAnswerList;
        }
        else{
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('assignments_questions_answer_list_show')")
     * @ApiDoc(
     *  section="[Assignments] Question Answer List",
     *  description="Creates a new question answer list for employee.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\AnswerList\QuestionAnswerList",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/{id}", name="assignments_questions_answer_list_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param QuestionAnswerList $questionAnswerList
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(QuestionAnswerList $questionAnswerList, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.assignment'));
        }

        return $questionAnswerList;
    }

    /**
     * @Security("is_granted('assignments_questions_answer_list_edit')")
     * @ApiDoc(
     *  section="[Assignments] Question Answer List",
     *  description="Edits an existing question answer list entity.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\AnswerList\QuestionAnswerList",
     *  input="AssignmentsBundle\Form\Question\AnswerList\QuestionAnswerListType",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="assignments_questions_answer_list_put")
     * @Rest\Patch("/{id}", name="assignments_questions_answer_list_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @param Request $request
     * @param QuestionAnswerList $questionAnswerList
     * @param Branch $branch
     * @param BranchStation $station
     * @return QuestionAnswerList|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, QuestionAnswerList $questionAnswerList, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.assignment'));
        }

        if(!$questionAnswerList) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        foreach ($questionAnswerList->getRepeatMonths() as $repeatMonth)  {
            $questionAnswerList->removeRepeatMonth($repeatMonth);
        }
        foreach ($questionAnswerList->getRepeatMonthDays() as $repeatMonthDay)  {
            $questionAnswerList->removeRepeatMonthDay($repeatMonthDay);
        }
        foreach ($questionAnswerList->getRepeatWeekDays() as $repeatWeekDay)  {
            $questionAnswerList->removeRepeatWeekDay($repeatWeekDay);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($questionAnswerList);
        $em->flush();

        $editForm = $this->createForm('AssignmentsBundle\Form\Assignment\Question\AnswerList\QuestionAnswerListType', $questionAnswerList, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $questionAnswerList;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('assignments_questions_answer_list_delete')")
     * @ApiDoc(
     *  section="[Assignments] Question Answer List",
     *  description="Deletes a question answer list entity.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Delete("/{id}", name="assignments_questions_answer_list_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param QuestionAnswerList $questionAnswerList
     * @param Branch $branch
     * @param BranchStation $station
     * @return Response
     */
    public function deleteAction(Request $request, QuestionAnswerList $questionAnswerList, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.assignment'));
        }

        if(!$questionAnswerList) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($questionAnswerList);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('assignments_questions_answer_list_answer')")
     * @ApiDoc(
     *  section="[Assignments] Question Answer List",
     *  description="Answer a question answer list.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\RequestParam(name="answer", allowBlank=false, requirements="\d+")
     * @Rest\View()
     * @Rest\Post("/{id}/answer", name="assignments_questions_answer_list_answer")
     * @param Request $request
     * @param QuestionAnswerList $questionAnswerList
     * @param Branch $branch
     * @param BranchStation $station
     */
    public function answerAction(Request $request, QuestionAnswerList $questionAnswerList, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$questionAnswerList) {
            throw new NotFoundHttpException('Record not found!');
        }

        /** @var $handler QuestionAnswerListHandler */
        $handler = $this->get('service.assignments.question_answer_list_handler');

        return $handler->handleAnswer($questionAnswerList, $request->request->get('answer'));
    }

    /**
     * @Security("is_granted('assignments_questions_answer_list_snooze')")
     * @ApiDoc(
     *  section="[Assignments] Question Answer List",
     *  description="snooze a question",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @Rest\Get("/{id}/snooze", name="assignments_questions_answer_list_snooze")
     * @param Request $request
     * @param QuestionAnswerList $questionAnswerList
     * @param Branch $branch
     * @param BranchStation $station
     * @return mixed
     */
    public function snoozeAction(Request $request, QuestionAnswerList $questionAnswerList, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$questionAnswerList){
            throw new NotFoundHttpException('Record not found!');
        }

        /** @var $handler QuestionAnswerListHandler */
        $handler = $this->get('service.assignments.question_answer_list_handler');

        return $handler->snooze($questionAnswerList);
    }

}
