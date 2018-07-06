<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RecommendationsRolesData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'name' => 'Front Manager',
                        'defaulted' => 1,
                        'color' => '915F5F',
                        'recommendationsStation' => $this->getReference('recommendation_station_1'),
                    ],
                'reference' => 'recommendation_role_1'
            ],
            [
                'fields' =>
                    [
                        'name' => 'Kitchen Manager',
                        'defaulted' => 0,
                        'color' => '915F5F',
                        'recommendationsStation' => $this->getReference('recommendation_station_1'),
                    ],
                'reference' => 'recommendation_role_2'
            ],
            [
                'fields' =>
                    [
                        'name' => 'Chef',
                        'defaulted' => 0,
                        'color' => '915F5F',
                        'recommendationsStation' => $this->getReference('recommendation_station_1'),
                    ],
                'reference' => 'recommendation_role_3'
            ],
            [
                'fields' =>
                    [
                        'name' => 'WW',
                        'defaulted' => 0,
                        'color' => '915F5F',
                        'recommendationsStation' => $this->getReference('recommendation_station_1'),
                    ],
                'reference' => 'recommendation_role_4'
            ],
            [
                'fields' =>
                    [
                        'name' => 'Super Manager',
                        'defaulted' => 0,
                        'color' => '915F5F',
                        'recommendationsStation' => $this->getReference('recommendation_station_2'),
                    ],
                'reference' => 'recommendation_role_5'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], \ApiBundle\Entity\Recommendations\RecommendationsRoles::class);
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
        return 230;
    }

}
