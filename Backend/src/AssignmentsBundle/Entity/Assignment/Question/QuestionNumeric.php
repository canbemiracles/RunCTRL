<?php

namespace AssignmentsBundle\Entity\Assignment\Question;

use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionNumeric
 *
 * @ORM\Table(name="assignment_question_numeric")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\Question\QuestionNumericRepository")
 */
class QuestionNumeric extends AbstractAssignment
{

}
