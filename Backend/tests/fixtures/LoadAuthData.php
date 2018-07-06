<?php

use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 01.09.2017
 * Time: 16:27
 */

class LoadAuthData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{

    use FillEntityTrait;

    public function load(ObjectManager $manager)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $client = $em->getRepository('ApiBundle:OAuth\Client')->findOneBy([]);

        $data = [
            [
                'fields' =>
                    [
                        'client' => $client,
                        'user' => $em->getRepository('ApiBundle:User\Admin')->findOneBy([]),
                        'token' => 'TOKEN_ADMIN',
                    ],
            ],
            [
                'fields' =>
                    [
                        'client' => $client,
                        'user' => $em->getRepository('ApiBundle:User\BranchManager')->findOneBy([]),
                        'token' => 'TOKEN_BRANCH_MANAGER',
                    ],
            ],
            [
                'fields' =>
                    [
                        'client' => $client,
                        'user' => $em->getRepository('ApiBundle:User\Supervisor')->findOneBy([]),
                        'token' => 'TOKEN_SUPERVISOR',
                    ],
            ],
            [
                'fields' =>
                    [
                        'client' => $client,
                        'user' => $em->getRepository('ApiBundle:User\CoManager')->findOneBy([]),
                        'token' => 'TOKEN_CO_MANAGER',
                    ],
            ],
            [
                'fields' =>
                    [
                        'client' => $client,
                        'user' => $em->getRepository('ApiBundle:User\OfficeEmployee')->findOneBy([]),
                        'token' => 'TOKEN_OFFICE_EMPLOYEE',
                    ],
            ],
            [
                'fields' =>
                    [
                        'client' => $client,
                        'user' => $em->getRepository('ApiBundle:User\Owner')->findOneBy([]),
                        'token' => 'TOKEN_OWNER',
                    ],
            ],

        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], \ApiBundle\Entity\OAuth\AccessToken::class);
            $manager->persist($entity);
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
        return 1;
    }
}