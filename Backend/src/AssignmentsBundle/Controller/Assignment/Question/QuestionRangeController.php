<?php

namespace AssignmentsBundle\Controller\Assignment\Question;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\User\AbstractUser;
use AssignmentsBundle\Entity\Assignment\Question\QuestionRange;
use AssignmentsBundle\Repository\Assignment\Question\QuestionRangeRepository;
use AssignmentsBundle\Service\Question\QuestionRangeHandler;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Question range controller.
 *
 * @Route("branches/{branch_id}/stations/{station_id}/assignments/questions/range")
 */
class QuestionRangeController extends AbstractController
{

    /**
     * @Security("is_granted('assignments_questions_range_index')")
     * @ApiDoc(
     *  section="[Assignments] Question Range",
     *  description="Lists all employee questions range.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionRange",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/", name="assignments_questions_range_index")
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

        /** @var $repository QuestionRangeRepository */
        $repository = $em->getRepository('AssignmentsBundle:Assignment\Question\QuestionRange');

        return $repository->getAssignmentsByStation($station);
    }

    /**
     * @Security("is_granted('assignments_questions_range_new')")
     * @ApiDoc(
     *  section="[Assignments] Question Range",
     *  description="Creates a new question range for employee.",
     *  input="AssignmentsBundle\Form\Question\QuestionRangeType",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionRange",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\Post("/new", name="assignments_questions_range_new")
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

        $questionRange = new Questionrange();
        $form = $this->createForm('AssignmentsBundle\Form\Assignment\Question\QuestionRangeType', $questionRange);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $questionRange->setStation($station);
            $em = $this->getDoctrine()->getManager();
            $em->persist($questionRange);
            $em->flush();

            return $questionRange;
        }
        else{
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('assignments_questions_range_show')")
     * @ApiDoc(
     *  section="[Assignments] Question Range",
     *  description="Finds and displays a question range entity.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionRange",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Get("/{id}", name="assignments_questions_range_show")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param QuestionRange $questionRange
     * @param Branch $branch
     * @param BranchStation $station
     * @return object
     */
    public function showAction(QuestionRange $questionRange, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.assignment'));
        }

        return $questionRange;

    }

    /**
     * @Security("is_granted('assignments_questions_range_edit')")
     * @ApiDoc(
     *  section="[Assignments] Question Range",
     *  description="Edits an existing question range entity.",
     *  output="AssignmentsBundle\Entity\Assignment\Questions\QuestionRange",
     *  input="AssignmentsBundle\Form\Question\QuestionRangeType",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="assignments_questions_range_put")
     * @Rest\Patch("/{id}", name="assignments_questions_range_patch")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @param Request $request
     * @param QuestionRange $questionRange
     * @param Branch $branch
     * @param BranchStation $station
     * @return QuestionRange|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, QuestionRange $questionRange, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.assignment'));
        }

        if(!$questionRange)
        {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        foreach ($questionRange->getRepeatMonths() as $repeatMonth)  {
            $questionRange->removeRepeatMonth($repeatMonth);
        }
        foreach ($questionRange->getRepeatMonthDays() as $repeatMonthDay)  {
            $questionRange->removeRepeatMonthDay($repeatMonthDay);
        }
        foreach ($questionRange->getRepeatWeekDays() as $repeatWeekDay)  {
            $questionRange->removeRepeatWeekDay($repeatWeekDay);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($questionRange);
        $em->flush();

        $editForm = $this->createForm('AssignmentsBundle\Form\Assignment\Question\QuestionRangeType', $questionRange, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $questionRange;
        }
        else{
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('assignments_questions_range_delete')")
     * @ApiDoc(
     *  section="[Assignments] Question Range",
     *  description="Deletes a question range entity.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Delete("/{id}", name="assignments_questions_range_delete")
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param QuestionRange $questionRange
     * @param Branch $branch
     * @param BranchStation $station
     * @return Response
     */
    public function deleteAction(Request $request, QuestionRange $questionRange, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$branch->getCompany()->getUsers()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.assignment'));
        }

        if(!$questionRange) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($questionRange);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('assignments_questions_range_answer')")
     * @ApiDoc(
     *  section="[Assignments] Question Range",
     *  description="Answer a question range.",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\RequestParam(name="range_x", allowBlank=false)
     * @Rest\RequestParam(name="range_y", allowBlank=false)
     * @Rest\View()
     * @Rest\Post("/{id}/answer", name="assignments_questions_range_answer")
     * @param Request $request
     * @param QuestionRange $questionRange
     * @param Branch $branch
     * @param BranchStation $station
     */
    public function answerAction(Request $request, QuestionRange $questionRange, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$questionRange){
            throw new NotFoundHttpException('Record not found!');
        }

        /** @var $handler QuestionRangeHandler */
        $handler = $this->get('service.assignments.question_range_handler');

        return $handler->handleAnswer($questionRange, $request->request->get('range_x'), $request->request->get('range_y'));
    }

    /**
     * @Security("is_granted('assignments_questions_range_snooze')")
     * @ApiDoc(
     *  section="[Assignments] Question Range",
     *  description="snooze a question",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @ParamConverter("branch", options={"mapping": {"branch_id" : "id"}})
     * @ParamConverter("station", options={"mapping": {"station_id" : "id"}})
     * @Rest\View()
     * @Rest\Get("/{id}/snooze", name="assignments_questions_range_snooze")
     * @param Request $request
     * @param QuestionRange $questionRange
     * @param Branch $branch
     * @param BranchStation $station
     * @return mixed
     */
    public function snoozeAction(Request $request, QuestionRange $questionRange, Branch $branch, BranchStation $station)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->hasRole(AbstractUser::ROLE_DEVICE) && !$branch->getCompany()->getUsers()->contains($user)
            || $user->hasRole(AbstractUser::ROLE_DEVICE) && !$station->getDevices()->contains($user)
            || $station->getBranch() !== $branch) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.assignments'));
        }

        if(!$questionRange){
            throw new NotFoundHttpException('Record not found!');
        }

        /** @var $handler QuestionRangeHandler */
        $handler = $this->get('service.assignments.question_range_handler');

        return $handler->snooze($questionRange);
    }

}
