<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\Type;

/**
 * BranchShift
 *
 * @ORM\Table(name="branch_shift")
 * @ORM\EntityListeners({"ApiBundle\EntityListener\BranchShiftListener"})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\BranchShiftRepository")
 */
class BranchShift
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Branch", inversedBy="shifts")
     * @ORM\JoinColumn(name="branch_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $branch;

    /**
     * @ORM\Column(type="string", nullable=true)
    */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\ShiftDay", inversedBy="branch_shifts")
     * @ORM\JoinColumn(name="shift_day_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $shift_day;

    /**
     * @Type("DateTime<'H:i:s'>")
     * @ORM\Column(type="time")
     */
    protected $time_open;

    /**
     * @Type("DateTime<'H:i:s'>")
     * @ORM\Column(type="time")
     */
    protected $time_close;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\Employee", mappedBy="branch_shifts", fetch="EAGER")
     */
    protected $employees;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\HistoryEmployeeRole", mappedBy="branch_shift")
     */
    protected $history_employee_roles;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Report\EndOfShiftReport", mappedBy="branch_shift")
     */
    protected $end_of_shift_reports;

    /**
     *  @ORM\Column(type="integer")
     */
    protected $limit_time_open;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Assignment\HistoryProblemTask", mappedBy="branch_shift")
     */
    protected $problemTasks;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Answer\AbstractAnswer", mappedBy="branch_shift")
     */
    protected $assignmentAnswers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->time_open = new \DateTime(date('H:i:s'));
        $this->time_close = new \DateTime(date('H:i:s'));
        $this->limit_time_open = 5;
        $this->employees = new \Doctrine\Common\Collections\ArrayCollection();
        $this->history_employee_roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->end_of_shift_reports = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set branch
     *
     * @param \ApiBundle\Entity\Branch $branch
     *
     * @return BranchShift
     */
    public function setBranch(\ApiBundle\Entity\Branch $branch = null)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch
     *
     * @return \ApiBundle\Entity\Branch
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Set shiftDay
     *
     * @param \ApiBundle\Entity\ShiftDay $shiftDay
     *
     * @return BranchShift
     */
    public function setShiftDay(\ApiBundle\Entity\ShiftDay $shiftDay = null)
    {
        $this->shift_day = $shiftDay;

        return $this;
    }

    /**
     * Get shiftDay
     *
     * @return \ApiBundle\Entity\ShiftDay
     */
    public function getShiftDay()
    {
        return $this->shift_day;
    }


    /**
     * Add employee
     *
     * @param \ApiBundle\Entity\Employee $employee
     *
     * @return BranchShift
     */
    public function addEmployee(\ApiBundle\Entity\Employee $employee)
    {
        $this->employees[] = $employee;

        return $this;
    }

    /**
     * Remove employee
     *
     * @param \ApiBundle\Entity\Employee $employee
     */
    public function removeEmployee(\ApiBundle\Entity\Employee $employee)
    {
        $this->employees->removeElement($employee);
    }

    /**
     * Get employees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * Add historyEmployeeRole
     *
     * @param \ApiBundle\Entity\HistoryEmployeeRole $historyEmployeeRole
     *
     * @return BranchShift
     */
    public function addHistoryEmployeeRole(\ApiBundle\Entity\HistoryEmployeeRole $historyEmployeeRole)
    {
        $this->history_employee_roles[] = $historyEmployeeRole;

        return $this;
    }

    /**
     * Remove historyEmployeeRole
     *
     * @param \ApiBundle\Entity\HistoryEmployeeRole $historyEmployeeRole
     */
    public function removeHistoryEmployeeRole(\ApiBundle\Entity\HistoryEmployeeRole $historyEmployeeRole)
    {
        $this->history_employee_roles->removeElement($historyEmployeeRole);
    }

    /**
     * Get historyEmployeeRoles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoryEmployeeRoles()
    {
        return $this->history_employee_roles;
    }

    /**
     * Set timeOpen
     *
     * @param \DateTime $timeOpen
     *
     * @return BranchShift
     */
    public function setTimeOpen($timeOpen)
    {
        $this->time_open = $timeOpen;

        return $this;
    }

    /**
     * Get timeOpen
     *
     * @return \DateTime
     */
    public function getTimeOpen()
    {
        return $this->time_open;
    }

    /**
     * Set timeClose
     *
     * @param \DateTime $timeClose
     *
     * @return BranchShift
     */
    public function setTimeClose($timeClose)
    {
        $this->time_close = $timeClose;

        return $this;
    }

    /**
     * Get timeClose
     *
     * @return \DateTime
     */
    public function getTimeClose()
    {
        return $this->time_close;
    }

    /**
     * Set limitTimeOpen
     *
     * @param integer $limitTimeOpen
     *
     * @return BranchShift
     */
    public function setLimitTimeOpen($limitTimeOpen)
    {
        $this->limit_time_open = $limitTimeOpen;

        return $this;
    }

    /**
     * Get limitTimeOpen
     *
     * @return integer
     */
    public function getLimitTimeOpen()
    {
        return $this->limit_time_open;
    }

    /**
     * Add endOfShiftReport
     *
     * @param \ApiBundle\Entity\Report\EndOfShiftReport $endOfShiftReport
     *
     * @return BranchShift
     */
    public function addEndOfShiftReport(\ApiBundle\Entity\Report\EndOfShiftReport $endOfShiftReport)
    {
        $this->end_of_shift_reports[] = $endOfShiftReport;

        return $this;
    }

    /**
     * Remove endOfShiftReport
     *
     * @param \ApiBundle\Entity\Report\EndOfShiftReport $endOfShiftReport
     */
    public function removeEndOfShiftReport(\ApiBundle\Entity\Report\EndOfShiftReport $endOfShiftReport)
    {
        $this->end_of_shift_reports->removeElement($endOfShiftReport);
    }

    /**
     * Get endOfShiftReports
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEndOfShiftReports()
    {
        return $this->end_of_shift_reports;
    }

    /**
     * Get company id
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->getBranch()->getCompany()->getId();
    }

    /**
     * Get branch id
     *
     * @return integer
     */
    public function getBranchId()
    {
        return $this->getBranch()->getId();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return BranchShift
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
     * Add problemTask
     *
     * @param \AssignmentsBundle\Entity\Assignment\HistoryProblemTask $problemTask
     *
     * @return BranchShift
     */
    public function addProblemTask(\AssignmentsBundle\Entity\Assignment\HistoryProblemTask $problemTask)
    {
        $this->problemTasks[] = $problemTask;

        return $this;
    }

    /**
     * Remove problemTask
     *
     * @param \AssignmentsBundle\Entity\Assignment\HistoryProblemTask $problemTask
     */
    public function removeProblemTask(\AssignmentsBundle\Entity\Assignment\HistoryProblemTask $problemTask)
    {
        $this->problemTasks->removeElement($problemTask);
    }

    /**
     * Get problemTasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProblemTasks()
    {
        return $this->problemTasks;
    }

    /**
     * Add assignmentAnswer
     *
     * @param \AssignmentsBundle\Entity\Answer\AbstractAnswer $assignmentAnswer
     *
     * @return BranchShift
     */
    public function addAssignmentAnswer(\AssignmentsBundle\Entity\Answer\AbstractAnswer $assignmentAnswer)
    {
        $this->assignmentAnswers[] = $assignmentAnswer;

        return $this;
    }

    /**
     * Remove assignmentAnswer
     *
     * @param \AssignmentsBundle\Entity\Answer\AbstractAnswer $assignmentAnswer
     */
    public function removeAssignmentAnswer(\AssignmentsBundle\Entity\Answer\AbstractAnswer $assignmentAnswer)
    {
        $this->assignmentAnswers->removeElement($assignmentAnswer);
    }

    /**
     * Get assignmentAnswers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssignmentAnswers()
    {
        return $this->assignmentAnswers;
    }

    public function getCustomTimeOpen()
    {
        if(!empty($this->getTimeOpen()) && !empty($this->getBranch()) && !empty($this->getBranch()->getCompany())
            && !empty($this->getBranch()->getCompany()->getTimeZone())) {
            $offset = intval($this->getBranch()->getCompany()->getTimeZone()->getOffset());
            /** @var $time \DateTime*/
            $time = (new \DateTime())->setTimestamp($this->getTimeOpen()->getTimestamp());
            return !empty($time) ? $time->modify("{$offset} hour")->format("1970-01-01\TH:i:sP") : null;
        } else {
            return null;
        }

    }

    public function getCustomTimeClose()
    {
        if (!empty($this->getTimeClose()) && !empty($this->getBranch()) && !empty($this->getBranch()->getCompany())
            && !empty($this->getBranch()->getCompany()->getTimeZone())) {
            $offset = intval($this->getBranch()->getCompany()->getTimeZone()->getOffset());
            /** @var $time \DateTime */
            $time = (new \DateTime())->setTimestamp($this->getTimeClose()->getTimestamp());
            return !empty($time) ? $time->modify("{$offset} hour")->format("1970-01-01\TH:i:sP") : null;
        } else {
            return null;
        }
    }
}
