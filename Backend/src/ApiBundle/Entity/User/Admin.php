<?php

namespace ApiBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * Admin
 *
 * @ORM\Table(name="user_admin")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\User\AdminRepository")
 */
class Admin extends AbstractUser
{

}
