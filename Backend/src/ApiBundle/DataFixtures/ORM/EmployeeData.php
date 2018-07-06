<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\Employee;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EmployeeData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'geographicalArea' => $this->getReference('geo_area_vietnam'),
                        'firstName' => 'Eliza',
                        'lastName' => 'Aviles',
                        'familySituation' => $this->getReference('family_status_1'),
                        'hourlyRate' => '1.15',
                        'branch' => [$this->getReference('branch_mc_donalds')],
                    ],
                'reference' => 'employee_eliza_aviles'
            ],
            [
                'fields' =>
                    [
                        'geographicalArea' => $this->getReference('geo_area_belarus'),
                        'firstName' => 'Harvey',
                        'lastName' => 'Doherty',
                        'familySituation' => $this->getReference('family_status_2'),
                        'hourlyRate' => '1.15',
                        'branch' => [$this->getReference('branch_mc_donalds')],
                    ],
                'reference' => 'employee_harvey_doherty'
            ],
            [
                'fields' =>
                    [
                        'geographicalArea' => $this->getReference('geo_area_spain'),
                        'firstName' => 'Darrel',
                        'lastName' => 'Collazo',
                        'familySituation' => $this->getReference('family_status_3'),
                        'hourlyRate' => '1.15',
                        'branch' => [$this->getReference('branch_mc_donalds')],
                    ],
                'reference' => 'employee_darrel_collazo'
            ],
            [
                'fields' =>
                    [
                        'geographicalArea' => $this->getReference('geo_area_spain_madrid'),
                        'firstName' => 'Regina',
                        'lastName' => 'Sutton',
                        'familySituation' => $this->getReference('family_status_4'),
                        'hourlyRate' => '1.15',
                        'branch' => [$this->getReference('branch_mc_donalds')],
                    ],
                'reference' => 'employee_regina_sutton'
            ],
            [
                'fields' =>
                    [
                        'geographicalArea' => $this->getReference('geo_area_spain_toledo'),
                        'firstName' => 'Paula',
                        'lastName' => 'Robertson',
                        'familySituation' => $this->getReference('family_status_5'),
                        'hourlyRate' => '1.15',
                        'branch' => [$this->getReference('branch_mc_donalds')],
                    ],
                'reference' => 'employee_paula_robertson'
            ],

        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], Employee::class);
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
        return 130;
    }

}
