<?php

namespace AssignmentsBundle\Command;

use AssignmentsBundle\Service\Manager\AssignmentManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendAssignmentsCommand extends ContainerAwareCommand{

    protected function configure()
    {
        $this->setName('api:assignments:send');
        $this->setDescription('Sending assignments to devices');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();

        /** @var $manager AssignmentManager */
        $manager = $this->getContainer()->get('service.assignments.manager');

        $tasks = $manager->handleNewAndRepeatableAssignments();

        $snoozed_tasks = $manager->handleSnoozedAssignments();

        $confirm_tasks = $manager->handleConfirmationAssignments();

       $output->writeln("[".$now->format('d.m.Y H:i:s')."] Handled {$tasks} assignments, {$snoozed_tasks} snoozed assignments and {$confirm_tasks} confirmation assignments");
    }
}