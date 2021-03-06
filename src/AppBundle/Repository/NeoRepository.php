<?php

namespace AppBundle\Repository;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * NeoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NeoRepository extends \Doctrine\ORM\EntityRepository
{
    public function findFastestAsteroid($isHazardous = false)
    {
        /**
         * The functionality can be achived with sub query like bellow
         * SELECT a.* FROM neo a
         * INNER JOIN (
         *  SELECT is_hazardous, MAX(speed) maxspeed
         *  FROM neo
         *  GROUP BY is_hazardous
         * ) b ON a.is_hazardous = b.is_hazardous AND a.speed = b.maxspeed
         *
         * The solution bellow is an alternate to the sub query which avoids sub-query
         */
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder
            ->select('a')
            ->leftJoin('AppBundle:Neo', 'b', 'WITH', 'a.isHazardous = b.isHazardous AND a.speed < b.speed')
            ->andWhere('b.isHazardous IS NULL')
            ->andWhere('a.isHazardous = :isHazardous')
            ->setParameter('isHazardous', $isHazardous);

        $query = $queryBuilder->getQuery();
        $result = $query->getSingleResult();

        return $result;
    }

    public function findYearWithMostAsteroid($isHazardous = false)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');

        $queryBuilder = $this->createQueryBuilder('a');

        // info: Only a few databases have the _LIMIT_ clause like mysql
        // the workarounds is to use setFirstResult(), setMaxResults()
        $queryBuilder
            ->select('YEAR(a.date) as year', 'count(a.date) as neocount')
            ->groupBy('year', 'a.isHazardous')
            ->having('a.isHazardous = :isHazardous')
            ->orderBy('neocount', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1);

        // not sure method chaining in here was not working for setting up parameter
        // may be because of use of the user defined function
        // would be interesting to find out reason later
        $queryBuilder->setParameter('isHazardous', $isHazardous);

        $query = $queryBuilder->getQuery();
        $result = $query->getSingleResult();

        return $result;
    }

    public function findMonthWithMostAsteroid($isHazardous = false)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('EXTRACT', 'DoctrineExtensions\Query\Mysql\Extract');

        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder
            ->select('EXTRACT(YEAR_MONTH from a.date) as year_month', 'count(a.date) as neocount')
            ->groupBy('year_month', 'a.isHazardous')
            ->having('a.isHazardous = :isHazardous')
            ->orderBy('neocount', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1);

        $queryBuilder->setParameter('isHazardous', $isHazardous);

        $query = $queryBuilder->getQuery();
        $result = $query->getSingleResult();
        $month = new \DateTime($result['year_month'].'01');

        return array('month' => $month->format('Y-m'), 'neocount' => $result['neocount']);
    }
}
