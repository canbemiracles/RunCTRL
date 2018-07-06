<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\DateFormat;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DateFormatData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'dateFormat' => 'dd.mm.yyyy',
                    ],
                'reference' => 'date_format_1'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'yyyy.mm.dd',

                    ],
                'reference' => 'date_format_2'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'dd/mm/yyyy',

                    ],
                'reference' => 'date_format_3'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'dd.mm.yyyy',

                    ],
                'reference' => 'date_format_4'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'dd-mm-yy',

                    ],
                'reference' => 'date_format_5'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'yyyy-mm-dd',

                    ],
                'reference' => 'date_format_6'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'dd.mm.yyyy',

                    ],
                'reference' => 'date_format_7'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'dd-mm-yyyy',

                    ],
                'reference' => 'date_format_8'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'dd.mm.yyyy',

                    ],
                'reference' => 'date_format_9'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'dd/mm/yyyy',

                    ],
                'reference' => 'date_format_10'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'yyyy/mm/dd',

                    ],
                'reference' => 'date_format_11'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'dd-mm-yyyy',

                    ],
                'reference' => 'date_format_12'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'dd.mm.yyyy',

                    ],
                'reference' => 'date_format_13'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'dd/mm/yyyy',

                    ],
                'reference' => 'date_format_14'
            ],
            [
                'fields' =>
                    [
                        'dateFormat' => 'mm/dd/yyyy',

                    ],
                'reference' => 'date_format_15'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], DateFormat::class);
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
        return 10;
    }

}
