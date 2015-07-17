<?php

/**
 * Stay
 */
namespace Dvsa\Olcs\Api\Domain\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Dvsa\Olcs\Api\Domain\Exception;
use Dvsa\Olcs\Api\Domain\QueryBuilderInterface;
use Zend\Stdlib\ArraySerializableInterface as QryCmd;
use Dvsa\Olcs\Api\Entity\Cases\Stay as Entity;
use Doctrine\ORM\QueryBuilder;
use Dvsa\Olcs\Transfer\Query\QueryInterface;

/**
 * Stay
 */
class Stay extends AbstractRepository
{
    protected $entity = Entity::class;

    public function __construct(
        EntityManagerInterface $em,
        QueryBuilderInterface $queryBuilder
    ) {
        parent::__construct($em, $queryBuilder);
    }

    /**
     * Fetch the default record by it's id
     *
     * @param Query|QryCmd $query
     * @param int $hydrateMode

     * @return mixed
     * @throws Exception\NotFoundException
     * @throws Exception\VersionConflictException
     */
    public function fetchUsingCaseId(QryCmd $query, $hydrateMode = Query::HYDRATE_OBJECT)
    {
        /* @var \Doctrine\Orm\QueryBuilder $qb*/
        $qb = $this->createQueryBuilder();

        $this->applyListJoins($qb);

        if (method_exists($query, 'getId')) {
            $qb->andWhere($qb->expr()->eq($this->alias . '.id', ':id'))
                ->setParameter('id', $query->getId());
        }

        if (method_exists($query, 'getCase')) {
            $qb->andWhere($qb->expr()->eq($this->alias . '.case', ':byCase'))
                ->setParameter('byCase', $query->getCase());
        }

        $queryBuilderHelper = $this->getQueryBuilder()->modifyQuery($qb);
        $queryBuilderHelper->withRefdata();

        $result = $qb->getQuery()->getResult($hydrateMode);

        if (empty($result)) {
            throw new Exception\NotFoundException('Resource not found');
        }

        return $result[0];
    }

    /**
     *
     * @param QueryBuilder $qb
     * @param QueryInterface $query
     */
    protected function applyListFilters(QueryBuilder $qb, QueryInterface $query)
    {
        $qb->andWhere($qb->expr()->eq($this->alias . '.case', ':byCase'))
            ->setParameter('byCase', $query->getCase());
    }

    /**
     *
     * @param QueryBuilder $qb
     */
    protected function applyListJoins(QueryBuilder $qb)
    {
        $this->getQueryBuilder()->modifyQuery($qb)
            ->withRefData()
            ->with('case')
            ->with('createdBy')
            ->with('lastModifiedBy');
    }
}
