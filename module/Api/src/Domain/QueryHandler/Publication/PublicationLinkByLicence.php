<?php

namespace Dvsa\Olcs\Api\Domain\QueryHandler\Publication;

use Doctrine\ORM\Query as DoctrineQuery;
use Dvsa\Olcs\Api\Domain\QueryHandler\AbstractQueryHandler;
use Dvsa\Olcs\Transfer\Query\QueryInterface;
use Dvsa\Olcs\Transfer\Query\Publication\PublicationLinkLicenceList;

/**
 * PublicationLinkByLicence
 */
final class PublicationLinkByLicence extends AbstractQueryHandler
{
    protected $repoServiceName = 'PublicationLink';

    public function handleQuery(QueryInterface $query)
    {
        /** @var PublicationLinkLicenceList $query */
        $repo = $this->getRepo();

        return [
            'result' => $this->resultList(
                $repo->fetchList($query, DoctrineQuery::HYDRATE_OBJECT),
                [
                    'publicationSection',
                    'publication' => [
                        'pubStatus',
                        'trafficArea'
                    ]
                ]
            ),
            'count' => $repo->fetchCount($query)
        ];
    }
}
