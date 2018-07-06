<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UTC
 *
 * @ORM\Table(name="u_t_c")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\UTCRepository")
 */
class UTC
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $value;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\TimeZone", inversedBy="utc")
     * @ORM\JoinColumn(name="time_zone_id", referencedColumnName="id", onDelete="set null")
     */
    protected $time_zone;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return UTC
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set timeZone
     *
     * @param \ApiBundle\Entity\TimeZone $timeZone
     *
     * @return UTC
     */
    public function setTimeZone(\ApiBundle\Entity\TimeZone $timeZone = null)
    {
        $this->time_zone = $timeZone;

        return $this;
    }

    /**
     * Get timeZone
     *
     * @return \ApiBundle\Entity\TimeZone
     */
    public function getTimeZone()
    {
        return $this->time_zone;
    }
}
