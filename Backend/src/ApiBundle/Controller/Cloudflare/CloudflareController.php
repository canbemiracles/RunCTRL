<?php

namespace ApiBundle\Controller\Cloudflare;

use ApiBundle\Controller\AbstractController;
use Gpenverne\CloudflareBundle\Services\CloudflareService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Cloudflare controller.
 *
 * @Route("cloudflare")
 */
class CloudflareController extends AbstractController
{
    /**
     * @Security("is_granted('setting_cloudflare')")
     * @ApiDoc(
     *  section="[Cloudflare] Cloudflare",
     *  description="Cloudflare.",
     *  output="ApiBundle\Entity\Cloudflare\Cloudflare",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/setting", name="setting_cloudflare")
     * @Rest\View()
     * @param Request $request
     * @return JsonResponse
     */
    public function settingCloudflareAction(Request $request)
    {
        return $this->get('service.cloudflare.cloudflare_setting')->setting(
            $request->get('method'),
            $request->get('url'),
            $request->get('param')
        );

    }

}
