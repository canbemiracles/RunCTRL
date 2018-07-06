<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\User\Supervisor;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SupervisorData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    use FillEntityTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var UserManagerInterface $userManager */
        $userManager = $this->container->get('fos_user.user_manager');

        $data = [
            [
                'fields' =>
                    [
                        'login' => 'Supervisor',
                        'username' => 'Jewel Bradshaw',
                        'email' => 'supervisor@simplex.ink',
                        'firstName' => 'Jewel',
                        'lastName' => 'Bradshaw',
                        'plainPassword' => '123',
                        'enabled' => true,
                        'group' => [$this->getReference('group_supervisor')],
                        'company' => $this->getReference('company_simple_company'),
                    ],
                'reference' => 'user_supervisor_jewel_bradshaw'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], Supervisor::class);
            $manager->persist($entity);
            $userManager->updateUser($entity);
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
        return 80;
    }

}
