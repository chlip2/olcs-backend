<?php

/**
 * VI Operator view test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Dvsa\OlcsTest\Api\Domain\Repository;

use Mockery as m;
use Dvsa\Olcs\Api\Domain\Repository\ViOpView as ViOpViewRepo;
use Dvsa\Olcs\Transfer\Query\QueryInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Query;

/**
 * VI Operator view test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class ViOpViewTest extends RepositoryTestCase
{
    public function setUp()
    {
        $this->setUpSut(ViOpViewRepo::class);
    }

    public function testFetchDiscsToPrint()
    {
        $mockQb = m::mock(QueryBuilder::class)
            ->shouldReceive('select')
            ->with('m.viLine as line')
            ->andReturnSelf()
            ->once()
            ->shouldReceive('getQuery')
            ->andReturn(
                m::mock()
                ->shouldReceive('getResult')
                ->with(Query::HYDRATE_ARRAY)
                ->once()
                ->andReturn(['result'])
                ->getMock()
            )
            ->getMock();

        $this->em
            ->shouldReceive('getRepository->createQueryBuilder')
            ->once()
            ->andReturn($mockQb);

        $this->assertEquals(['result'], $this->sut->fetchForExport());
    }
}
