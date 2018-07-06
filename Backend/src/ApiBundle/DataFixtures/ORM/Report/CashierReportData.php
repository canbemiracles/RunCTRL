<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\Report\CashierReport;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CashierReportData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    use FillEntityTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            [
                'fields' =>
                    [
                        'branchStation' => $this->getReference('branch_station_kitchen'),
                        'branchShift' => $this->getReference('branch_shift'),
                        'paymentMethod' => 'credits',
                        'amount' => '15000',
                        'currency' => $this->getReference('currency_usd'),
                        'archive' => '0',
                        'deleted' => '0'
                    ],
                'reference' => 'cashier_report'
            ],
            [
                'fields' =>
                    [
                        'branchStation' => $this->getReference('branch_station_kitchen'),
                        'branchShift' => $this->getReference('branch_shift'),
                        'paymentMethod' => 'cash',
                        'amount' => '3000',
                        'currency' => $this->getReference('currency_usd'),
                        'archive' => '0',
                        'deleted' => '0'
                    ],
                'reference' => 'cashier_report_2'
            ],
            [
                'fields' =>
                    [
                        'branchStation' => $this->getReference('branch_station_hall'),
                        'branchShift' => $this->getReference('branch_shift'),
                        'paymentMethod' => 'checks',
                        'amount' => '7000',
                        'currency' => $this->getReference('currency_usd'),
                        'archive' => '0',
                        'deleted' => '0'
                    ],
                'reference' => 'cashier_report_3'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], CashierReport::class);
            $manager->persist($entity);
            if(array_key_exists('reference', $itemData)){
                $this->addReference($itemData['reference'], $entity);
            }
        }
        $manager->flush();

    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 170;
    }

}
