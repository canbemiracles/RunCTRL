<?php

namespace ApiBundle\Service\Currency;

use Doctrine\ORM\EntityManager;
use OceanApplications\currencylayer;

class CurrencyService
{

    /** @var  EntityManager */
    protected $entityManager;

    /** @var string*/
    protected $api_key;

    /**
     * @param $entityManager EntityManager
     * @param $api_key string
     */
    public function __construct(EntityManager $entityManager, $api_key)
    {
        $this->entityManager = $entityManager;
        $this->api_key = $api_key;
    }

    /**
     * @param $source string
     * @param $currencies array
     * @return mixed
    */
    public function live($source, $currencies)
    {
        $currencylayer = new currencylayer\client($this->api_key);
        return $currencylayer
            ->source($source)
            ->currencies(implode(",", $currencies))
            ->live();
    }

    /**
     * @param $source string
     * @param $currencies array
     * @param $date string
     * @return mixed
     */
    public function historical($source, $currencies, $date)
    {
        $currencylayer = new currencylayer\client($this->api_key);
        return $currencylayer
            ->date($date)
            ->source($source)
            ->currencies(implode(",", $currencies))
            ->historical();
    }


    /**
     * @param $from string
     * @param $to string
     * @param $amount float
     * @param $date string
     * @return mixed
    */
    public function convert($from, $to, $amount, $date = null)
    {
        $currencylayer = new currencylayer\client($this->api_key);
        return $currencylayer
            ->from($from)
            ->to($to)
            ->amount($amount)
            ->date($date)
            ->convert();
    }

    /**
     * @param $source string
     * @param $currencies array
     * @param $start_date string
     * @param $end_date string
     * @return mixed
     */
    public function change($source, $currencies, $start_date, $end_date)
    {
        $currencylayer = new currencylayer\client($this->api_key);
        return $currencylayer
            ->source($source)
            ->currencies(implode(",", $currencies))
            ->start_date($start_date)
            ->end_date($end_date)
            ->change();
    }

}