<?php

namespace ApiBundle\SearchRepository;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\Company;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use FOS\ElasticaBundle\Repository;
use Elastica\Query\Term;

class EndOfShiftReportRepository extends Repository
{
    public function findWithCustomQuery(Company $company, string $searchText = '', Branch $branch = null)
    {
        $boolQuery = new BoolQuery();

        $boolQueryPart = new BoolQuery();

        $workday_start_time = new Match();
        $workday_start_time->setFieldQuery('workday_start_time', $searchText);

        $workday_end_time = new Match();
        $workday_end_time->setFieldQuery('workday_end_time', $searchText);

        $companyTerm = new Term();
        $companyTerm->setTerm('branch_shift.company_id', $company->getId());

        if($branch) {
            $branchTerm = new Term();
            $branchTerm->setTerm('branch_shift.branch_id', $branch->getId());
            $boolQuery->addMust($branchTerm);
        }

        $boolQueryPart->addShould($workday_start_time)
            ->addShould($workday_end_time);

        $boolQuery->addMust($companyTerm)->addMust($boolQueryPart);

        return $this->find($boolQuery);
    }
}