<?php
namespace ApiBundle\Entity\Card;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Payment as BasePayment;

/**
 * @ORM\Table
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Card\PaymentRepository")
 */
class Payment extends BasePayment
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
    */
    protected $has_paid = 0;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User\AbstractUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set hasPaid
     *
     * @param boolean $hasPaid
     *
     * @return Payment
     */
    public function setHasPaid($hasPaid)
    {
        $this->has_paid = $hasPaid;

        return $this;
    }

    /**
     * Get hasPaid
     *
     * @return boolean
     */
    public function getHasPaid()
    {
        return $this->has_paid;
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User\AbstractUser $user
     *
     * @return Payment
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
}
