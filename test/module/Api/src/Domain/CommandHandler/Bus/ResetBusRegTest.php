<?php

/**
 * Reset BusReg Test
 */

namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Bus;

use Doctrine\ORM\Query;
use Mockery as m;
use Dvsa\Olcs\Api\Domain\CommandHandler\Bus\ResetBusReg;
use Dvsa\Olcs\Api\Domain\Repository\Bus as BusRepo;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;
use Dvsa\Olcs\Transfer\Command\Bus\ResetBusReg as Cmd;
use Dvsa\Olcs\Api\Entity\Bus\BusReg as BusEntity;
use Dvsa\Olcs\Api\Domain\Command\Result;

/**
 * Reset BusReg Test
 */
class ResetBusRegTest extends CommandHandlerTestCase
{
    public function setUp(): void
    {
        $this->sut = new ResetBusReg();
        $this->mockRepo('Bus', BusRepo::class);

        parent::setUp();
    }

    /**
     * testHandleCommand
     */
    public function testHandleCommand()
    {
        $id = 99;

        $command = Cmd::Create(
            [
                'id' => $id,
            ]
        );

        /** @var BusEntity $busReg */
        $busReg = m::mock(BusEntity::class);
        $busReg->shouldReceive('resetStatus')
            ->once()
            ->shouldReceive('getId')
            ->andReturn($id);

        $this->repoMap['Bus']->shouldReceive('fetchUsingId')
            ->with($command, Query::HYDRATE_OBJECT)
            ->andReturn($busReg)
            ->shouldReceive('save')
            ->with(m::type(BusEntity::class))
            ->once();

        $result = $this->sut->handleCommand($command);

        $this->assertInstanceOf(Result::class, $result);
    }
}
