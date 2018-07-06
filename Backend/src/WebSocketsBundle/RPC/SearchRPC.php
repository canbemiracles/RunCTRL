<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 18.10.2017
 * Time: 15:27
 */

namespace WebSocketsBundle\RPC;


use ApiBundle\Entity\User\AbstractUser;
use Doctrine\ORM\EntityManager;
use Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\RPC\RpcInterface;
use Ratchet\ConnectionInterface;
use ApiBundle\Service\Elastica\SearchService;

class SearchRPC implements RpcInterface
{
    /** @var  ClientManipulatorInterface */
    protected $clientManipulator;

    /** @var SearchService */
    protected  $elastica;

    /** @var  EntityManager */
    protected $entityManager;

    public function __construct(ClientManipulatorInterface $clientManipulator, SearchService $elastica, EntityManager $entityManager)
    {
        $this->clientManipulator = $clientManipulator;
        $this->elastica = $elastica;
        $this->entityManager = $entityManager;
    }

    public function search(ConnectionInterface $connection, WampRequest $request, $params)
    {
        $client = $this->clientManipulator->getClient($connection);
        if(!($client instanceof AbstractUser)) {
            return false;
        }

        if(empty($params["type"]) || empty($params["term"])) {
            return false;
        }

        $user = $this->entityManager->getRepository('ApiBundle:User\AbstractUser')->findOneBy(['id' => $client->getId()]);
        $company = $user->getCompany();

        if(!$company) {
            return false;
        }

        $country = isset($params["country"]) ? $params["country"] : null;

        return $this->elastica->search($params["type"], $params["term"], $company, $country);
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'search.rpc';
    }
}