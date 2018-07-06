<?php

namespace ApiBundle\Entity\Card;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoryCardPayment
 *
 * @ORM\Table(name="history_card_payment")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Card\HistoryCardPaymentRepository")
 */
class HistoryCardPayment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Card\Card", inversedBy="history_payments")
     * @ORM\JoinColumn(name="card_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $card;

    /**
     * @ORM\Column(type="float", precision=2)
     */
    protected $payment_amount;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set paymentAmount
     *
     * @param float $paymentAmount
     *
     * @return HistoryCardPayment
     */
    public function setPaymentAmount($paymentAmount)
    {
        $this->payment_amount = $paymentAmount;

        return $this;
    }

    /**
     * Get paymentAmount
     *
     * @return float
     */
    public function getPaymentAmount()
    {
        return $this->payment_amount;
    }

    /**
     * Set card
     *
     * @param \ApiBundle\Entity\Card\Card $card
     *
     * @return HistoryCardPayment
     */
    public function setCard(\ApiBundle\Entity\Card\Card $card = null)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get card
     *
     * @return \ApiBundle\Entity\Card\Card
     */
    public function getCard()
    {
        return $this->card;
    }
}
