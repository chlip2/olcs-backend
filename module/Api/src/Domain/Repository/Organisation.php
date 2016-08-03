<?php

namespace Dvsa\Olcs\Api\Domain\Repository;

use Doctrine\ORM\Query;
use Dvsa\Olcs\Api\Domain\Exception\NotFoundException;
use Dvsa\Olcs\Api\Entity\Organisation\Organisation as Entity;
use Zend\Stdlib\ArraySerializableInterface as QryCmd;

/**
 * Organisation
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class Organisation extends AbstractRepository
{
    protected $entity = Entity::class;

    protected $alias = 'o';

    /**
     * Fetch business details by Id
     *
     * @param QryCmd $query       Query or Command
     * @param int    $hydrateMode Hydrate mode
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function fetchBusinessDetailsUsingId(QryCmd $query, $hydrateMode = Query::HYDRATE_OBJECT)
    {
        return $this->fetchBusinessDetailsById($query->getId(), $hydrateMode);
    }

    /**
     * Fetch business details by Id
     *
     * @param int $id          Identifier
     * @param int $hydrateMode Hydrate mode
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function fetchBusinessDetailsById($id, $hydrateMode = Query::HYDRATE_OBJECT)
    {
        $qb = $this->createQueryBuilder();

        $this->buildDefaultQuery($qb, $id)->withContactDetails();

        $results = $qb->getQuery()->getResult($hydrateMode);

        if (empty($results)) {
            throw new NotFoundException('Organisation not found');
        }

        return $results[0];
    }

    /**
     * Fetch Ifro details by Id
     *
     * @param QryCmd $query       Command or Query
     * @param int    $hydrateMode Hydrate mode
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function fetchIrfoDetailsUsingId(QryCmd $query, $hydrateMode = Query::HYDRATE_OBJECT)
    {
        return $this->fetchIrfoDetailsById($query->getId(), $hydrateMode);
    }

    /**
     * Fetch Ifro details by Id
     *
     * @param int $id          Identifier
     * @param int $hydrateMode Hydrate mode
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function fetchIrfoDetailsById($id, $hydrateMode = Query::HYDRATE_OBJECT)
    {
        $qb = $this->createQueryBuilder();

        $this->getQueryBuilder()->modifyQuery($qb)
            ->withRefData()
            ->with('irfoNationality')
            ->with('irfoPartners')
            ->with('tradingNames', 'tn')
            ->withContactDetails('irfoContactDetails')
            ->byId($id);

        $results = $qb->getQuery()->getResult($hydrateMode);

        if (empty($results)) {
            throw new NotFoundException('Resource not found');
        }

        return $results[0];
    }

    /**
     * Get organisations by company number
     *
     * @param string $companyNumber Company number
     *
     * @return Entity[]
     * @throws NotFoundException
     */
    public function getByCompanyOrLlpNo($companyNumber)
    {
        $qb = $this->createQueryBuilder();

        $this->getQueryBuilder()->modifyQuery($qb)
            ->withRefdata();

        $qb
            ->andWhere($qb->expr()->eq($this->alias . '.companyOrLlpNo', ':companyNumber'))
            ->setParameter('companyNumber', $companyNumber);

        $results = $qb->getQuery()->getResult();

        if (empty($results)) {
            throw new NotFoundException('Organisation not found for company number '.$companyNumber);
        }

        return $results;
    }

    /**
     * Get organisations by status
     *
     * @param \Dvsa\Olcs\Transfer\Query\Organisation\CpidOrganisation $query Query
     *
     * @return array
     */
    public function fetchByStatusPaginated($query)
    {
        $qb = $this->createQueryBuilder();

        $this->getQueryBuilder()->modifyQuery($qb)
            ->withRefdata()
            ->order('name', 'asc')
            ->paginate($query->getPage(), $query->getLimit());

        if ($query->getCpid() !== Entity::OPERATOR_CPID_ALL) {
            if (is_null($query->getCpid())) {
                $qb->where($qb->expr()->isNull($this->alias . '.cpid'));
            } else {
                $status = $this->getRefdataReference($query->getCpid());
                $qb->where($qb->expr()->eq($this->alias . '.cpid', ':cpid'));
                $qb->setParameter(
                    'cpid', $status
                );
            }
        }

        return [
            'result' => $this->fetchPaginatedList($qb, Query::HYDRATE_OBJECT),
            'count' => $this->fetchPaginatedCount($qb)
        ];
    }

    /**
     * Get organisations data data for export
     *
     * @param string $status Status
     *
     * @return \Doctrine\ORM\Internal\Hydration\IterableResult
     */
    public function fetchAllByStatusForCpidExport($status = null)
    {
        $qb = $this->createQueryBuilder();

        $this->getQueryBuilder()->modifyQuery($qb)
            ->with('cpid', 'r');

        if ($status !== Entity::OPERATOR_CPID_ALL) {
            if (is_null($status)) {
                $qb->where($qb->expr()->isNull($this->alias . '.cpid'));
            } else {
                $qb->where($qb->expr()->eq($this->alias . '.cpid', ':cpid'));
                $qb->setParameter('cpid', $status);
            }
        }

        $qb->select(
            $this->alias . '.id',
            $this->alias . '.name',
            'r.id AS cpid'
        );

        $query = $qb->getQuery();

        return $query->iterate();
    }
}
