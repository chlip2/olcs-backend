<?php

namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Email;

use Dvsa\Olcs\Api\Domain\Command\Email\SendEcmtShortTermAutomaticallyWithdrawn
    as SendEcmtShortTermAutomaticallyWithdrawnCmd;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\Email\SendEcmtShortTermAutomaticallyWithdrawn
    as SendEcmtShortTermAutomaticallyWithdrawnHandler;
use Dvsa\Olcs\Email\Data\Message;
use Dvsa\Olcs\Email\Domain\Command\SendEmail;
use Dvsa\Olcs\Email\Service\TemplateRenderer;
use Mockery as m;

/**
 * Test the short term permit automatically withdrawn email
 */
class SendEcmtShortTermAutomaticallyWithdrawnTest extends AbstractPermitTest
{
    protected $commandClass = SendEcmtShortTermAutomaticallyWithdrawnCmd::class;
    protected $commandHandlerClass = SendEcmtShortTermAutomaticallyWithdrawnHandler::class;
    protected $template = 'ecmt-short-term-automatically-withdrawn';
    protected $subject = 'email.ecmt.short.term.automatically.withdrawn.subject';
    protected $permitApplicationRepo = 'IrhpApplication';
    protected $applicationEntityClass = IrhpApplication::class;

    /**
     * test handle command
     */
    public function testHandleCommand()
    {
        $templateVars = [
            'applicationRef' => $this->applicationRef,
            'paymentDeadlineNumDays' => '10',
        ];

        $this->mockedSmServices[TemplateRenderer::class]->shouldReceive('renderBody')->once()->with(
            m::type(Message::class),
            $this->template,
            $templateVars,
            'default'
        );

        $this->applicationEntity->shouldReceive('getCreatedBy')->once()->withNoArgs()->andReturn($this->userEntity);

        $this->organisation->shouldReceive('getAdminEmailAddresses')->once()->andReturn($this->orgEmails);

        $this->contactDetails->shouldReceive('getEmailAddress')->once()->withNoArgs()->andReturn($this->userEmail);
        $this->userEntity->shouldReceive('getContactDetails')->once()->withNoArgs()->andReturn($this->contactDetails);
        $this->expectedSideEffect(SendEmail::class, $this->data, new Result());

        $result = $this->sut->handleCommand($this->commandEntity);

        $this->assertSame(['IrhpApplication' => $this->permitAppId], $result->getIds());
        $this->assertSame(['Email sent'], $result->getMessages());

        /** @var Message $message */
        $message = $this->sut->getMessage();
        $this->assertSame($this->userEmail, $message->getTo());
        $this->assertSame($this->orgEmails, $message->getCc());
        $this->assertSame($this->subject, $message->getSubject());
    }
}
