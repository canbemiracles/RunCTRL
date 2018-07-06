<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 22.08.2017
 * Time: 18:56
 */

namespace ApiBundle\Service\Generator;


use ApiBundle\DQL\Date;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Service\Generator\Type\Interfaces\CodeInterface;
use ApiBundle\Service\Generator\Type\Interfaces\PinInterface;
use DateTime;
use Doctrine\ORM\EntityManager;

class BranchStationAccessGenerator
{

    /** @var EntityManager */
    protected $em;

    /** @var PinInterface */
    protected $pin;

    /** @var  CodeInterface */
    protected $code;

    public function __construct(EntityManager $em, PinInterface $pin, CodeInterface $code)
    {
        $this->em = $em;
        $this->pin = $pin;
        $this->code = $code;
    }

    public function generatePin(BranchStation $branchStation)
    {
        //We want to generate unique pin.
        $used_pins = $this->em->getRepository('ApiBundle:BranchStation')->getUsedPins();

        do {
            $pin_local = $this->pin->generate();
        }
        while (in_array($pin_local,$used_pins));

        $branchStation->setPin($pin_local);
        $branchStation->setPinExpire(new DateTime('+1 week'));

        $this->em->persist($branchStation);
        $this->em->flush();
    }

    public function generateCode(BranchStation $branchStation)
    {
        $bsRepository = $this->em->getRepository('ApiBundle:BranchStation');

        //We want to generate unique code.
        do {
            $code_local = $this->code->generate();
        }
        while($bsRepository->findBy(['device_code' => $code_local]) != null);

        $branchStation->setDeviceCode($code_local);

        $this->em->persist($branchStation);
        $this->em->flush();
    }

    public function getDeviceCodeByPin(int $pin)
    {
        if(!$pin) {
            return null;
        }

        /** @var $station BranchStation*/
        $station = $this->em->getRepository('ApiBundle:BranchStation')->findOneBy(['pin' => $pin]);

        if(!$station || new \DateTime() > $station->getPinExpire()) {
            return null;
        }

        return $station->getDeviceCode();
    }
}