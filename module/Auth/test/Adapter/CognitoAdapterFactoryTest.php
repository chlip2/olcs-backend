<?php
declare(strict_types = 1);

namespace Dvsa\Olcs\Auth\Test\Adapter;

use Dvsa\Authentication\Cognito\Client;
use Dvsa\Olcs\Auth\Adapter\CognitoAdapter;
use Dvsa\Olcs\Auth\Adapter\CognitoAdapterFactory;
use Laminas\ServiceManager\ServiceManager;
use Mockery as m;
use Olcs\TestHelpers\MockeryTestCase;
use Olcs\TestHelpers\Service\MocksServicesTrait;

class CognitoAdapterFactoryTest extends MockeryTestCase
{
    use MocksServicesTrait;

    /**
     * @var CognitoAdapterFactory
     */
    protected $sut;

    public function setUp(): void
    {
        $this->setUpServiceManager();
    }

    /**
     * @test
     */
    public function __invoke_IsCallable(): void
    {
        // Setup
        $this->setUpSut();

        // Assert
        $this->assertIsCallable([$this->sut, '__invoke']);
    }

    /**
     * @test
     * @depends __invoke_IsCallable
     */
    public function __invoke_ReturnsAnInstanceOfCognitoAdapter()
    {
        // Setup
        $this->setUpSut();

        // Execute
        $result = $this->sut->__invoke($this->serviceManager(), null);

        // Assert
        $this->assertInstanceOf(CognitoAdapter::class, $result);
    }

    protected function setUpSut(): void
    {
        $this->sut = new CognitoAdapterFactory();
    }

    /**
     * @param ServiceManager $serviceManager
     */
    protected function setUpDefaultServices(ServiceManager $serviceManager)
    {
        $serviceManager->setService(Client::class, m::mock(Client::class));
    }
}
