<?php

namespace Dvsa\OlcsTest\Api\Service\Nr;

use Dvsa\Olcs\Api\Service\Nr\MsiResponseFactory;
use Dvsa\Olcs\Api\Service\Nr\MsiResponse;
use Interop\Container\ContainerInterface;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use Mockery as m;
use Olcs\XmlTools\Xml\XmlNodeBuilder;

/**
 * Class MsiResponseFactoryTest
 * @package Dvsa\OlcsTest\Api\Service\Nr
 * @author Ian Lindsay <ian@hemera-business-services.co.uk>
 */
class MsiResponseFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $config = [
            'nr' => [
                'compliance_episode' => [
                    'xmlNs' => 'xml ns'
                ],
            ],
        ];

        $mockSl = m::mock(ContainerInterface::class);
        $mockSl->shouldReceive('get')->with('Config')->once()->andReturn($config);

        $sut = new MsiResponseFactory();
        $service = $sut->__invoke($mockSl, MsiResponse::class);

        $this->assertInstanceOf(MsiResponse::class, $service);
        $this->assertInstanceOf(XmlNodeBuilder::class, $service->getXmlBuilder());
    }

    public function testInvokeMissingConfig()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No config specified for xml ns');

        $mockSl = m::mock(ContainerInterface::class);
        $mockSl->shouldReceive('get')->with('Config')->once()->andReturn([]);
        $sut = new MsiResponseFactory();
        $sut->__invoke($mockSl, MsiResponse::class);
    }
}
