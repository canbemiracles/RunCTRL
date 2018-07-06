<?php

namespace AssignmentsBundle\Entity\Assignment\Question;

use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionYesNo
 *
 * @ORM\Table(name="assignment_question_yes_no")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\Question\QuestionYesNoRepository")
 */
class QuestionYesNo extends AbstractAssignment
{

}
