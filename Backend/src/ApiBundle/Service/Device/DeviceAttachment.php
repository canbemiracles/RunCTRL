<?php

namespace ApiBundle\Service\Device;

use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\OAuth\AccessToken;
use ApiBundle\Entity\OAuth\Client;
use ApiBundle\Entity\User\Device\Device;
use ApiBundle\Entity\User\Group;
use ApiBundle\Service\Generator\DeviceGenerate;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use FOS\OAuthServerBundle\Entity\AccessTokenManager;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 03.09.2017
 * Time: 20:15
 */

class DeviceAttachment
{
    /** @var EntityManager */
    protected $em;

    protected $accessTokenManager;

    /** @var DeviceGenerate */
    protected $device_generate;

    /** @var TranslatorInterface */
    protected $translator;

    public function __construct(EntityManager $em, AccessTokenManager $accessTokenManager, TranslatorInterface $translator, DeviceGenerate $device_generate)
    {
        $this->em = $em;
        $this->accessTokenManager = $accessTokenManager;
        $this->translator = $translator;
        $this->device_generate = $device_generate;
    }

    /**
     * Attaching device to a branch station. Returns access token or error string.
     *
     * @param $code
     * @param $type
     * @return AccessToken|string
     */
    public function attachDeviceToBranchStation($code, $type)
    {
        /** @var BranchStation */
        $branchStation = $this->em->getRepository('ApiBundle:BranchStation')->findOneBy(['device_code' => $code]);
        /** @var Group */
        $group = $this->em->getRepository('ApiBundle:User\Group')->findOneBy(['name' => 'device']);
        /** @var Client */
        $client = $this->em->getRepository('ApiBundle:OAuth\Client')->findOneBy([]);

        if($branchStation == null) {
            return $this->translator->trans("device.code_not_found");
        }

        $name = $this->device_generate->generateName($code, $type);

        $device = new Device();
        $device->setUsername($name);
        $device->setEmail($name);
        $device->setUsernameCanonical($name);
        $device->setEmailCanonical($name);
        $device->setPassword('');
        $device->setCompany($branchStation->getBranch()->getCompany());
        $device->setEnabled(1);
        $device->addGroup($group);
        $device->setStation($branchStation);
        $device->setRoles([]);
        $this->em->persist($device);

        /** @var AccessToken */
        $token = $this->accessTokenManager->createToken();
        $token->setUser($device);
        $token->setToken($name);
        //TODO: think about better way to getting client.
        $token->setClient($client);
        $this->em->persist($token);

        $branchStation->addDevice($device);
        $this->em->persist($branchStation);

        $this->em->flush();

        return $token;
    }

    /**
     * Detaching device to a branch station. Returns success string or error string.
     *
     * @param Device $device
     * @return string
     */
    public function detachDeviceFromBranchStation($device)
    {
        /** @var $current_device Device */
        $current_device = $this->em->getRepository('ApiBundle:User\Device\Device')->findOneBy(['id' => $device->getId()]);

        if($current_device == null) {
            return $this->translator->trans("device.not_found");
        }

         /** @var $branchStation BranchStation */
        $branchStation = $this->em->getRepository('ApiBundle:BranchStation')->findOneBy(['id' =>  $current_device->getBranchId()]);

        if($branchStation == null) {
            return $this->translator->trans("device.code_not_found");
        }

        foreach ($current_device->getGroups() as $item) {
            $current_device->removeGroup($item);
        }

        $branchStation->removeDevice($current_device);
        $this->em->remove($current_device);
        $this->em->persist($branchStation);

        /** @var $accessToken Collection AccessToken */
        $accessTokens = $this->em->getRepository('ApiBundle:OAuth\AccessToken')->findBy(['user' => $device->getId()]);
        foreach ($accessTokens as $token) {
            $this->em->remove($token);
        }
        
        $this->em->flush();

        return "Success";
    }
}