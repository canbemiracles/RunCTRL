<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\Employee;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FamilyStatusesData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'familyStatus' => 'Single',
                    ],
                'reference' => 'family_status_1'
            ],
            [
                'fields' =>
                    [
                        'familyStatus' => 'Married',
                    ],
                'reference' => 'family_status_2'
            ],
            [
                'fields' =>
                    [
                        'familyStatus' => 'Divorced',
                    ],
                'reference' => 'family_status_3'
            ],
            [
                'fields' =>
                    [
                        'familyStatus' => 'Widowed',
                    ],
                'reference' => 'family_status_4'
            ],
            [
                'fields' =>
                    [
                        'familyStatus' => 'In active search',
                    ],
                'reference' => 'family_status_5'
            ],
            [
                'fields' =>
                    [
                        'familyStatus' => 'It\'s Complicated',
                    ],
                'reference' => 'family_status_6'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], \ApiBundle\Entity\FamilyStatuses::class);
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
        return 125;
    }

}
