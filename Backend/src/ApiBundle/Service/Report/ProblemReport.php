<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 30.09.2017
 * Time: 18:41
 */

namespace ApiBundle\Service\Report;


use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\BranchStation;
use Doctrine\ORM\EntityManager;

class ProblemReport
{
    /** @var EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param BranchStation $station
     * @param string $title
     * @param string $description
     * @return \ApiBundle\Entity\Report\ProblemReport
     */
    public function generateProblemReport(BranchStation $station, BranchShift $shift, string $title, string $description)
    {
        $report = new \ApiBundle\Entity\Report\ProblemReport();
        $report->setBranchStation($station);
        $report->setBranchShift($shift);
        $report->setTitle($title);
        $report->setDescription($description);

        $this->entityManager->persist($report);
        $this->entityManager->flush($report);
        return $report;
    }
}