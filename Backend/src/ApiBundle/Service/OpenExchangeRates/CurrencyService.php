<?php

namespace ApiBundle\Service\OpenExchangeRates;

use Doctrine\ORM\EntityManager;
use Mrzard\OpenExchangeRates\Service\OpenExchangeRatesService;


/**
 * Class OpenExchangeRatesService
 * This class exposes the OpenExchangeRates API
 */

class CurrencyService extends OpenExchangeRatesService
{

    /** @var  EntityManager */
    protected $entityManager;

    /**
     * @param $entityManager EntityManager
     * @param $openExchangeRatesAppId string
     * @param $apiOptions array
     * @param $client object
     */
    public function __construct(EntityManager $entityManager, $openExchangeRatesAppId, $apiOptions, $client)
    {
        $this->entityManager = $entityManager;
        parent::__construct($openExchangeRatesAppId, $apiOptions, $client);
    }

}