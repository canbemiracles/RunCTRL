<?php

namespace ApiBundle\Entity\Session;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table(name="sessions")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Session\SessionRepository")
 */
class Session
{
    /**
     * @var int
     *
     * @ORM\Column(name="sess_id", type="string", length=128)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="sess_data", type="blob")
     */
    protected $data;

    /**
     * @ORM\Column(name="sess_time", type="integer", length=10)
     */
    protected $time;

    /**
     * @ORM\Column(name="sess_lifetime", type="integer", length=11)
     */
    protected $lifetime;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

