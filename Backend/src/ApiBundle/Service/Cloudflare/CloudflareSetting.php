<?php

namespace ApiBundle\Service\Cloudflare;


use Gpenverne\CloudflareBundle\Services\CloudflareService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CloudflareSetting
{
    /** @var CloudflareService*/
    protected $cloudflare_service;

    public function __construct(CloudflareService $cloudflare_service)
    {
        $this->cloudflare_service = $cloudflare_service;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $param
     * @return JsonResponse
     */
    public function setting($method, $url, $param = array())
    {
        if(empty($method)) {
            throw new BadRequestHttpException('Param "method" should not be empty');
        }

        if(empty($url)) {
            throw new BadRequestHttpException('Param "url" should not be empty');
        }

        $result = null;

        switch ($method) {
            case 'get':
                $result = new JsonResponse($this->cloudflare_service->getApi()->get($url));
                break;
            case 'patch':
                $result = new JsonResponse($this->cloudflare_service->getApi()->patch($url, $param));
                break;
            case 'put':
                $result = new JsonResponse($this->cloudflare_service->getApi()->put($url, $param));
                break;
            default:
                break;
        }

        if(is_null($result))  {
            throw new BadRequestHttpException('Method not found');
        }

        return $result;
    }
}