<?php

namespace ApiBundle\Command;

use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Service\BranchShift\BranchShiftManagement;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;


class StartShiftCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('api:start-shift')
            ->setDescription('Start a new shift.')
            ->setHelp('This command allows you to create a shift');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $shiftManagement BranchShiftManagement*/
        $shiftManagement = $this->getContainer()->get('service.branch_shift.branch_shift_management');
        $response = $shiftManagement->openShift();

        $output->writeln('_________________________');
        $output->writeln('Shifts successfully start!');
        if(!empty($response['open_shifts'])) {
            /** @var $shifts Collection BranchShift*/
            $shifts = $response['open_shifts'];
            foreach ($shifts as $shift) {
                /** @var $shift BranchShift*/
                $output->writeln("Start shift №{$shift->getId()}");
            }
        }

        if(!empty($response['close_shift_employee'])) {
            /** @var $histories Collection HistoryEmployeeRole */
            $histories = $response['close_shift_employee'];
            foreach ($histories as $item) {
                /** @var $item HistoryEmployeeRole*/
                $output->writeln("Forcibly closed record №{$item->getId()} (shift №{$item->getBranchShift()->getId()})");
            }
        }
        $output->writeln('_________________________');
    }
}