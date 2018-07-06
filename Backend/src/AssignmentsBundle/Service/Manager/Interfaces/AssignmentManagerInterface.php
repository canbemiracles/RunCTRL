<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 22.09.2017
 * Time: 14:34
 */

namespace AssignmentsBundle\Service\Manager\Interfaces;


interface AssignmentManagerInterface
{
    public function handleNewAndRepeatableAssignments();
    public function handleSnoozedAssignments();
}