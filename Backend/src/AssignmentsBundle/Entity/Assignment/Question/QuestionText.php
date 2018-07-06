<?php

namespace AssignmentsBundle\Entity\Assignment\Question;

use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionText
 *
 * @ORM\Table(name="assignment_question_text")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\Question\QuestionTextRepository")
 */
class QuestionText extends AbstractAssignment
{

}
