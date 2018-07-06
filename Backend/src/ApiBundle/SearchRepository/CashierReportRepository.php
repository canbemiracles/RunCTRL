<?php

namespace ApiBundle\SearchRepository;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\Company;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use FOS\ElasticaBundle\Repository;
use Elastica\Query\Term;

class CashierReportRepository extends Repository
{
    public function findWithCustomQuery(Company $company, string $searchText = '', Branch $branch = null)
    {
        $boolQuery = new BoolQuery();

        $boolQueryPart = new BoolQuery();

        $payment_method = new Match();
        $payment_method->setFieldQuery('payment_method', $searchText);

        $amount = new Match();
        $amount->setFieldQuery('amount', (float)$searchText);

        $companyTerm = new Term();
        $companyTerm->setTerm('branch_shift.company_id', $company->getId());

        if($branch) {
            $branchTerm = new Term();
            $branchTerm->setTerm('branch_shift.branch_id', $branch->getId());
            $boolQuery->addMust($branchTerm);
        }

        $boolQueryPart->addShould($payment_method)
        ->addShould($amount);

        $boolQuery
            ->addMust($boolQueryPart)
            ->addMust($companyTerm);

        return $this->find($boolQuery);
    }
}