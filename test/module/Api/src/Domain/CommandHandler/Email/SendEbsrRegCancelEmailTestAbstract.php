<?php

/**
 * Abstract for testing ebsr registered and cancelled emails
 */
namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Email;

use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Email\Domain\Command\SendEmail;
use Dvsa\Olcs\Api\Domain\Repository\EbsrSubmission as EbsrSubmissionRepo;
use Dvsa\Olcs\Api\Domain\Repository\BusRegSearchView as BusRegSearchViewRepo;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;
use Dvsa\Olcs\Email\Service\TemplateRenderer;
use Mockery as m;
use Dvsa\Olcs\Api\Entity\Ebsr\EbsrSubmission as EbsrSubmissionEntity;
use Dvsa\Olcs\Api\Entity\Bus\BusReg as BusRegEntity;
use Dvsa\Olcs\Api\Entity\Publication\PublicationSection as PublicationSectionEntity;
use Dvsa\Olcs\Api\Entity\View\BusRegSearchView as BusRegSearchViewEntity;
use Doctrine\ORM\Query;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Dvsa\Olcs\Api\Domain\CommandHandler\Email\SendEbsrAbstract;

/**
 * Abstract for testing ebsr registered and cancelled emails
 */
abstract class SendEbsrRegCancelEmailTestAbstract extends CommandHandlerTestCase
{
    protected $template = null;
    protected $sutClass = null;
    protected $cmdClass = null;

    /**
     * @var CommandInterface
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new $this->sutClass();
        $this->mockRepo('EbsrSubmission', EbsrSubmissionRepo::class);
        $this->mockRepo('BusRegSearchView', BusRegSearchViewRepo::class);

        $this->mockedSmServices = [
            TemplateRenderer::class => m::mock(TemplateRenderer::class),
        ];

        $this->references = [
            PublicationSectionEntity::class => [
                26 => m::mock(PublicationSectionEntity::class)
            ],
        ];

        parent::setUp();
    }

    /**
     * @dataProvider handleCommandProvider
     *
     * @param string $cmdClass
     */
    public function testHandleCommand($cmdClass)
    {
        $ebsrSubmissionId = 1234;
        $regNo = 5678;
        $busRegId = 12;
        $startPoint = 'start point';
        $endPoint = 'end point';
        $serviceNumbers = '99999 (12345, 567910)';
        $orgEmail = 'foo@bar.com';
        $publicationInfo = 'publicationInfo';

        $submittedDate = '2015-01-15';
        $formattedSubmittedDate = date(SendEbsrAbstract::DATE_FORMAT, strtotime($submittedDate));

        $effectiveDate = new \DateTime('2015-01-16 00:00:00');
        $formattedEffectiveDate = $effectiveDate->format(SendEbsrAbstract::DATE_FORMAT);

        $command = $cmdClass::create(['id' => $ebsrSubmissionId]);

        $busRegSearchViewEntity = m::mock(BusRegSearchViewEntity::class);
        $busRegSearchViewEntity->shouldReceive('getServiceNo')->once()->andReturn($serviceNumbers);

        $busRegEntity = m::mock(BusRegEntity::class);
        $busRegEntity->shouldReceive('getId')->once()->andReturn($busRegId);
        $busRegEntity->shouldReceive('getRegNo')->once()->andReturn($regNo);
        $busRegEntity->shouldReceive('getStartPoint')->once()->andReturn($startPoint);
        $busRegEntity->shouldReceive('getFinishPoint')->once()->andReturn($endPoint);
        $busRegEntity->shouldReceive('getEffectiveDate')->once()->andReturn($effectiveDate);
        $busRegEntity->shouldReceive('getLicence->getTranslateToWelsh')->once()->andReturn(false);
        $busRegEntity->shouldReceive('getLocalAuthoritys')->once()->andReturn(new ArrayCollection());
        $busRegEntity->shouldReceive('getPublicationSectionForGrantEmail')->once()->andReturn(26);
        $busRegEntity->shouldReceive('getPublicationLinksForGrantEmail')->once()->andReturn($publicationInfo);

        $ebsrSubmissionEntity = m::mock(EbsrSubmissionEntity::class);
        $ebsrSubmissionEntity->shouldReceive('getId')->andReturn($ebsrSubmissionId);
        $ebsrSubmissionEntity->shouldReceive('getSubmittedDate')->andReturn($submittedDate);
        $ebsrSubmissionEntity->shouldReceive('getOrganisationEmailAddress')->once()->andReturn($orgEmail);
        $ebsrSubmissionEntity->shouldReceive('getBusReg')->once()->andReturn($busRegEntity);

        $this->repoMap['EbsrSubmission']
            ->shouldReceive('fetchUsingId')
            ->with(m::type($cmdClass), Query::HYDRATE_OBJECT, null)
            ->once()
            ->andReturn($ebsrSubmissionEntity);

        $this->repoMap['BusRegSearchView']
            ->shouldReceive('fetchById')
            ->with($busRegId)
            ->once()
            ->andReturn($busRegSearchViewEntity);

        $this->mockedSmServices[TemplateRenderer::class]->shouldReceive('renderBody')->with(
            m::type(\Dvsa\Olcs\Email\Data\Message::class),
            $this->template,
            [
                'submissionDate' => $formattedSubmittedDate,
                'registrationNumber' => $regNo,
                'origin' => $startPoint,
                'destination' => $endPoint,
                'lineName' => $serviceNumbers,
                'startDate' => $formattedEffectiveDate,
                'localAuthoritys' => '',
                'publicationId' => $publicationInfo,
            ],
            null
        );

        $result = new Result();
        $data = [
            'to' => $orgEmail,
            'locale' => 'en_GB',
            'subject' => 'email.' . $this->template . '.subject'
        ];

        $this->expectedSideEffect(SendEmail::class, $data, $result);

        $result = $this->sut->handleCommand($command);

        $this->assertSame(['ebsrSubmission' => $ebsrSubmissionId], $result->getIds());
        $this->assertSame(['Email sent'], $result->getMessages());
    }

    public function handleCommandProvider()
    {
        return [
            [$this->cmdClass],
            [$this->cmdClass]
        ];
    }
}