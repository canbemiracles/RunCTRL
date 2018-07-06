<?php

namespace AssignmentsBundle\Entity\Assignment\Question;

use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionRange
 *
 * @ORM\Table(name="assignment_question_range")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\Question\QuestionRangeRepository")
 */
class QuestionRange extends AbstractAssignment
{
 
}
