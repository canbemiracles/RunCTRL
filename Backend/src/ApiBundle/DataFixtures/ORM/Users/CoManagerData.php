<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\User\CoManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CoManagerData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'login' => 'CoManager',
                        'username' => 'Ray Murphy',
                        'email' => 'coManager@simplex.ink',
                        'firstName' => 'Ray',
                        'lastName' => 'Murphy',
                        'plainPassword' => '123',
                        'enabled' => true,
                        'branch' => $this->getReference('branch_mc_donalds'),
                        'group' => [$this->getReference('group_co_manager')],
                        'company' => $this->getReference('company_simple_company'),
                    ],
                'reference' => 'user_comanager_ray_murphy'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], CoManager::class);
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
        return 100;
    }

}
