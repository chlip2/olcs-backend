<?php

/**
 * Create DeclareUnfit Test
 */
namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\TmCaseDecision;

use Mockery as m;
use Dvsa\Olcs\Api\Domain\CommandHandler\TmCaseDecision\CreateDeclareUnfit;
use Dvsa\Olcs\Api\Domain\Repository\TmCaseDecision;
use Dvsa\Olcs\Api\Entity\Cases\Cases as CasesEntity;
use Dvsa\Olcs\Api\Entity\Tm\TmCaseDecision as TmCaseDecisionEntity;
use Dvsa\Olcs\Transfer\Command\TmCaseDecision\CreateDeclareUnfit as Cmd;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;

/**
 * Create DeclareUnfit Test
 */
class CreateDeclareUnfitTest extends CommandHandlerTestCase
{
    public function setUp()
    {
        $this->sut = new CreateDeclareUnfit();
        $this->mockRepo('TmCaseDecision', TmCaseDecision::class);

        parent::setUp();
    }

    protected function initReferences()
    {
        $this->refData = [
            TmCaseDecisionEntity::DECISION_DECLARE_UNFIT,
            'unfitnessReason',
            'rehabMeasure',
        ];

        $this->references = [
            CasesEntity::class => [
                11 => m::mock(CasesEntity::class)
            ],
        ];

        parent::initReferences();
    }

    public function testHandleCommand()
    {
        $data = [
            'case' => 11,
            'isMsi' => 'Y',
            'decisionDate' => '2016-01-01',
            'notifiedDate' => '2016-01-01',
            'unfitnessStartDate' => '2016-02-01',
            'unfitnessEndDate' => '2016-02-01',
            'unfitnessReasons' => ['unfitnessReason'],
            'rehabMeasures' => ['rehabMeasure'],
        ];

        $command = Cmd::create($data);

        $this->repoMap['TmCaseDecision']->shouldReceive('save')
            ->once()
            ->with(m::type(TmCaseDecisionEntity::class))
            ->andReturnUsing(
                function (TmCaseDecisionEntity $entity) {
                    $entity->setId(111);
                }
            );

        $result = $this->sut->handleCommand($command);

        $expected = [
            'id' => [
                'tmCaseDecision' => 111,
            ],
            'messages' => [
                'Decision created successfully'
            ]
        ];

        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * @expectedException \Dvsa\Olcs\Api\Domain\Exception\ValidationException
     */
    public function testHandleCommandThrowsIncorrectNotifiedDateException()
    {
        $data = [
            'case' => 11,
            'isMsi' => 'Y',
            'decisionDate' => '2016-01-02',
            'notifiedDate' => '2016-01-01',
            'unfitnessStartDate' => '2016-02-01',
            'unfitnessEndDate' => '2016-02-01',
            'unfitnessReasons' => ['unfitnessReason'],
            'rehabMeasures' => ['rehabMeasure'],
        ];

        $command = Cmd::create($data);

        $this->sut->handleCommand($command);
    }

    /**
     * @expectedException \Dvsa\Olcs\Api\Domain\Exception\ValidationException
     */
    public function testHandleCommandThrowsIncorrectUnfitnessEndDateException()
    {
        $data = [
            'case' => 11,
            'isMsi' => 'Y',
            'decisionDate' => '2016-01-01',
            'notifiedDate' => '2016-01-01',
            'unfitnessStartDate' => '2016-02-02',
            'unfitnessEndDate' => '2016-02-01',
            'unfitnessReasons' => ['unfitnessReason'],
            'rehabMeasures' => ['rehabMeasure'],
        ];

        $command = Cmd::create($data);

        $this->sut->handleCommand($command);
    }
}
