<?php

/**
 * Grant Psv Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Application;

use Dvsa\Olcs\Api\Domain\Command\Application\CreateGrantFee;
use Dvsa\Olcs\Api\Domain\Command\Application\Grant\CommonGrant;
use Dvsa\Olcs\Api\Domain\Command\Application\Grant\CopyApplicationDataToLicence;
use Dvsa\Olcs\Api\Domain\Command\Application\Grant\CreateDiscRecords;
use Dvsa\Olcs\Api\Domain\Command\Application\Grant\ProcessApplicationOperatingCentres;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\Application\GrantPsv;
use Dvsa\Olcs\Api\Entity\Application\Application as ApplicationEntity;
use Dvsa\Olcs\Api\Entity\Licence\Licence;
use Dvsa\Olcs\Transfer\Command\Application\CreateSnapshot;
use Mockery as m;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;
use Dvsa\Olcs\Api\Domain\Command\Application\GrantPsv as Cmd;

/**
 * Grant Psv Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class GrantPsvTest extends CommandHandlerTestCase
{
    public function setUp()
    {
        $this->sut = new GrantPsv();
        $this->mockRepo('Application', \Dvsa\Olcs\Api\Domain\Repository\Application::class);

        parent::setUp();
    }

    protected function initReferences()
    {
        $this->refData = [
            Licence::LICENCE_STATUS_VALID,
            ApplicationEntity::APPLICATION_STATUS_VALID
        ];
        $this->references = [];

        parent::initReferences();
    }

    public function testHandleCommand()
    {
        $data = [
            'id' => 111
        ];

        $command = Cmd::create($data);

        /** @var Licence $licence */
        $licence = m::mock(Licence::class)->makePartial();

        /** @var ApplicationEntity $application */
        $application = m::mock(ApplicationEntity::class)->makePartial();
        $application->setId(111);
        $application->setLicence($licence);
        $application->shouldReceive('isSpecialRestricted')
            ->andReturn(false);

        $this->repoMap['Application']->shouldReceive('fetchUsingId')
            ->with($command)
            ->andReturn($application)
            ->shouldReceive('save')
            ->once()
            ->with($application);

        $result1 = new Result();
        $result1->addMessage('CreateSnapshot');
        $this->expectedSideEffect(CreateSnapshot::class, ['id' => 111, 'event' => CreateSnapshot::ON_GRANT], $result1);

        $result2 = new Result();
        $result2->addMessage('CopyApplicationDataToLicence');
        $this->expectedSideEffect(CopyApplicationDataToLicence::class, $data, $result2);

        $result3 = new Result();
        $result3->addMessage('CreateDiscRecords');
        $this->expectedSideEffect(CreateDiscRecords::class, $data, $result3);

        $result4 = new Result();
        $result4->addMessage('ProcessApplicationOperatingCentres');
        $this->expectedSideEffect(ProcessApplicationOperatingCentres::class, $data, $result4);

        $result5 = new Result();
        $result5->addMessage('CommonGrant');
        $this->expectedSideEffect(CommonGrant::class, $data, $result5);

        $result = $this->sut->handleCommand($command);

        $expected = [
            'id' => [],
            'messages' => [
                'CreateSnapshot',
                'Application status updated',
                'Licence status updated',
                'CopyApplicationDataToLicence',
                'CreateDiscRecords',
                'ProcessApplicationOperatingCentres',
                'CommonGrant'
            ]
        ];

        $this->assertEquals($expected, $result->toArray());

        $this->assertEquals(Licence::LICENCE_STATUS_VALID, $licence->getStatus()->getId());
        $this->assertEquals(ApplicationEntity::APPLICATION_STATUS_VALID, $application->getStatus()->getId());
    }
}
