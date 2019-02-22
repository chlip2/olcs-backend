<?php

namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Surrender;

use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\Command\Surrender\Clear as ClearCommand;
use Dvsa\Olcs\Api\Domain\CommandHandler\Surrender\Clear as ClearHandler;
use Dvsa\Olcs\Api\Domain\Repository\Surrender as SurrenderRepo;
use Dvsa\Olcs\Api\Entity\Surrender as SurrenderEntity;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;
use Mockery as m;
use ZfcRbac\Service\AuthorizationService;

class ClearTest extends CommandHandlerTestCase
{
    /**
     * @var WithdrawHandler
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new ClearHandler();
        $this->mockRepo('Surrender', SurrenderRepo::class);
        $this->mockedSmServices = [
            AuthorizationService::class => m::mock(AuthorizationService::class)
        ];
        parent::setUp();
    }

    public function testHandleCommand()
    {
        $surrenderEntity = m::mock(SurrenderEntity::class);
        $surrenderEntity->shouldReceive('getId')->andReturn(1);

        foreach ($this->surrenderProperties() as $property => $value) {
            $method = 'set' . $property;
            $surrenderEntity->shouldReceive($method)->with($value)->once();
        }

        $this->repoMap['Surrender']
            ->shouldReceive('fetchOneByLicenceId')
            ->once()
            ->andReturn($surrenderEntity);


        $this->repoMap['Surrender']
            ->shouldReceive('save')
            ->with(m::type(SurrenderEntity::class))
            ->once();

        $command = ClearCommand::create(['id' => 111]);
        $result = $this->sut->handleCommand($command);

        $this->assertSame(['Surrender data successfully cleared'], $result->getMessages());
        $this->assertInstanceOf(Result::class, $result);
    }

    protected function surrenderProperties()
    {
        return [
            'CommunityLicenceDocumentInfo' => null,
            'CommunityLicenceDocumentStatus' => null,
            'DigitalSignature' => null,
            'DiscDestroyed' => null,
            'DiscLost' => null,
            'DiscLostInfo' => null,
            'DiscStolen' => null,
            'DiscStolenInfo' => null,
            'LicenceDocumentInfo' => null,
            'LicenceDocumentStatus' => null,
            'SignatureType' => null
        ];
    }
}
