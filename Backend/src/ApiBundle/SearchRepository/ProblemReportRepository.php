<?php

namespace ApiBundle\SearchRepository;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\Company;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use FOS\ElasticaBundle\Repository;
use Elastica\Query\Term;

class ProblemReportRepository extends Repository
{
    public function findWithCustomQuery(Company $company, string $searchText = '', Branch $branch = null)
    {
        $boolQuery = new BoolQuery();

        $boolQueryPart = new BoolQuery();

        $title = new Match();
        $title->setFieldQuery('title', $searchText);

        $description = new Match();
        $description->setFieldQuery('description', $searchText);

        $companyTerm = new Term();
        $companyTerm->setTerm('branch_shift.company_id', $company->getId());

        if($branch) {
            $branchTerm = new Term();
            $branchTerm->setTerm('branch_shift.branch_id', $branch->getId());
            $boolQuery->addMust($branchTerm);
        }

        $boolQueryPart->addShould($title)
            ->addShould($description);

        $boolQuery->addMust($companyTerm)->addMust($boolQueryPart);

        return $this->find($boolQuery);
    }
}