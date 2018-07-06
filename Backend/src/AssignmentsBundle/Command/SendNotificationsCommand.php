<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 03.10.2017
 * Time: 19:38
 */

namespace AssignmentsBundle\Command;


use AssignmentsBundle\Service\Manager\DeviceNotificationManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendNotificationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('api:notifications:send');
        $this->setDescription('Sending notifications to devices');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();

        /** @var $manager DeviceNotificationManager */
        $manager = $this->getContainer()->get('service.device.notifications.manager');

        $tasks = $manager->handleDeviceNotifications();


        $output->writeln("[".$now->format('d.m.Y H:i:s')."] Handled {$tasks} notifications");
    }
}