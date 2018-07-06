<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RecommendationsStationsData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'name' => 'Recommendation_1',
                        'color' => '915F5F',
                        'subcategory' => $this->getReference('subcategory_1'),
                    ],
                'reference' => 'recommendation_station_1'
            ],
            [
                'fields' =>
                    [
                        'name' => 'Recommendation_2',
                        'color' => '5F7291',
                        'subcategory' => $this->getReference('subcategory_2'),
                    ],
                'reference' => 'recommendation_station_2'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], \ApiBundle\Entity\Recommendations\RecommendationsStations::class);
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
        return 220;
    }

}
