<?php

namespace ApiBundle\Service\Elastica;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\Country;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use JMS\Serializer\Serializer;
use Doctrine\Common\Collections\Collection;
use FOS\ElasticaBundle\Manager\RepositoryManager;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;

class SearchService
{
    /** @var Collection TransformedFinder  */
    protected $finders;

    /** @var Serializer  */
    protected $serializer;

    /** @var RepositoryManager  */
    protected $manager;

    /**
     * SearchService constructor.
     * @param RepositoryManagerInterface $manager
     * @param mixed $finders
     * @param Serializer $serializer
     */
    public function __construct(RepositoryManagerInterface $manager, $finders, Serializer $serializer)
    {
        $this->manager = $manager;
        $this->finders = $finders;
        $this->serializer = $serializer;
    }

    /**
     * Search function
     *
     * @param string $type Type to search (Reports, Employees..)
     * @param string $term String to search
     * @param Company $company Company to search
     * @param Branch $branch
     * @param null|string $country Country to search (Can be null, only used for employee search)
     * @return array
     * @internal param $params
     */
    public function search(string $type, string $term, Company $company, Branch $branch = null, string $country = null)
    {
        if(empty($type) || empty($term) || $company == null)
        {
            return [];
        }
        $results = [];
        switch($type) {
            case 'cashier_report':
                /** @var $repository \ApiBundle\SearchRepository\CashierReportRepository */
                $repository = $this->manager->getRepository('ApiBundle:Report\CashierReport');
                $results = $repository->findWithCustomQuery($company, $term, $branch);
                break;
            case 'commodity_report':
                /** @var $repository \ApiBundle\SearchRepository\CommodityReportRepository */
                $repository = $this->manager->getRepository('ApiBundle:Report\CommodityReport');
                $results = $repository->findWithCustomQuery($company, $term, $branch);
                break;
            case 'problem_report':
                /** @var $repository \ApiBundle\SearchRepository\ProblemReportRepository */
                $repository = $this->manager->getRepository('ApiBundle:Report\ProblemReport');
                $results = $repository->findWithCustomQuery($company, $term, $branch);
                break;
            case 'end_of_shift_report':
                /** @var $repository \ApiBundle\SearchRepository\EndOfShiftReportRepository */
                $repository = $this->manager->getRepository('ApiBundle:Report\EndOfShiftReport');
                $results = $repository->findWithCustomQuery($company, $term, $branch);
                break;
            case 'report_all':
                /** @var $repository \ApiBundle\SearchRepository\CashierReportRepository */
                $repository = $this->manager->getRepository('ApiBundle:Report\CashierReport');
                $temp_reports[] = $repository->findWithCustomQuery($company, $term, $branch);
                /** @var $repository \ApiBundle\SearchRepository\CommodityReportRepository */
                $repository = $this->manager->getRepository('ApiBundle:Report\CommodityReport');
                $temp_reports[] = $repository->findWithCustomQuery($company, $term, $branch);
                /** @var $repository \ApiBundle\SearchRepository\ProblemReportRepository */
                $repository = $this->manager->getRepository('ApiBundle:Report\ProblemReport');
                $temp_reports[] = $repository->findWithCustomQuery($company, $term, $branch);
                /** @var $repository \ApiBundle\SearchRepository\EndOfShiftReportRepository */
                $repository = $this->manager->getRepository('ApiBundle:Report\EndOfShiftReport');
                $temp_reports[] = $repository->findWithCustomQuery($company, $term, $branch);
                foreach($temp_reports as $reports) {
                    foreach ($reports as $report) {
                        $results[] = $report;
                    }
                }
                break;
            case 'employee':
                /** @var $repository \ApiBundle\SearchRepository\EmployeeRepository */
                $repository = $this->manager->getRepository('ApiBundle:Employee');
                $results = $repository->findWithCustomQuery($company, $country, $term);
                break;
            default:
                $results = [];
                break;
        }

        $response = [];

        foreach($results as $result) {
            $response[] = array(
                "object" => (new \ReflectionClass($result))->getShortName(),
                "data" => $this->serializer->toArray($result)
            );
        }

        return $response;
    }

}
