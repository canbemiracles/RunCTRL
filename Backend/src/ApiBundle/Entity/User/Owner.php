<?php

namespace ApiBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;

/**
 * Owner
 *
 * @ORM\Table(name="user_owner")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\User\OwnerRepository")
 */
class Owner extends AbstractUser
{

}

