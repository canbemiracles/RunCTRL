<?php

namespace ApiBundle\Entity\Report;

use ApiBundle\Entity\Traits\CreatedUpdatedTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * AbstractReport
 *
 * @ORM\Table(name="report")
 * @ORM\EntityListeners({"ApiBundle\EntityListener\AbstractReportListener"})
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"Cashier" = "CashierReport", "Commodity" = "CommodityReport", "EndOfShift" = "EndOfShiftReport", "Problem" = "ProblemReport", "EndOfDay" = "EndOfDayReport"})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Report\AbstractReportRepository")
 */
abstract class AbstractReport
{
    use CreatedUpdatedTrait;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $archive = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $deleted = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $date_deleted = null;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\BranchShift", inversedBy="end_of_shift_reports")
     * @ORM\JoinColumn(name="branch_shift_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $branch_shift;

    /**
     * @ORM\Column(name="is_read", type="boolean")
     */
    protected $read = 0;

    /**
     * @ORM\OneToMany(targetEntity="WebSocketsBundle\Entity\Notification\ReportNotification", mappedBy="report")
     */
    protected $notifications;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created = new \DateTime(date('Y-m-d H:i:s'));
        $this->updated = new \DateTime(date('Y-m-d H:i:s'));
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
     * Set archive
     *
     * @param boolean $archive
     *
     * @return AbstractReport
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive
     *
     * @return boolean
     */
    public function getArchive()
    {
        return $this->archive;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return AbstractReport
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set dateDeleted
     *
     * @param \DateTime $dateDeleted
     *
     * @return AbstractReport
     */
    public function setDateDeleted($dateDeleted)
    {
        $this->date_deleted = $dateDeleted;

        return $this;
    }

    /**
     * Get dateDeleted
     *
     * @return \DateTime
     */
    public function getDateDeleted()
    {
        return $this->date_deleted;
    }

    /**
     * Set branchShift
     *
     * @param \ApiBundle\Entity\BranchShift $branchShift
     *
     * @return AbstractReport
     */
    public function setBranchShift(\ApiBundle\Entity\BranchShift $branchShift = null)
    {
        $this->branch_shift = $branchShift;

        return $this;
    }

    /**
     * Get branchShift
     *
     * @return \ApiBundle\Entity\BranchShift
     */
    public function getBranchShift()
    {
        return $this->branch_shift;
    }

    /**
     * Set read
     *
     * @param boolean $read
     *
     * @return AbstractReport
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get read
     *
     * @return boolean
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Add notification
     *
     * @param \WebSocketsBundle\Entity\Notification\ReportNotification $notification
     *
     * @return AbstractReport
     */
    public function addNotification(\WebSocketsBundle\Entity\Notification\ReportNotification $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param \WebSocketsBundle\Entity\Notification\ReportNotification $notification
     */
    public function removeNotification(\WebSocketsBundle\Entity\Notification\ReportNotification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    public function getBranchManager()
    {
        $branchShift = $this->getBranchShift();
        if($branchShift != null) {
            $branch = $branchShift->getBranch();
            if($branch != null ) {
                return $branch->getBranchManager();
            }
        }

        return null;
    }

    public function getBranchGeoArea()
    {
        $branchShift = $this->getBranchShift();
        if($branchShift != null) {
            $branch = $branchShift->getBranch();
            if($branch != null ) {
                return $branch->getGeographicalArea();
            }
        }

        return null;
    }

    public function getCreatedTimeZone()
    {
        if(!empty($this->getCreated()) && !empty($this->getBranchShift()) && !empty($this->getBranchShift()->getBranch())
            && !empty($this->getBranchShift()->getBranch()->getCompany())
            && !empty($this->getBranchShift()->getBranch()->getCompany()->getTimeZone())) {
            $offset = intval($this->getBranchShift()->getBranch()->getCompany()->getTimeZone()->getOffset());
            /** @var $snooze \DateTime*/
            $time = (new \DateTime())->setTimestamp($this->getCreated()->getTimestamp());
            return !empty($time) ? $time->modify("{$offset} hour") : null;
        } else {
            return null;
        }
    }
}
