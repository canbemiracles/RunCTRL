<?php

namespace ApiBundle\Controller\Card;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Card\Card;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Repository\Card\CardRepository;
use Doctrine\Common\Collections\Collection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Card controller.
 *
 * @Route("cards")
 */
class CardController extends AbstractController
{

    /**
     * @Security("is_granted('card_show')")
     * @ApiDoc(
     *  section="[Card] Card",
     *  description="Finds and displays a Card entity.",
     *  output="ApiBundle\Entity\Card\Card",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/", name="card_index")
     * @Rest\View()
     * @return Collection Card
     */
    public function getCardByUserAction()
    {
        /** @var $user AbstractUser*/
        $user = $this->getUser();

        return $user->getCards();
    }

    /**
     * @Security("is_granted('card_new')")
     * @ApiDoc(
     *  section="[Card] Card",
     *  description="Creates a new Card entity.",
     *  input="ApiBundle\Form\Card\CardType",
     *  output="ApiBundle\Entity\Card\Card",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/new", name="card_new")
     * @Rest\View()
     * @param Request $request
     * @return Card|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $card = new Card();

        $form = $this->createForm('ApiBundle\Form\Card\CardType', $card);
        $form->handleRequest($request);

        /** @var $user AbstractUser */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var $repository CardRepository*/
            $repository = $em->getRepository(Card::class);
            /** @var $exist Card*/
            $exist = $repository->findOneBy(['number' => $card->getNumber()]);
            if(!empty($exist) && $user->getCompany() === $exist->getUser()->getCompany()) {
                throw new BadRequestHttpException($this->get('translator')->trans('card_number_error'));
            }

            $em->persist($card);
            $em->flush();

            return $card;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }

    /**
     * @Security("is_granted('card_show')")
     * @ApiDoc(
     *  section="[Card] Card",
     *  description="Finds and displays a Card entity.",
     *  output="ApiBundle\Entity\Card\Card",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="card_show")
     * @Rest\View()
     * @param Card $card
     * @return Card
     */
    public function showAction(Card $card)
    {
        if(!$card){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && $card->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.card'));
        }

        return $card;
    }

    /**
     * @Security("is_granted('card_edit')")
     * @ApiDoc(
     *  section="[Card] Card",
     *  description="Displays a form to edit an existing Card entity.",
     *  output="ApiBundle\Entity\Card\Card",
     *  input="ApiBundle\Form\Card\CardType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="card_put")
     * @Rest\Patch("/{id}", name="card_patch")
     * @param Request $request
     * @param Card $card
     * @return Card|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAction(Request $request, Card $card)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && $card->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.card'));
        }

        $editForm = $this->createForm('ApiBundle\Form\Card\CardType', $card, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            /** @var $otherCards Collection Card*/
            $otherCards = $card->getUser()->getCards();
            foreach ($otherCards as $item) {
                /** @var $item Card*/
                if($item !== $card) {
                    $item->setActive(false);
                    $this->getDoctrine()->getManager()->persist($item);
                }
            }
            $this->getDoctrine()->getManager()->flush();
            return $card;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('card_delete')")
     * @ApiDoc(
     *  section="[Card] Card",
     *  description="Deletes a card entity.",
     *  output="ApiBundle\Entity\Card\Card",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="card_delete")
     * @Rest\View()
     * @param Card $card
     * @return Response
     */
    public function deleteAction(Card $card)
    {
        if(!$card){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && $card->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.card'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($card);
        $em->flush();

        return new Response();
    }

}
