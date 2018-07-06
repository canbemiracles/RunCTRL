<?php


namespace ApiBundle\Service\Generator;

use Doctrine\ORM\EntityManager;

class DeviceGenerate
{
    /** @var EntityManager */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function generateName($code, $type)
    {
        return $type . '_' . $code . '_' . time();
    }
}