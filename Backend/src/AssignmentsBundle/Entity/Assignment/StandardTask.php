<?php

namespace AssignmentsBundle\Entity\Assignment;

use Doctrine\ORM\Mapping as ORM;

/**
 * StandardTask
 *
 * @ORM\Table(name="assignment_standard_task")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\StandardTaskRepository")
 */
class StandardTask extends AbstractAssignment
{
    /**
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * Column shows employee working on this task or not
     *
     * @ORM\Column(name="working_on_it", type="boolean")
     */
    protected $working = 0;

    /**
     * @ORM\Column(name="has_confirmation", type="boolean")
    */
    protected $has_confirmation = 0;

    /**
     * The time after which confirmation will come. Measured in seconds
     * @ORM\Column(name="time_confirmation", type="integer")
    */
    protected $time_confirmation = 600;

    /**
     * @ORM\Column(name="last_time_send_confirmation", type="datetime", nullable=true)
     */
    protected $last_time_send_confirmation;

    /**
     * Set description
     *
     * @param string $description
     *
     * @return StandardTask
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set working
     *
     * @param boolean $working
     *
     * @return AbstractAssignment
     */
    public function setWorking($working)
    {
        $this->working = $working;

        return $this;
    }

    /**
     * Get working
     *
     * @return boolean
     */
    public function getWorking()
    {
        return $this->working;
    }

    /**
     * Set has_confirmation
     *
     * @param boolean $has_confirmation
     *
     * @return AbstractAssignment
     */
    public function setHasConfirmation($has_confirmation)
    {
        $this->has_confirmation = $has_confirmation;

        return $this;
    }

    /**
     * Get has_confirmation
     *
     * @return boolean
     */
    public function getHasConfirmation()
    {
        return $this->has_confirmation;
    }

    /**
     * Set last_time_send_confirmation
     *
     * @param \DateTime $last_time_send_confirmation
     *
     * @return AbstractAssignment
     */
    public function setLastTimeSendConfirmation($last_time_send_confirmation)
    {
        $this->last_time_send_confirmation = $last_time_send_confirmation;

        return $this;
    }

    /**
     * Get last_time_send_confirmation
     *
     * @return \DateTime
     */
    public function getLastTimeSendConfirmation()
    {
        return $this->last_time_send_confirmation;
    }


    /**
     * Set timeConfirmation
     *
     * @param integer $timeConfirmation
     *
     * @return StandardTask
     */
    public function setTimeConfirmation($timeConfirmation)
    {
        $this->time_confirmation = $timeConfirmation;

        return $this;
    }

    /**
     * Get timeConfirmation
     *
     * @return integer
     */
    public function getTimeConfirmation()
    {
        return $this->time_confirmation;
    }
}
