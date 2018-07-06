<?php

namespace ApiBundle\Service\Generator\Type;


use ApiBundle\Service\Generator\Type\Interfaces\PinInterface;

class Pin implements PinInterface
{
    public function generate()
    {
        return mt_rand(1000, 9999);
    }
}