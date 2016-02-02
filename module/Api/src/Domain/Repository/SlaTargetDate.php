<?php

namespace Dvsa\Olcs\Api\Domain\Repository;

use Doctrine\ORM\Query;
use Dvsa\Olcs\Api\Domain\Exception;
use Dvsa\Olcs\Api\Entity\System\SlaTargetDate as Entity;
use Doctrine\ORM\QueryBuilder;
use Dvsa\Olcs\Transfer\Query\QueryInterface;

/**
 * SLA Target Date
 */
class SlaTargetDate extends AbstractRepository
{
    protected $entity = Entity::class;

    private $references = [];

    /**
     * Fetches SLA target date by Entity ID and Entity Type
     * Uses this as the Add command requires an entity type + an entity Id, hence this provides a composite key to
     * uniquely identify a row. Used for editing to be consistent, rather than using the primary key.
     *
     * @param $entityType
     * @param $entityId
     * @return array
     */
    public function fetchUsingEntityIdAndType(
        $entityType,
        $entityId,
        $hydrateMode = Query::HYDRATE_OBJECT,
        $version = null
    )
    {
        $qb = $this->createQueryBuilder();

        $qb->andWhere($qb->expr()->eq($this->alias . '.' . $entityType, ':byEntityId'))
            ->setParameter('byEntityId', $entityId);

        return $qb->getQuery()->getSingleResult();

/*        // If we are not locking and requesting an object, check the cache first
        $cache = ($version === null && $hydrateMode === Query::HYDRATE_OBJECT);
        if ($cache && isset($this->references[$id])) {
            return $this->references[$id];
        }

        $qb = $this->getRepository()->createQueryBuilder($this->alias);
        $query = $this->getQueryBuilder()->modifyQuery($qb)->withRefdata();

        $qb = $this->getQueryBuilder()
            ->modifyQuery($qb)
            ->withRefdata();
            $qb->andWhere($qb->expr()->eq($this->alias . '.' . $entityType, ':by' . ucfirst($entityType)))
            ->setParameter('by' . ucfirst($entityType), $entityId);

        $results = $qb->getQuery()->getResult($hydrateMode);

        if (empty($results)) {
            throw new Exception\NotFoundException(
                sprintf('Resource not found (%s id %s)', $this->entity, $id)
            );
        }

        if ($hydrateMode === Query::HYDRATE_OBJECT && $version !== null) {
            $this->lock($results[0], $version);
        }

        if ($cache) {
            $this->references[$id] = $results[0];
        }

        return $results[0];*/
    }

    /**
     * Apply List Filters
     * @param QueryBuilder $qb
     * @param QueryInterface $query
     * @return QueryBuilder|void
     */
    protected function applyListFilters(QueryBuilder $qb, QueryInterface $query)
    {
        $entityType = $query->getEntityType();
        $qb->andWhere($qb->expr()->eq($this->alias . '.' . $entityType, ':by' . ucfirst($entityType)))
            ->setParameter('by' . ucfirst($entityType), $query->getEntityId());

        return $qb;
    }
}
