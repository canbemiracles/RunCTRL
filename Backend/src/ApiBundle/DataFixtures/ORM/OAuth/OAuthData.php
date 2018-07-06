<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OAuthData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    use FillEntityTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $idSetter = function ($obj, $id) {
            $obj->id = $id;
        };

        $clients = [
            1 => [
                'id'     => '1fwfg4mreq680s0404s8g8ggkgkkgoc08skow044o08cwckc4o',
                'secret' => '3kz917qhoo6ccw8ogg8og0k8k4kw80skokg8scsco88k8wk4wk',
            ],
        ];

        /** @var ClientManagerInterface $clientManager */
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');

        $baseClient = $clientManager->createClient();
        $baseClient->setRedirectUris([]);
        $baseClient->setAllowedGrantTypes(['password', 'refresh_token']);

        /** @var ClassMetadata $metadata */
        $metadata = $this->container->get('doctrine.orm.entity_manager')->getClassMetadata(get_class($baseClient));
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        foreach ($clients as $key => $value) {
            $client = $clientManager->findClientByPublicId($key . '_' . $value['id']);

            if (!$client) {
                $client = clone $baseClient;

                $idSetter = \Closure::bind($idSetter, null, $client);
                $idSetter($client, $key);

                $client->setRandomId($value['id']);
                $client->setSecret($value['secret']);

                $clientManager->updateClient($client);
            }
        }
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
        return 5;
    }

}
