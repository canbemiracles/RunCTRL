<?php

namespace AssignmentsBundle\Command;

use AssignmentsBundle\Service\Manager\AssignmentManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckProblemTasksCommand extends ContainerAwareCommand{

    protected function configure()
    {
        $this->setName('api:assignments:check');
        $this->setDescription('Checks for tasks to fail');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $manager AssignmentManager */
        $manager = $this->getContainer()->get('service.assignments.manager');

        $message = $manager->checkProblemTasks();

        $output->writeln($message);
    }
}