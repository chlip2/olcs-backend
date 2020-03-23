<?php

/**
 * IrhpPermitStock
 */
namespace Dvsa\Olcs\Api\Domain\Repository;

use DateTime;
use Doctrine\ORM\Query;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitStock as Entity;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitType as IrhpPermitTypeEntity;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermit as IrhpPermitEntity;

/**
 * IrhpPermitStock
 */
class IrhpPermitStock extends AbstractRepository
{
    protected $entity = Entity::class;

    protected $alias = 'ips';

    /**
     * Returns list of stocks ready to print
     *
     * @param int    $irhpPermitTypeId Irhp Permit Type Id
     * @param string $countryId        Country Id
     *
     * @return array
     */
    public function fetchReadyToPrint($irhpPermitTypeId, $countryId = null)
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select($this->alias)
            ->distinct()
            ->innerJoin($this->alias . '.irhpPermitRanges', 'ipr')
            ->innerJoin('ipr.irhpPermits', 'ip')
            ->where($qb->expr()->in('ip.status', ':statuses'))
            ->andWhere($this->alias . '.irhpPermitType = :irhpPermitTypeId')
            ->setParameter('statuses', IrhpPermitEntity::$readyToPrintStatuses)
            ->setParameter('irhpPermitTypeId', $irhpPermitTypeId);

        if (!empty($countryId)) {
            $qb
                ->andWhere($this->alias . '.country = :countryId')
                ->setParameter('countryId', $countryId);
        }

        $qb->orderBy($this->alias . '.validFrom', 'DESC');

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * Returns the count of permit stocks for a type and validity period
     *
     * @param int $permitTypeId
     * @param string $validFrom
     * @param string $validTo
     * @return int
     */
    public function getPermitStockCountByTypeDate($permitTypeId, $validFrom, $validTo, $excludeId)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('count(ips.id)')
            ->from(Entity::class, 'ips')
            ->where('ips.irhpPermitType = ?1')
            ->andWhere('ips.validFrom = ?2')
            ->andWhere('ips.validTo = ?3')
            ->andWhere('ips.id != ?4')
            ->setParameter(1, $permitTypeId)
            ->setParameter(2, $validFrom)
            ->setParameter(3, $validTo)
            ->setParameter(4, $excludeId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param int $irhpPermitType
     * @return array
     */
    public function fetchByIrhpPermitType(int $irhpPermitType)
    {
        return $this->fetchByX('irhpPermitType', [$irhpPermitType]);
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        $qb = $this->createQueryBuilder();
        return $qb->getQuery()->getResult();
    }

    /**
     * Fetch all bilateral stocks with open windows for a given country
     *
     * @param string $country
     * @param DateTime $now Now
     *
     * @return array
     */
    public function fetchOpenBilateralStocksByCountry(string $country, DateTime $now)
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select($this->alias)
            ->innerJoin('ips.irhpPermitType', 'ipt')
            ->innerJoin('ips.irhpPermitWindows', 'ipw')
            ->innerJoin('ips.country', 'c')
            ->where($qb->expr()->eq($this->alias.'.country', ':country'))
            ->andWhere($qb->expr()->lte('ipw.startDate', ':now'))
            ->andWhere($qb->expr()->gt('ipw.endDate', ':now'))
            ->andWhere($qb->expr()->eq('ipt.id', ':type'))
            ->setParameter('country', $country)
            ->setParameter('type', IrhpPermitTypeEntity::IRHP_PERMIT_TYPE_ID_BILATERAL)
            ->setParameter('now', $now->format(DateTime::ISO8601));

        return $qb->getQuery()->getResult();
    }
}
