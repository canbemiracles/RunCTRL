<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\User\Group;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\GroupManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GroupData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    use FillEntityTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        /** @var GroupManagerInterface $groupManager */
        $groupManager = $this->container->get('fos_user.group_manager');

        $data = [
            [
                'fields' =>
                    [
                        'name' => 'admin',
                        'role' => array("ROLE_ADMIN"),
                    ],
                'reference' => 'group_admin'
            ],
            [
                'fields' =>
                    [
                        'name' => 'supervisor',
                        'role' => array("ROLE_SUPERVISOR"),
                    ],
                'reference' => 'group_supervisor'
            ],
            [
                'fields' =>
                    [
                        'name' => 'branch_manager',
                        'role' => array("ROLE_BRANCH_MANAGER"),
                    ],
                'reference' => 'group_manager'
            ],
            [
                'fields' =>
                    [
                        'name' => 'co_manager',
                        'role' => array("ROLE_CO_MANAGER"),
                    ],
                'reference' => 'group_co_manager'
            ],
            [
                'fields' =>
                    [
                        'name' => 'office_employee',
                        'role' => array("ROLE_OFFICE_EMPLOYEE"),
                    ],
                'reference' => 'group_office_employee'
            ],
            [
                'fields' =>
                    [
                        'name' => 'owner',
                        'role' => array("ROLE_OWNER"),
                    ],
                'reference' => 'group_owner'
            ],
            [
                'fields' =>
                    [
                        'name' => 'device',
                        'role' => array("ROLE_DEVICE"),
                    ],
                'reference' => 'group_device'
            ],
        ];


        foreach ($data as $key => $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], new Group($itemData['fields']['name'], $itemData['fields']['role']), false);
            $groupManager->updateGroup($entity);
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
        return 4;
    }

}
