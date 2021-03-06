<?php

namespace ApiBundle\Entity\Report;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * ProblemReport
 *
 * @ORM\Table(name="problem_report")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Report\ProblemReportRepository")
 */
class ProblemReport extends AbstractReport
{
    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\BranchStation", inversedBy="problem_reports")
     * @ORM\JoinColumn(name="branch_station_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $branch_station;

    /**
     * Set title
     *
     * @param string $title
     *
     * @return ProblemReport
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ProblemReport
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
     * Set branchStation
     *
     * @param \ApiBundle\Entity\BranchStation $branchStation
     *
     * @return ProblemReport
     */
    public function setBranchStation(\ApiBundle\Entity\BranchStation $branchStation = null)
    {
        $this->branch_station = $branchStation;

        return $this;
    }

    /**
     * Get branchStation
     *
     * @return \ApiBundle\Entity\BranchStation
     */
    public function getBranchStation()
    {
        return $this->branch_station;
    }

    public function getBranchId()
    {
        $station = $this->getBranchStation();
        if($station != null)
        {
            $branch = $station->getBranch();
            if($branch != null) {
                return $branch->getId();
            }
        }

        return null;
    }
    public function getBranchStationId()
    {
        $station = $this->getBranchStation();
        if($station != null) {
            return $station->getId();
        }
        return null;
    }
}
