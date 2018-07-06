<?php

namespace ApiBundle\Controller\Card;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Card\Card;
use ApiBundle\Entity\Card\HistoryCardPayment;
use ApiBundle\Entity\Card\Payment;
use ApiBundle\Entity\User\AbstractUser;
use Doctrine\Common\Collections\Collection;
use Knp\Component\Pager\Paginator;
use Payum\Core\Payum;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Security\TokenInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * HistoryCardPayment controller.
 *
 * @Route("history_card_payments")
 */
class HistoryCardPaymentController extends AbstractController
{
    /**
     * @Security("is_granted('history_card_payment_show')")
     * @ApiDoc(
     *  section="[Card] HistoryCardPayment",
     *  description="displays a HistoryCardPayment by card.",
     *  output="ApiBundle\Entity\Card\HistoryCardPayment",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/show_by_card/{card_id}", name="history_card_payment_show_by_card")
     * @ParamConverter("card", options={"mapping": {"card_id" : "id"}})
     * @Rest\View()
     * @param Card $card
     * @param Request $request
     * @return mixed
     */
    public function showByCardAction(Card $card, Request $request)
    {
        if(!$card) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        /** @var $user AbstractUser*/
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$user->getCards()->contains($card)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.card'));
        }

        $page = 1;

        if($request->query->get('page') != null) {
            $page = $request->query->get('page');
            $request->query->remove('page');
        }

        $pager = $this->get('knp_paginator');

        return $pager->paginate($card->getHistoryPayments()->toArray(), $page, $this->getParameter('page_range')['history_card_payment']);
    }

    /**
     * @Security("is_granted('history_card_payment_new')")
     * @ApiDoc(
     *  section="[Card] HistoryCardPayment",
     *  description="Creates a new HistoryCardPayment entity.",
     *  input="ApiBundle\Form\Card\HistoryCardPayment",
     *  output="ApiBundle\Entity\Card\HistoryCardPayment",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="history_card_payment_new")
     * @Rest\View()
     * @param Request $request
     * @return HistoryCardPayment|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $history_card_payment = new HistoryCardPayment();

        $form = $this->createForm('ApiBundle\Form\Card\HistoryCardPaymentType', $history_card_payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($history_card_payment);
            $em->flush();

            return $history_card_payment;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('history_card_payment_show')")
     * @ApiDoc(
     *  section="[Card] HistoryCardPayment",
     *  description="Finds and displays a HistoryCardPayment entity.",
     *  output="ApiBundle\Entity\Card\HistoryCardPayment",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="history_card_payment_show")
     * @Rest\View()
     * @param HistoryCardPayment $history_card_payment
     * @return HistoryCardPayment
     */
    public function showAction(HistoryCardPayment $history_card_payment)
    {
        if(!$history_card_payment) {
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && $history_card_payment->getCard()->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.card'));
        }

        return $history_card_payment;
    }

    /**
     * @Security("is_granted('history_card_payment_edit')")
     * @ApiDoc(
     *  section="[Card] HistoryCardPayment",
     *  description="Displays a form to edit an existing HistoryCardPayment entity.",
     *  output="ApiBundle\Entity\Card\HistoryCardPayment",
     *  input="ApiBundle\Form\Card\HistoryCardPaymentType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="history_card_payment_put")
     * @Rest\Patch("/{id}", name="history_card_payment_patch")
     * @param Request $request
     * @param HistoryCardPayment $history_card_payment
     * @return HistoryCardPayment|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, HistoryCardPayment $history_card_payment)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && $history_card_payment->getCard()->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.card'));
        }

        $editForm = $this->createForm('ApiBundle\Form\Card\HistoryCardPaymentType', $history_card_payment, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $history_card_payment;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('history_card_payment_delete')")
     * @ApiDoc(
     *  section="[Card] HistoryCardPayment",
     *  description="Deletes a HistoryCardPayment entity.",
     *  output="ApiBundle\Entity\Card\HistoryCardPayment",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="history_card_payment_delete")
     * @Rest\View()
     * @param HistoryCardPayment $history_card_payment
     * @return Response
     */
    public function deleteAction(HistoryCardPayment $history_card_payment)
    {
        if(!$history_card_payment){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && $history_card_payment->getCard()->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.card'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($history_card_payment);
        $em->flush();

        return new Response();
    }

}
