<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\Report\CommodityReport;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CommodityReportData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'title' => 'Commodity Report',
                        'description' => 'Description',
                        'archive' => '0',
                        'deleted' => '0'
                    ],
                'reference' => 'commodity_report'
            ],
            [
                'fields' =>
                    [
                        'branchStation' => $this->getReference('branch_station_hall'),
                        'branchShift' => $this->getReference('branch_shift'),
                        'title' => 'Commodity Report 2',
                        'description' => 'Description 2',
                        'archive' => '0',
                        'deleted' => '0'
                    ],
                'reference' => 'commodity_report_2'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], CommodityReport::class);
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
        return 180;
    }

}
