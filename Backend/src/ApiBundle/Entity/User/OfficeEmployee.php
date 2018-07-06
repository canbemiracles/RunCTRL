<?php

namespace ApiBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * OfficeEmployee
 *
 * @ORM\Table(name="user_office_employee")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\User\OfficeEmployeeRepository")
 */
class OfficeEmployee extends AbstractUser
{

}

