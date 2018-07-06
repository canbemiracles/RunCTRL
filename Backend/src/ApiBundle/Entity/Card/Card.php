<?php

namespace ApiBundle\Entity\Card;

use Doctrine\ORM\Mapping as ORM;

/**
 * Card
 *
 * @ORM\Table(name="card")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Card\CardRepository")
 */
class Card
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
     * @ORM\Column(type="string", length=80)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=16)
     */
    protected $number;

    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $month;

    /**
     * @ORM\Column(type="string", length=4)
     */
    protected $year;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $cvv;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User\AbstractUser", inversedBy="cards")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Card\HistoryCardPayment", mappedBy="card")
     */
    protected $history_payments;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active = false;


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
     * Constructor
     */
    public function __construct()
    {
        $this->history_payments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Card
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return Card
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set month
     *
     * @param string $month
     *
     * @return Card
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param string $year
     *
     * @return Card
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set cvv
     *
     * @param string $cvv
     *
     * @return Card
     */
    public function setCvv($cvv)
    {
        $this->cvv = $cvv;

        return $this;
    }

    /**
     * Get cvv
     *
     * @return string
     */
    public function getCvv()
    {
        return $this->cvv;
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User\AbstractUser $user
     *
     * @return Card
     */
    public function setUser(\ApiBundle\Entity\User\AbstractUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ApiBundle\Entity\User\AbstractUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add historyPayment
     *
     * @param \ApiBundle\Entity\Card\HistoryCardPayment $historyPayment
     *
     * @return Card
     */
    public function addHistoryPayment(\ApiBundle\Entity\Card\HistoryCardPayment $historyPayment)
    {
        $this->history_payments[] = $historyPayment;

        return $this;
    }

    /**
     * Remove historyPayment
     *
     * @param \ApiBundle\Entity\Card\HistoryCardPayment $historyPayment
     */
    public function removeHistoryPayment(\ApiBundle\Entity\Card\HistoryCardPayment $historyPayment)
    {
        $this->history_payments->removeElement($historyPayment);
    }

    /**
     * Get historyPayments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoryPayments()
    {
        return $this->history_payments;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Card
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}
