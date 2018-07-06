<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\User\BranchManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BranchManagerData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'login' => 'BranchManager',
                        'username' => 'Clyde Greene',
                        'email' => 'branchManager@simplex.ink',
                        'firstName' => 'Clyde',
                        'lastName' => 'Greene',
                        'plainPassword' => '123',
                        'enabled' => true,
                        'group' => [$this->getReference('group_manager')],
                        'company' => $this->getReference('company_simple_company'),
                        'branch' => $this->getReference('branch_mc_donalds'),
                    ],
                'reference' => 'user_branch_manager_clyde_greene'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], BranchManager::class);
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
        return 95;
    }

}
