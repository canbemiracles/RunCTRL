<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\GeographicalArea;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GeographicalAreaData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'country' => $this->getReference("country_vietnam"),
                        'region' => 'vietnam_region',
                        'city' => 'Hanoi',
                        'streetAddress' => 'baker street',
                        'zip' => '456123'

                    ],
                'reference' => 'geo_area_vietnam'
            ],
            [
                'fields' =>
                    [
                        'country' => $this->getReference("country_belarus"),
                        'region' => 'belarus_region',
                        'city' => 'Minsk',
                        'streetAddress' => 'baker street',
                        'zip' => '456123'

                    ],
                'reference' => 'geo_area_belarus'
            ],
            [
                'fields' =>
                    [
                        'country' => $this->getReference("country_spain"),
                        'region' => 'spain_region',
                        'city' => 'Barcelona',
                        'streetAddress' => 'baker street',
                        'zip' => '456123'

                    ],
                'reference' => 'geo_area_spain'
            ],
            [
                'fields' =>
                    [
                        'country' => $this->getReference("country_spain"),
                        'region' => 'spain_region',
                        'city' => 'Madrid',
                        'streetAddress' => 'baker street',
                        'zip' => '456123'

                    ],
                'reference' => 'geo_area_spain_madrid'
            ],
            [
                'fields' =>
                    [
                        'country' => $this->getReference("country_spain"),
                        'region' => 'spain_region',
                        'city' => 'Toledo',
                        'streetAddress' => 'baker street',
                        'zip' => '456123'

                    ],
                'reference' => 'geo_area_spain_toledo'
            ],

        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], GeographicalArea::class);
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
        return 20;
    }

}
