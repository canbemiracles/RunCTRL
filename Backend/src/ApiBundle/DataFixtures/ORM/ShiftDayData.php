<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\ShiftDay;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ShiftDayData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'day' => '1',
                        'branch' => [$this->getReference('branch_mc_donalds')],
                    ],
                'reference' => 'shift_day_monday'
            ],
            [
                'fields' =>
                    [
                        'day' => '2',
                        'branch' => [$this->getReference('branch_mc_donalds')],
                    ],
                'reference' => 'shift_day_tuesday'
            ],
            [
                'fields' =>
                    [
                        'day' => '3',
                    ],
                'reference' => 'shift_day_wednesday'
            ],
            [
                'fields' =>
                    [
                        'day' => '4',
                    ],
                'reference' => 'shift_day_thursday'
            ],
            [
                'fields' =>
                    [
                        'day' => '5',
                    ],
                'reference' => 'shift_day_friday'
            ],
            [
                'fields' =>
                    [
                        'day' => '6',
                    ],
                'reference' => 'shift_day_saturday'
            ],
            [
                'fields' =>
                    [
                        'day' => '7',
                    ],
                'reference' => 'shift_day_sunday'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], ShiftDay::class);
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
        return 150;
    }

}
