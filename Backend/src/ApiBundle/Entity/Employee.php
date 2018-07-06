<?php

namespace ApiBundle\Entity;

use ApiBundle\Entity\Company;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use Doctrine\ORM\Mapping as ORM;

/**
 * Employee
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\EmployeeRepository")
 */
class Employee
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
     * @ORM\Column(type="string", length=80)
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    protected $last_name;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\FamilyStatuses", inversedBy="employees")
     * @ORM\JoinColumn(name="family_situation_id", referencedColumnName="id")
     */
    protected $family_situation;

    /**
     * @ORM\Column(type="float", precision=2)
     */
    protected $hourly_rate;

    /**
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\GeographicalArea", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="geographical_area_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $geographical_area;

    /**
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\PhoneNumber", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="phone_number_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $phone_number;

    /**
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\File\AvatarFile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $avatar;


    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\HistoryEmployeeRole", mappedBy="history_employee")
     */
    protected $history_roles;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\Role\AbstractBranchStationRole", inversedBy="employees")
     * @ORM\JoinTable(name="employees_branch_roles")
     */
    protected $roles;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\Branch", inversedBy="employees")
     * @ORM\JoinTable(name="employee_branch")
     */
    protected $branches;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\BranchShift", inversedBy="employees")
     * @ORM\JoinTable(name="employees_branch_shifts")
     */
    protected $branch_shifts;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Answer\AbstractAnswer", mappedBy="employee")
     */
    protected $assignmentAnswers;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Notification\DeviceNotificationMessage", mappedBy="employee")
     */
    protected $messages;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Company", inversedBy="employees")
     * @ORM\JoinColumn(name="company_id")
     */
    protected $company;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $social_security_number = null;

    /**
     * @ORM\Column(type="float", precision=2, nullable=true)
     */
    protected $bonus = null;

    /**
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\User\BranchManager")
     */
    protected $branch_manager;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->history_roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->branch_shifts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->branches = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Employee
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Employee
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }


    /**
     * Set hourlyRate
     *
     * @param float $hourlyRate
     *
     * @return Employee
     */
    public function setHourlyRate($hourlyRate)
    {
        $this->hourly_rate = $hourlyRate;

        return $this;
    }

    /**
     * Get hourlyRate
     *
     * @return float
     */
    public function getHourlyRate()
    {
        return $this->hourly_rate;
    }

    /**
     * Set geographicalArea
     *
     * @param \ApiBundle\Entity\GeographicalArea $geographicalArea
     *
     * @return Employee
     */
    public function setGeographicalArea(\ApiBundle\Entity\GeographicalArea $geographicalArea = null)
    {
        $this->geographical_area = $geographicalArea;

        return $this;
    }

    /**
     * Get geographicalArea
     *
     * @return \ApiBundle\Entity\GeographicalArea
     */
    public function getGeographicalArea()
    {
        return $this->geographical_area;
    }

    /**
     * Set phoneNumber
     *
     * @param \ApiBundle\Entity\PhoneNumber $phoneNumber
     *
     * @return Employee
     */
    public function setPhoneNumber(\ApiBundle\Entity\PhoneNumber $phoneNumber = null)
    {
        $this->phone_number = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return \ApiBundle\Entity\PhoneNumber
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }


    /**
     * Add role
     *
     * @param \ApiBundle\Entity\Role\AbstractBranchStationRole $role
     *
     * @return Employee
     */
    public function addRole(\ApiBundle\Entity\Role\AbstractBranchStationRole $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \ApiBundle\Entity\Role\AbstractBranchStationRole $role
     */
    public function removeRole(\ApiBundle\Entity\Role\AbstractBranchStationRole $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add branch
     *
     * @param \ApiBundle\Entity\Branch $branch
     *
     * @return Employee
     */
    public function addBranch(\ApiBundle\Entity\Branch $branch)
    {
        $this->branches[] = $branch;

        return $this;
    }

    /**
     * Remove branch
     *
     * @param \ApiBundle\Entity\Branch $branch
     */
    public function removeBranch(\ApiBundle\Entity\Branch $branch)
    {
        $this->branches->removeElement($branch);
    }

    /**
     * Get branches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * Add branchShift
     *
     * @param \ApiBundle\Entity\BranchShift $branchShift
     *
     * @return Employee
     */
    public function addBranchShift(\ApiBundle\Entity\BranchShift $branchShift)
    {
        $this->branch_shifts[] = $branchShift;

        return $this;
    }

    /**
     * Remove branchShift
     *
     * @param \ApiBundle\Entity\BranchShift $branchShift
     */
    public function removeBranchShift(\ApiBundle\Entity\BranchShift $branchShift)
    {
        $this->branch_shifts->removeElement($branchShift);
    }

    /**
     * Get branchShifts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBranchShifts()
    {
        return $this->branch_shifts;
    }

    /**
     * Set avatar
     *
     * @param \ApiBundle\Entity\File\AvatarFile $avatar
     *
     * @return Employee
     */
    public function setAvatar(\ApiBundle\Entity\File\AvatarFile $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \ApiBundle\Entity\File\AvatarFile
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add assignmentAnswer
     *
     * @param \AssignmentsBundle\Entity\Answer\AbstractAnswer $assignmentAnswer
     *
     * @return Employee
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

    /**
     * Add message
     *
     * @param \AssignmentsBundle\Entity\Notification\DeviceNotificationMessage $message
     *
     * @return Employee
     */
    public function addMessage(\AssignmentsBundle\Entity\Notification\DeviceNotificationMessage $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \AssignmentsBundle\Entity\Notification\DeviceNotificationMessage $message
     */
    public function removeMessage(\AssignmentsBundle\Entity\Notification\DeviceNotificationMessage $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set company
     *
     * @param Company|null $company
     * @return Employee
     *
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Get company id
     *
     * @return integer
     */
    public function getCompanyId()
    {
        if($this->getCompany() != null) {
            return $this->getCompany()->getId();
        }

        return null;
    }

    /**
     * Get branches info
     *
     * @return mixed
     */
    public function getBranchesInfo()
    {
        if($this->getBranches() != null) {
            $simple_data_branches = [];
            foreach($this->getBranches() as $branch) {
                /** @var $branch Branch */
                $simple_data_branches[] = [ 'id' => $branch->getId(), 'geographical_address' => $branch->getGeographicalArea()];
            }
            return $simple_data_branches;
        }
        return null;
    }

    /**
     * Get roles info
     * @param BranchStation $station
     * @return mixed
     */
    public function getRolesInfo($station = null)
    {
        if($this->getRoles() != null) {
            $simple_data_roles = [];
            foreach($this->getRoles() as $role) {
                /** @var $role AbstractBranchStationRole */
                if($role->getBranchStation() === $station || is_null($station)) {
                    $simple_data_roles[] = array('id' => $role->getId(), 'role' => $role->getRole(),
                        'id_station' => $role->getBranchStationId(), 'id_branch' => $role->getBranchStation()->getBranch()->getId(),
                        'color' => $role->getColor());
                }
            }
            return $simple_data_roles;
        }
        return null;
    }

    /**
     * Get shift info
     *
     * @return mixed
     */
    public function getBranchShiftInfo()
    {
        if($this->getBranchShifts() != null) {
            $shifts = [];
            foreach($this->getBranchShifts() as $shift) {
                /** @var $shift BranchShift */
                $shifts[] = array('id' => $shift->getId(), 'name' => $shift->getName(),
                    'id_branch' => $shift->getBranch()->getId());
            }
            return $shifts;
        }
        return null;
    }

    /**
     * Set socialSecurityNumber
     *
     * @param string $socialSecurityNumber
     *
     * @return Employee
     */
    public function setSocialSecurityNumber($socialSecurityNumber)
    {
        $this->social_security_number = $socialSecurityNumber;

        return $this;
    }

    /**
     * Get socialSecurityNumber
     *
     * @return string
     */
    public function getSocialSecurityNumber()
    {
        return $this->social_security_number;
    }

    /**
     * Set bonus
     *
     * @param float $bonus
     *
     * @return Employee
     */
    public function setBonus($bonus)
    {
        $this->bonus = $bonus;

        return $this;
    }

    /**
     * Get bonus
     *
     * @return float
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * Add historyRole
     *
     * @param \ApiBundle\Entity\HistoryEmployeeRole $historyRole
     *
     * @return Employee
     */
    public function addHistoryRole(\ApiBundle\Entity\HistoryEmployeeRole $historyRole)
    {
        $this->history_roles[] = $historyRole;

        return $this;
    }

    /**
     * Remove historyRole
     *
     * @param \ApiBundle\Entity\HistoryEmployeeRole $historyRole
     */
    public function removeHistoryRole(\ApiBundle\Entity\HistoryEmployeeRole $historyRole)
    {
        $this->history_roles->removeElement($historyRole);
    }

    /**
     * Get historyRoles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoryRoles()
    {
        return $this->history_roles;
    }

    /**
     * Set familySituation
     *
     * @param \ApiBundle\Entity\FamilyStatuses $familySituation
     *
     * @return Employee
     */
    public function setFamilySituation(\ApiBundle\Entity\FamilyStatuses $familySituation = null)
    {
        $this->family_situation = $familySituation;

        return $this;
    }

    /**
     * Get familySituation
     *
     * @return \ApiBundle\Entity\FamilyStatuses
     */
    public function getFamilySituation()
    {
        return $this->family_situation;
    }

    /**
     * Set branchManager
     *
     * @param \ApiBundle\Entity\User\BranchManager $branchManager
     *
     * @return Employee
     */
    public function setBranchManager(\ApiBundle\Entity\User\BranchManager $branchManager = null)
    {
        $this->branch_manager = $branchManager;

        return $this;
    }

    /**
     * Get branchManager
     *
     * @return \ApiBundle\Entity\User\BranchManager
     */
    public function getBranchManager()
    {
        return $this->branch_manager;
    }

    public function getBranchMangerInfo()
    {
        if(!empty($this->getBranchManager())) {
            return array(
                'branch_manager_id' => $this->getBranchManager()->getId(),
                'full_name' => "{$this->getBranchManager()->getLastName()} {$this->getBranchManager()->getFirstName()}");
        } else {
            return null;
        }
    }
}
