<?php

namespace Dvsa\Olcs\Api\Domain\Repository;

use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitJurisdictionQuota as Entity;

/**
 * IRHP Permit Jurisdiction Quota
 *
 * @author Jonathan Thomas <jonathan@opalise.co.uk>
 */
class IrhpPermitJurisdictionQuota extends AbstractRepository
{
    protected $entity = Entity::class;

    /**
     * Returns a list of jurisdiction id/jurisdiction quota pairs relating to the specified stock, where the
     * jurisdiction quotas are greater than zero
     *
     * @param int $stockId
     *
     * @return array
     */
    public function fetchByNonZeroQuota($stockId)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('IDENTITY(ipjq.trafficArea) as jurisdictionId, ipjq.quotaNumber')
            ->from(Entity::class, 'ipjq')
            ->where('ipjq.quotaNumber > 0')
            ->andWhere('IDENTITY(ipjq.irhpPermitStock) = ?1')
            ->setParameter(1, $stockId)
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * Fetch IRHP Jurisdiction by Permit Stock ID
     *
     * @param int $irhpPermitStockId
     * @return array
     */
    public function fetchByIrhpPermitStockId($irhpPermitStockId)
    {
        $doctrineQb = $this->createQueryBuilder();

        $doctrineQb
            ->andWhere($doctrineQb->expr()->eq($this->alias . '.irhpPermitStock', ':irhpPermitStock'))
            ->setParameter('irhpPermitStock', $irhpPermitStockId);

        return $doctrineQb->getQuery()->getResult();
    }

    /**
     * Update a Jurisdiction Permit Quantity by Permit Stock ID and Traffic Area.
     *
     * @param string $quotaNumber
     * @param string $trafficArea
     * @param int $irhpPermitStockId
     */
    public function updateTrafficAreaPermitQuantity($quotaNumber, $trafficArea, $irhpPermitStockId)
    {
        $doctrineQb = $this->createQueryBuilder();

        $doctrineQb
            ->update(Entity::class, 'ipsq')
            ->set('ipsq.quotaNumber', $doctrineQb->expr()->literal($quotaNumber))
            ->where($doctrineQb->expr()->eq('ipsq.irhpPermitStock', ':irhpPermitStock'))
            ->andWhere($doctrineQb->expr()->eq('ipsq.trafficArea', ':trafficArea'))
            ->setParameter('irhpPermitStock', $irhpPermitStockId)
            ->setParameter('trafficArea', $trafficArea);

        $doctrineQb->getQuery()->execute();
    }
}
