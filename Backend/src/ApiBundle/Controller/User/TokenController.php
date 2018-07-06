<?php
namespace ApiBundle\Controller\User;

use Doctrine\ORM\EntityManager;
use FOS\OAuthServerBundle\Controller\TokenController as BaseController;
use OAuth2\OAuth2;
use Symfony\Component\HttpFoundation\Request;

class TokenController extends BaseController {

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param OAuth2 $server
     */
    public function __construct(OAuth2 $server, EntityManager $entityManager )
    {
        parent::__construct($server);
        $this->em = $entityManager;
    }

    public function tokenAction(Request $request)
    {
        $result = parent::tokenAction($request);
        return $result;
    }
}