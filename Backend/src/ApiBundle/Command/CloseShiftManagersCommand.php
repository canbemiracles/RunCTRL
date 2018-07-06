<?php

namespace ApiBundle\Command;

use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Entity\User\HistoryBranchManagerWork;
use ApiBundle\Service\BranchShift\BranchShiftManagement;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;


class CloseShiftManagersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('api:close-shift-managers')
            ->setDescription('Close the shift.')
            ->setHelp('This command allows you to close a shift for branch managers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $shiftManagement BranchShiftManagement*/
        $shiftManagement = $this->getContainer()->get('service.branch_shift.branch_shift_management');
        /** @var $response Collection HistoryBranchManagerWork*/
        $response = $shiftManagement->closeOldShiftBranchManagers();

        $output->writeln('_________________________');
        if(!empty($response)) {
            foreach ($response as $item) {
                /** @var $item HistoryBranchManagerWork*/
                $output->writeln("Forcibly closed record №{$item->getId()} (shift №{$item->getBranchShift()->getId()}, manager №{$item->getBranchManager()->getId()})");
            }
        }
        $output->writeln('_________________________');
    }
}