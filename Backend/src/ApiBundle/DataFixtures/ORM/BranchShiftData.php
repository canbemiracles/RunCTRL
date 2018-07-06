<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\BranchShift;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BranchShiftData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'name' => 'shift_name_1',
                        'branch' => $this->getReference('branch_mc_donalds'),
                        'shiftDay' => $this->getReference('shift_day_monday'),
                        'timeOpen' => new DateTime('09:00:00'),
                        'timeClose' => new DateTime('23:00:00'),
                    ],
                'reference' => 'branch_shift'
            ],
            [
                'fields' =>
                    [
                        'name' => 'shift_name_2',
                        'branch' => $this->getReference('branch_mc_donalds'),
                        'shiftDay' => $this->getReference('shift_day_tuesday'),
                        'timeOpen' => new DateTime('09:00:00'),
                        'timeClose' => new DateTime('20:00:00'),
                    ],
                'reference' => 'branch_shift_2'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], BranchShift::class);
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
        return 165;
    }

}
