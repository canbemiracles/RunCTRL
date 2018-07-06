<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 30.09.2017
 * Time: 16:40
 */

namespace AssignmentsBundle\Service\Checklist\Interfaces;


use AssignmentsBundle\Entity\Assignment\Checklist\Checklist;

interface ChecklistHandlerInterface
{
    public function handleAnswer(Checklist $checklist, $answer);
    public function snooze(Checklist $checklist);
}