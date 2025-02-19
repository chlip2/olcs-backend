<?php

namespace Dvsa\OlcsTest\DocumentShare\Service;

use Dvsa\Olcs\Api\Entity\User\User;
use Dvsa\Olcs\Api\Service\Toggle\ToggleService;
use Dvsa\Olcs\DocumentShare\Service\ClientFactory;
use Dvsa\Olcs\DocumentShare\Service\DocManClient;
use Dvsa\Olcs\DocumentShare\Service\WebDavClient;
use Psr\Container\ContainerInterface;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Log\LoggerInterface;
use LmcRbacMvc\Identity\IdentityInterface;
use LmcRbacMvc\Service\AuthorizationService;

/**
 * Client Factory Test
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
class ClientFactoryTest extends MockeryTestCase
{
    /**
     * @dataProvider provideSetOptions
     *
     * @param $config
     * @param $expected
     */
    public function testGetOptions($config, $expected)
    {
        $mockSl = m::mock(ContainerInterface::class);
        $mockSl->shouldReceive('get')->once()->with('Configuration')->andReturn($config);

        $sut = new ClientFactory();

        if ($expected instanceof \Exception) {
            $passed = false;
            try {
                $sut->getOptions($mockSl, 'testkey');
            } catch (\Exception $e) {
                if ($e->getMessage() == $expected->getMessage() && $e::class == $expected::class) {
                    $passed = true;
                }
            }

            $this->assertTrue($passed, 'Expected exception not thrown or message didn\'t match expected value');
        } else {
            $data = $sut->getOptions($mockSl, 'testkey');
            $this->assertEquals($expected, $data);
        }
    }

    public function provideSetOptions()
    {
        return [
            [[], new \RuntimeException('Options could not be found in "document_share.testkey".')],
            [
                ['document_share' => []],
                new \RuntimeException('Options could not be found in "document_share.testkey".')
            ],
            [
                ['document_share' => ['testkey' => ['foo' => 'bar']]],
                ['foo' => 'bar']
            ]
        ];
    }

    public function testEnforceWebDav(): void
    {
        $sut = new ClientFactory();

        $serviceManager = m::mock(ContainerInterface::class);

        $toggleService = m::mock(ToggleService::class);
        $logger = m::mock(LoggerInterface::class);
        $user = m::mock(User::class);
        $identity = m::mock(IdentityInterface::class);
        $authService = m::mock(AuthorizationService::class);

        $toggleService->shouldReceive('isEnabled')->with('enforce_webdav')->andReturn(true);
        $user->shouldReceive('getOstype')->andReturn(User::USER_OS_TYPE_WINDOWS_7);
        $identity->shouldReceive('getUser')->andReturn($user);
        $authService->shouldReceive('getIdentity')->andReturn($identity);

        $serviceManager->shouldReceive('get')->with('Logger')->andReturn($logger);
        $serviceManager->shouldReceive('get')->with(ToggleService::class)->andReturn($toggleService);
        $serviceManager->shouldReceive('get')->with(AuthorizationService::class)->andReturn($authService);

        $webDavConfig = [
            'document_share' => [
                'http' => [],
                'client' => [
                    'baseuri' => 'http://testdocument_share',
                    'workspace' => 'testwebdav',
                    'username' => 'testwebdav',
                    'password' => 'ttestwebdavest',
                    'webdav_baseuri' => 'http://testdocument_share',
                    'uuid' => 'u1234'
                ]
            ]
        ];

        $serviceManager->shouldReceive('get')->once()->with('Configuration')->andReturn($webDavConfig);

        $service = $sut->__invoke($serviceManager, null);

        $this->assertInstanceOf(WebDavClient::class, $service);
    }

    /**
     * @dataProvider provideCreateService
     *
     * @param $config
     * @param $expected
     */
    public function testCreateService($config, $expected, $client)
    {
        $sut = new ClientFactory();

        $mockSl = m::mock(ContainerInterface::class);

        $toggleService = m::mock(ToggleService::class);
        $toggleService->shouldReceive('isEnabled')->with('enforce_webdav')->andReturn(false);
        $mockSl->shouldReceive('get')->once()->with(ToggleService::class)->andReturn($toggleService);

        $mockLogger = m::mock(LoggerInterface::class);
        $mockUser = m::mock(User::class)
            ->shouldReceive('getOstype')
            ->andReturn($client)->getMock();

        if ($client === User::USER_OS_TYPE_WINDOWS_7) {
            $mockLogger->shouldReceive('info')->once();
            $mockUser->shouldReceive('getId')->once();
        }

        $mockSl->shouldReceive('get')->once()->with('Logger')->andReturn($mockLogger);
        $authService = m::mock(AuthorizationService::class)
            ->shouldReceive('getIdentity')->once()
            ->andReturn(
                m::mock(IdentityInterface::class)->shouldReceive('getUser')->once()
                    ->andReturn($mockUser)->getMock()
            )->getMock();

        $mockSl->shouldReceive('get')
            ->once()
            ->with(AuthorizationService::class)
            ->andReturn(
                $authService
            )->getMock();
        $mockSl->shouldReceive('get')->once()->with('Configuration')->andReturn($config);
        if ($expected instanceof \Exception) {
            $passed = false;
            try {
                $sut->__invoke($mockSl, null);
            } catch (\Exception $e) {
                if ($e->getMessage() === $expected->getMessage() && $e::class === $expected::class) {
                    $passed = true;
                }
            }

            $this->assertTrue($passed, 'Expected exception not thrown or message didn\'t match expected value');
        } else {
            $service = $sut->__invoke($mockSl, null);

            if ($client === User::USER_OS_TYPE_WINDOWS_7) {
                $this->assertInstanceOf(DocManClient::class, $service);
                $this->assertInstanceOf(\Laminas\Http\Client::class, $service->getHttpClient());
                $this->assertEquals($config['document_share']['client']['workspace'], $service->getWorkspace());
                $this->assertEquals($config['document_share']['client']['baseuri'], $service->getBaseUri());
                if (isset($config['document_share']['client']['uuid'])) {
                    $this->assertEquals(
                        $config['document_share']['client']['uuid'],
                        $service->getUuid()
                    );
                }
            } else {
                $this->assertInstanceOf(WebDavClient::class, $service);
            }
        }
    }

    public function provideCreateService()
    {
        $configMissingBaseUri = [
            'document_share' => [
                'http' => [],
                'client' => [
                    'workspace' => 'test'
                ]
            ],
        ];

        $configMissingWorkspace = [
            'document_share' => [
                'http' => [],
                'client' => [
                    'baseuri' => 'http://testdocument_share'
                ]
            ],
        ];

        $config = [
            'document_share' => [
                'http' => [],
                'client' => [
                    'baseuri' => 'http://testdocument_share',
                    'workspace' => 'test'
                ]
            ],
        ];

        $configWebDavMissingUsername = [
            'document_share' => [
                'http' => [],
                'client' => [
                    'webdav_baseuri' => 'http://testdocument_share',
                    'workspace' => 'test',
                    'password' => 'test'
                ]
            ]
        ];

        $configWebDavMissingPassword = [
            'document_share' => [
                'http' => [],
                'client' => [
                    'webdav_baseuri' => 'http://testdocument_share',
                    'workspace' => 'test',
                    'username' => 'test'
                ]
            ]
        ];

        $webDavConfig = [
            'document_share' => [
                'http' => [],
                'client' => [
                    'baseuri' => 'http://testdocument_share',
                    'workspace' => 'testwebdav',
                    'username' => 'testwebdav',
                    'password' => 'ttestwebdavest',
                    'webdav_baseuri' => 'http://testdocument_share',
                    'uuid' => 'u1234'
                ]
            ]
        ];

        $webDavConfigMissingBaseUri = [
            'document_share' => [
                'http' => [],
                'client' => [
                    'workspace' => 'testwebdav',
                    'username' => 'testwebdav',
                    'password' => 'ttestwebdavest',
                    'uuid' => 'u1234'
                ]
            ]
        ];

        $webDavConfigMissingWorkspace = [
            'document_share' => [
                'http' => [],
                'client' => [
                    'username' => 'testwebdav',
                    'password' => 'ttestwebdavest',
                    'webdav_baseuri' => 'http://testdocument_share',
                    'uuid' => 'u1234'
                ]
            ]
        ];

        $configWithUuid = [
            'document_share' => [
                'http' => [],
                'client' => [
                    'baseuri' => 'http://testdocument_share',
                    'workspace' => 'test',
                    'uuid' => 'u1234'
                ]
            ],
        ];

        return [
            "missingBaseUri" => [
                $configMissingBaseUri,
                new \RuntimeException('Missing required option document_share.client.baseuri'),
                User::USER_OS_TYPE_WINDOWS_7
            ],
            "missingWorkspace" => [
                $configMissingWorkspace,
                new \RuntimeException('Missing required option document_share.client.workspace'),
                User::USER_OS_TYPE_WINDOWS_7
            ],
            "goodDocManConfig" => [
                $config,
                null,
                User::USER_OS_TYPE_WINDOWS_7
            ],
            "docManwithUuId" => [
                $configWithUuid,
                null,
                User::USER_OS_TYPE_WINDOWS_7
            ],
            "goodWebDavConfig" => [
                $webDavConfig,
                null,
                User::USER_OS_TYPE_WINDOWS_10
            ],

            "WebDavConfigMissingUsername" => [
                $configWebDavMissingUsername,
                new \RuntimeException('Missing required option document_share.client.username'),
                User::USER_OS_TYPE_WINDOWS_10

            ],
            "WebDavConfigMissingPassword" => [
                $configWebDavMissingPassword,
                new \RuntimeException('Missing required option document_share.client.password'),
                User::USER_OS_TYPE_WINDOWS_10

            ],
            "WebDavConfigMissingWorkspace" => [
                $webDavConfigMissingWorkspace,
                new \RuntimeException('Missing required option document_share.client.workspace'),
                User::USER_OS_TYPE_WINDOWS_10

            ],
            "WebDavConfigMissingPWebDavBaseUri" => [
                $webDavConfigMissingBaseUri,
                new \RuntimeException('Missing required option document_share.client.webdav_baseuri'),
                User::USER_OS_TYPE_WINDOWS_10

            ]

        ];
    }
}
