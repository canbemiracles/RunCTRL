<?php

namespace AssignmentsBundle\Repository\Assignment\Question;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Role\BranchStationOriginRole;
use AssignmentsBundle\Entity\Assignment\Question\QuestionRange;

/**
 * QuestionRangeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuestionRangeRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAssignmentsByStation(BranchStation $station)
    {
        $result = [];
        foreach($station->getAssignments() as $assignment)
        {
            if($assignment instanceof QuestionRange) {
                $result[] = $assignment;
            }
        }
        return $result;
    }
}
