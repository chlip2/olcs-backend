<?php

/**
 * Review Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\OlcsTest\Api\Domain\QueryHandler\Application;

use Dvsa\Olcs\Api\Entity\Application\ApplicationCompletion;
use Dvsa\Olcs\Transfer\Query\Application\Application;
use Mockery as m;
use Dvsa\Olcs\Api\Domain\Repository\Application as ApplicationRepo;
use Dvsa\Olcs\Api\Domain\QueryHandler\Application\Review;
use Dvsa\Olcs\Api\Entity\Application\Application as ApplicationEntity;
use Dvsa\OlcsTest\Api\Domain\QueryHandler\QueryHandlerTestCase;
use Dvsa\Olcs\Transfer\Query\Application\Review as Qry;

/**
 * Review Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class ReviewTest extends QueryHandlerTestCase
{
    public function setUp()
    {
        $this->sut = new Review();
        $this->mockRepo('Application', ApplicationRepo::class);

        $this->mockedSmServices['ReviewSnapshot'] = m::mock();

        parent::setUp();
    }

    public function testHandleQuery()
    {
        $query = Qry::create(['id' => 111]);

        /** @var ApplicationEntity $application */
        $application = m::mock(ApplicationEntity::class)->makePartial();

        $this->repoMap['Application']->shouldReceive('fetchUsingId')
            ->with($query)
            ->andReturn($application);

        $this->mockedSmServices['ReviewSnapshot']->shouldReceive('generate')
            ->with($application)
            ->andReturn('<foo>');

        $expected = [
            'markup' => '<foo>'
        ];

        $this->assertEquals($expected, $this->sut->handleQuery($query));
    }
}
