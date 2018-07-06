<?php

namespace ApiBundle\Repository\User;

use ApiBundle\Entity\Company;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

/**
 * AbstractUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AbstractUserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Returns users online
     * @param Company $company
     * @return mixed
     * TODO add config 5 minutes
     */
    public function getUsersOnline($company, $device = false)
    {
        $qb = $this->createQueryBuilder('User');
        $qb->innerJoin('User.company', 'c');

        $qb->andWhere('User.company = :company')
            ->andWhere('TIMESTAMPDIFF(second, :current, User.lastLogin) >= 0')
            ->setParameter('company', $company)
            ->setParameter('current', date('Y-m-d H:i:s', strtotime("+5 minutes", strtotime(date('Y-m-d H:i:s')))));

        if($device) {
            $qb->andWhere("User INSTANCE OF ApiBundle\Entity\User\Device\Device");
        } else {
            $qb->andWhere("User INSTANCE OF ApiBundle\Entity\User\Admin 
                or User INSTANCE OF ApiBundle\Entity\User\BranchManager
                or User INSTANCE OF ApiBundle\Entity\User\CoManager
                or User INSTANCE OF ApiBundle\Entity\User\OfficeEmployee
                or User INSTANCE OF ApiBundle\Entity\User\Owner
                or User INSTANCE OF ApiBundle\Entity\User\Supervisor");
        }

        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function getUserByAccessToken($token)
    {
        $qb = $this->createQueryBuilder('User');
        $qb->innerJoin('User.access_tokens', 'ac');
        $qb->andWhere('ac.token = :token')
            ->setParameter('token', $token);
        $query = $qb->getQuery();
        if(count($query->getResult()) > 1) {
            throw new InternalErrorException("Token is not unique");
        }
        return $query->getOneOrNullResult();
    }
}