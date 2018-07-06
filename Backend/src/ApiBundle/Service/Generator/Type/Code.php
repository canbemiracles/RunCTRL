<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 03.09.2017
 * Time: 19:50
 */

namespace ApiBundle\Service\Generator\Type;


use ApiBundle\Service\Generator\Type\Interfaces\CodeInterface;

class Code implements CodeInterface
{

    public function generate()
    {
        $length = 10;
        // Uses md5 & mt_rand. Not as "random" as it could be, but it works, and its fastest way to generate random string
        return str_shuffle(substr(str_repeat(md5(mt_rand()), 2+$length/32), 0, $length));
    }
}