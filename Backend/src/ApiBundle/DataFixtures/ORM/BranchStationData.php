<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\BranchStation;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BranchStationData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'branch' => $this->getReference('branch_mc_donalds'),
                        'name' => 'Kitchen',
                        'deviceCode' => 'test_code1',
                        'pin' => '1234',
                        'pinExpire' => new \DateTime('+ 1 year')
                    ],
                'reference' => 'branch_station_kitchen'
            ],
            [
                'fields' =>
                    [
                        'branch' => $this->getReference('branch_mc_donalds'),
                        'name' => 'Hall',
                        'deviceCode' => 'test_code2',
                        'pin' => '5678',
                        'pinExpire' => new \DateTime('+ 1 year')
                    ],
                'reference' => 'branch_station_hall'
            ],
            [
                'fields' =>
                    [
                        'branch' => $this->getReference('branch_mc_donalds'),
                        'name' => 'Management',
                        'deviceCode' => 'test_code3',
                        'pin' => '7787',
                        'pinExpire' => new \DateTime('+ 1 year')
                    ],
                'reference' => 'branch_station_management'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], BranchStation::class);
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
        return 120;
    }

}
