<?php

namespace ApiBundle\SearchRepository;

use ApiBundle\Entity\Company;
use ApiBundle\Entity\Country;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use FOS\ElasticaBundle\Repository;
use Elastica\Query\Term;

class EmployeeRepository extends Repository
{
    public function findWithCustomQuery(Company $company, $country, string $searchText = '')
    {
        $boolQueryPart = new BoolQuery();

        $boolQuery = new BoolQuery();

        $first_name = new Match();
        $first_name->setFieldQuery('first_name', $searchText);

        $last_name = new Match();
        $last_name->setFieldQuery('last_name', $searchText);

        $phone_number = new Match();
        $phone_number->setFieldQuery('phone_number', $searchText);

        if($country !== null) {
            $countryTerm = new Term();
            $countryTerm->setTerm('geographical_area.country.name', $country);
            $boolQuery->addMust($countryTerm);
        }

        $companyTerm = new Term();
        $companyTerm->setTerm('company_id', $company->getId());

        $boolQueryPart
            ->addShould($first_name)
            ->addShould($last_name)
            ->addShould($phone_number);

        $boolQuery->addMust($boolQueryPart)->addMust($companyTerm);

        return $this->find($boolQuery);
    }
}