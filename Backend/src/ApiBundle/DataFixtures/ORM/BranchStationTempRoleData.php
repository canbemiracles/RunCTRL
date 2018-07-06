<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BranchStationTempRoleData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'role' => 'temp_chef',
                        'originRole' => $this->getReference('role_chef'),
                        'color' => '915F5F'
                    ],
                'reference' => 'temp_role_chef'
            ],
            [
                'fields' =>
                    [
                        'branchStation' => $this->getReference('branch_station_kitchen'),
                        'role' => 'temp_chef2',
                        'originRole' => $this->getReference('role_chef2'),
                        'color' => '5F7291'
                    ],
                'reference' => 'temp_role_chef2'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], \ApiBundle\Entity\Role\BranchStationTempRole::class);
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
        return 145;
    }

}
