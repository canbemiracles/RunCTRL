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


class CloseShiftCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('api:close-shift')
            ->setDescription('Close the shift.')
            ->setHelp('This command allows you to close a shift');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $shiftManagement BranchShiftManagement*/
        $shiftManagement = $this->getContainer()->get('service.branch_shift.branch_shift_management');
        $response = $shiftManagement->closeShift();

        $output->writeln('_________________________');
        $output->writeln('Shifts successfully close!');
        if(!empty($response['close_shifts'])) {
            /** @var $shifts Collection BranchShift*/
            $shifts = $response['close_shifts'];
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