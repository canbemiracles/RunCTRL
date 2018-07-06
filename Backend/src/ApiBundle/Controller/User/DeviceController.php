<?php

namespace ApiBundle\Controller\User;

use ApiBundle\Entity\Company;
use ApiBundle\Entity\User\Device\Device;
use ApiBundle\Service\Device\DeviceAttachment;
use ApiBundle\Service\Device\DeviceService;
use ApiBundle\Service\Generator\BranchStationAccessGenerator;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ApiBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Device controller.
 *
 * @Route("users/devices")
 */
class DeviceController extends AbstractController
{
    /**
     * @Rest\RequestParam(name="device_code", allowBlank=false)
     * @Rest\RequestParam(name="type", allowBlank=false, requirements="(android|ipad)")
     * @ApiDoc(
     *  section="Device",
     *  description="Create device entity from device code.",
     *  output="ApiBundle\Entity\User\Device\Device",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Post("/new", name="user_device_new")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param ParamFetcher $paramFetcher
     * @internal param Request $request
     */
    public function newAction(ParamFetcher $paramFetcher)
    {
        return $this->get('service.device.device_attachment')->attachDeviceToBranchStation(
            $paramFetcher->get('device_code'),
            $paramFetcher->get('type')
        );
    }

    /**
     * @Rest\RequestParam(name="pin", allowBlank=false)
     * @ApiDoc(
     *  section="Device",
     *  description="Getting device code by pin",
     *  tags = {
     *    "Implemented" = "Green",
     *  },
     * )
     * @Rest\Post("/code", name="user_device_code")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param ParamFetcher $paramFetcher
     * @return JsonResponse
     */
    public function pinToDeviceCodeAction(ParamFetcher $paramFetcher)
    {

        /** @var $accessGenerator BranchStationAccessGenerator*/
        $accessGenerator = $this->get( 'service.generator.branch_access_generator');

        $deviceCode = $accessGenerator->getDeviceCodeByPin($paramFetcher->get('pin'));

        if(!$deviceCode) {
            throw new BadRequestHttpException('Pin code is not valid');
        }

        return new JsonResponse(['device_code' => $deviceCode]);

    }

    /**
     * @ApiDoc(
     *  section="Device",
     *  description="Edit device entity.",
     *  output="ApiBundle\Entity\User\Device\Device",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="user_device_put")
     * @Rest\Patch("/{id}", name="user_device_patch")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Request $request,
     * @param Device $device
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Device
     */
    public function editAction(Request $request, Device $device)
    {
        if(!$device) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        return $this->get('service.device.device_service')->editDeviceStation($device, $request->get("station"));
    }

    /**
     * @ApiDoc(
     *  section="Device",
     *  description="Delete device entity from device code.",
     *  output="ApiBundle\Entity\User\Device\Device",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\Delete("/{id}", name="user_device_delete")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Device $device
     * @return string
     */
    public function deleteAction(Device $device)
    {
        if(!$device) {
            throw new NotFoundHttpException($this->get('translator')->trans('user_not_found'));
        }

        /** @var $service DeviceAttachment */
        $service = $this->get('service.device.device_attachment');

        return $service->detachDeviceFromBranchStation($device);
    }

    /**
     * @Security("is_granted('user_device_token')")
     * @ApiDoc(
     *  section="Device",
     *  description="Add/Edit token.",
     *  output="ApiBundle\Entity\User\Device\Device",
     *  tags = {
     *    "Not Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/token", name="user_device_token")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @return string
     */
    public function tokenAction(Request $request)
    {
        /** @var $service DeviceService */
        $service = $this->get('service.device.device_service');

        return $service->addToken($request->get('token'));
    }

}
