<?php

namespace ApiBundle\Controller\User;

use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\Owner;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use ApiBundle\Controller\AbstractController;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;

/**
 * Owner controller.
 *
 * @Route("users/owners")
 */
class OwnerController extends AbstractController
{


}
