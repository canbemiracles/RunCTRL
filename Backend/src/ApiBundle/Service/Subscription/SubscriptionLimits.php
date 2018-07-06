<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 11.10.2017
 * Time: 15:12
 */

namespace ApiBundle\Service\Subscription;


use ApiBundle\Entity\Branch;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\User\AbstractUser;
use Doctrine\ORM\EntityManager;

class SubscriptionLimits
{
    /** @var EntityManager*/
    protected $entityManager;

    protected $freePlanLimits;

    protected $proPlanLimits;

    protected $enterprisePlanLimits;

    public function __construct(EntityManager $entityManager, $freePlanLimits, $proPlanLimits, $enterprisePlanLimits)
    {
        $this->entityManager = $entityManager;
        $this->freePlanLimits = array_shift($freePlanLimits);
        $this->proPlanLimits = array_shift($proPlanLimits);
        $this->enterprisePlanLimits = array_shift($enterprisePlanLimits);
    }

    public function canCreateBranch(AbstractUser $user)
    {
        if(!$user) {
            return false;
        }

        $canCreate = true;
        $company = $user->getCompany();
        $userPlan = $company->getPlan();

        switch ($userPlan)
        {
            case Company::PLAN_FREE:
            {
                if(!isset($this->freePlanLimits["branch_max"])) {
                    break;
                } elseif($company->getBranches()->count() >= $this->freePlanLimits["branch_max"]) {
                    $canCreate = false;
                }
                break;
            }
            case Company::PLAN_PRO:
            {
                if(!isset($this->proPlanLimits["branch_max"])) {
                    break;
                } elseif($company->getBranches()->count() >= $this->proPlanLimits["branch_max"]) {
                    $canCreate = false;
                }
                break;
            }
            case Company::PLAN_ENTERPRISE:
            {
                if(!isset($this->enterprisePlanLimits["branch_max"])) {
                    break;
                } elseif($company->getBranches()->count() >= $this->enterprisePlanLimits["branch_max"]) {
                    $canCreate = false;
                }
                break;
            }
            default:
                $canCreate = true;
                break;
        }

        return $canCreate;
    }

    public function canCreateStation(AbstractUser $user, Branch $branch)
    {
        $currentStationCount = $branch->getStations()->count();
        $canCreate = true;

        $company = $user->getCompany();
        $userPlan = $company->getPlan();

        switch ($userPlan)
        {
            case Company::PLAN_FREE:
            {
                if(!isset($this->freePlanLimits["station_max"])) {
                    break;
                } elseif($currentStationCount >= $this->freePlanLimits["station_max"]) {
                    $canCreate = false;
                }
                break;
            }
            case Company::PLAN_PRO:
            {
                if(!isset($this->proPlanLimits["station_max"])) {
                    break;
                } elseif($company->getBranches()->count() >= $this->proPlanLimits["station_max"]) {
                    $canCreate = false;
                }
                break;
            }
            case Company::PLAN_ENTERPRISE:
            {
                if(!isset($this->enterprisePlanLimits["station_max"])) {
                    break;
                } elseif($company->getBranches()->count() >= $this->enterprisePlanLimits["station_max"]) {
                    $canCreate = false;
                }
                break;
            }
            default:
                $canCreate = true;
                break;

        }
        return $canCreate;
    }

    public function canCreateAssignment(AbstractUser $user)
    {
        $canCreate = true;

        $company = $user->getCompany();
        $userPlan = $company->getPlan();

        switch ($userPlan)
        {
            case Company::PLAN_FREE:
            {
                if(!isset($this->freePlanLimits["total_tasks"])) {
                    break;
                } elseif($this->getTaskCountByCompany($company) >= $this->freePlanLimits["total_tasks"]) {
                    $canCreate = false;
                }
                break;
            }
            case Company::PLAN_PRO:
            {
                if(!isset($this->proPlanLimits["total_tasks"])) {
                    break;
                } elseif($this->getTaskCountByCompany($company) >= $this->proPlanLimits["total_tasks"]) {
                    $canCreate = false;
                }
                break;
            }
            case Company::PLAN_ENTERPRISE:
            {
                if(!isset($this->enterprisePlanLimits["total_tasks"])) {
                    break;
                } elseif($this->getTaskCountByCompany($company) >= $this->enterprisePlanLimits["total_tasks"]) {
                    $canCreate = false;
                }
                break;
            }
            default:
                $canCreate = true;
                break;

        }
        return $canCreate;
    }

    private function getTaskCountByCompany(Company $company)
    {
        return $this->entityManager->getRepository('AssignmentsBundle:Assignment\AbstractAssignment')->getCountTasksByCompany($company);
    }

}