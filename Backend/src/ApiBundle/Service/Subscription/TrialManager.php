<?php
/**
 * Created by PhpStorm.
 * User: mail
 * Date: 15.11.2017
 * Time: 14:18
 */

namespace ApiBundle\Service\Subscription;


use ApiBundle\Entity\Company;
use ApiBundle\Entity\Report\CommodityReport;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TrialManager
{
    /** @var EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createTrialPeriod(Company $company)
    {
        if($company->getPlan() == Company::PLAN_FREE)
        {
            throw new BadRequestHttpException('You are already using free plan');
        }

        $company->setPlan(Company::PLAN_FREE);
        $company->setPlanPay(Company::PLAN_FREE_PAY_MONTH);
        $company->setPlanPayedUntil(new \DateTime('+3 month'));

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Your trial successfully activated']);
    }
}