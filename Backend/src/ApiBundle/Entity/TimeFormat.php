<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TimeFormat
 *
 * @ORM\Table(name="time_format")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\TimeFormatRepository")
 */
class TimeFormat
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
    protected $time_format;


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
     * Set timeFormat
     *
     * @param string $timeFormat
     *
     * @return TimeFormat
     */
    public function setTimeFormat($timeFormat)
    {
        $this->time_format = $timeFormat;

        return $this;
    }

    /**
     * Get timeFormat
     *
     * @return string
     */
    public function getTimeFormat()
    {
        return $this->time_format;
    }
}
