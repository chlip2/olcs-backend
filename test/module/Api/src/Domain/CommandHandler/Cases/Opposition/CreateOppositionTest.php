<?php

/**
 * Create Opposition Test
 *
 * @author Shaun Lizzio <shaun@lizzio.co.uk>
 */
namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Cases\Opposition;

use Doctrine\ORM\Query;
use Mockery as m;
use Dvsa\Olcs\Api\Domain\CommandHandler\Cases\Opposition\CreateOpposition;
use Dvsa\Olcs\Api\Domain\Repository\Opposition as OppositionRepo;
use Dvsa\Olcs\Api\Domain\Repository\ContactDetails as ContactDetailsRepo;
use Dvsa\Olcs\Api\Domain\Repository\Cases as CasesRepo;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;
use Dvsa\Olcs\Transfer\Command\Cases\Opposition\CreateOpposition as Cmd;
use Dvsa\Olcs\Api\Entity\Opposition\Opposition as OppositionEntity;
use Dvsa\Olcs\Api\Entity\Cases\Cases as CasesEntity;
use Dvsa\Olcs\Api\Entity\ContactDetails\Country as CountryEntity;
use Dvsa\Olcs\Api\Entity\System\RefData as RefDataEntity;
use Dvsa\Olcs\Api\Entity\Licence\Licence as LicenceEntity;

/**
 * Create Opposition Test
 *
 * @author Shaun Lizzio <shaun@lizzio.co.uk>
 */
class CreateOppositionTest extends CommandHandlerTestCase
{
    public function setUp()
    {
        $this->sut = new CreateOpposition();
        $this->mockRepo('Opposition', OppositionRepo::class);
        $this->mockRepo('ContactDetails', ContactDetailsRepo::class);
        $this->mockRepo('Cases', CasesRepo::class);

        parent::setUp();
    }

    private function getPayload()
    {
        return [
            'case' => 24,
            "oppositionType" => "otf_eob",
            "contactDetailsDescription" => "CD notes",
            "raisedDate" => "2015-05-04",
            "opposerType" => "obj_t_police",
            "isValid" => "opp_v_yes",
            "validNotes" => "Notes",
            "isCopied" => "Y",
            "isWillingToAttendPi" => "N",
            "isInTime" => "Y",
            "isWithdrawn" => "N",
            "status" => "opp_ack",
            "licenceOperatingCentres" => [
                "16"
            ],
            "applicationOperatingCentres" => [],
            "grounds" => [
                "ogf_env",
                "ogf_parking"
            ],
            "isPublicInquiry" => "Y",
            "notes" => "Notes",
            "operatingCentres" => null,
            "opposerContactDetails" => [
                "emailAddress" => "bobED@jones.com",
                "person" => [
                    "forename" => "Bob",
                    "familyName" => "Jones"
                ],
                "address" => [
                    "addressLine1" => "Unit 5ED",
                    "addressLine2" => "12 Albert Street",
                    "addressLine3" => "Westpoint",
                    "addressLine4" => "",
                    "countryCode" => "GB",
                    "postcode" => "LS9 6NA",
                    "town" => "Leeds"
                ],
                "phoneContacts" => [
                    [
                        "phoneNumber" => "5525225",
                        "phoneContactType" => "phone_t_tel"
                    ]
                ]
            ]
        ];
    }

    private function getReferencedPayload()
    {
        return [
            'case' => 24,
            "oppositionType" => "otf_eob",
            "contactDetailsDescription" => "CD notes",
            "raisedDate" => "2015-05-04",
            "opposerType" => "obj_t_police",
            "isValid" => "opp_v_yes",
            "validNotes" => "Notes",
            "isCopied" => "Y",
            "isWillingToAttendPi" => "N",
            "isInTime" => "Y",
            "isWithdrawn" => "N",
            "status" => "opp_ack",
            "licenceOperatingCentres" => [
                "16"
            ],
            "applicationOperatingCentres" => [],
            "grounds" => [
                "ogf_env",
                "ogf_parking"
            ],
            "isPublicInquiry" => "Y",
            "notes" => "Notes",
            "operatingCentres" => null,
            "opposerContactDetails" => [
                "emailAddress" => "bobED@jones.com",
                "person" => [
                    "forename" => "Bob",
                    "familyName" => "Jones"
                ],
                "address" => [
                    "addressLine1" => "Unit 5ED",
                    "addressLine2" => "12 Albert Street",
                    "addressLine3" => "Westpoint",
                    "addressLine4" => "",
                    "countryCode" => new CountryEntity(),
                    "postcode" => "LS9 6NA",
                    "town" => "Leeds"
                ],
                "phoneContacts" => [
                    [
                        "phoneNumber" => "5525225",
                        "phoneContactType" => new RefDataEntity()
                    ]
                ]
            ]
        ];
    }

    protected function initReferences()
    {
        $this->refData = [
            'ct_obj',
            'otf_eob',
            'obj_t_police',
            'opp_v_yes',
            'opp_ack',
            'ogf_env',
            'phone_t_tel'
        ];

        $this->references = [
            CasesEntity::class => [
                24 => m::mock(CasesEntity::class)
            ]
        ];

        parent::initReferences();
    }

    public function testHandleCommand()
    {
        $payload = $this->getPayload();
        $referencedPayload = $this->getReferencedPayload();

        $command = Cmd::create(
            $payload
        );

        $this->repoMap['ContactDetails']
            ->shouldReceive('populateRefDataReference')
            ->with($payload['opposerContactDetails'])
            ->once()
            ->andReturn(
                $referencedPayload['opposerContactDetails']
            );

        $mockLicence = m::mock(LicenceEntity::class)->makePartial();

        $mockCase = m::mock(CasesEntity::class)->makePartial();
        $mockCase->setLicence($mockLicence);

        $this->repoMap['Cases']
            ->shouldReceive('fetchById')
            ->with($payload['case'], m::type('integer'))
            ->once()
            ->andReturn($mockCase);

        /** @var OppositionEntity $opp */
        $opp = null;

        $this->repoMap['Opposition']
            ->shouldReceive('generateRefdataArrayCollection')
            ->shouldReceive('save')
            ->with(m::type(OppositionEntity::class))
            ->andReturnUsing(
                function (OppositionEntity $opposition) use (&$opp) {
                    $opp = $opposition;
                    $opposition->setId(99);
                }
            )
            ->once();

        $result = $this->sut->handleCommand($command);

        $this->assertInstanceOf('Dvsa\Olcs\Api\Domain\Command\Result', $result);
        $this->assertObjectHasAttribute('ids', $result);
        $this->assertObjectHasAttribute('messages', $result);
        $this->assertContains('Opposition created', $result->getMessages());
    }
}
