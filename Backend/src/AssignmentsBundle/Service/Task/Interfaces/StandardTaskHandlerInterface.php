<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 02.10.2017
 * Time: 15:23
 */

namespace AssignmentsBundle\Service\Task\Interfaces;

use AssignmentsBundle\Entity\Assignment\StandardTask;

interface StandardTaskHandlerInterface
{
    public function workingOnIt(StandardTask $standardTask);
    public function handleAnswer(StandardTask $standardTask, bool $answer);
    public function snooze(StandardTask $standardTask);
}