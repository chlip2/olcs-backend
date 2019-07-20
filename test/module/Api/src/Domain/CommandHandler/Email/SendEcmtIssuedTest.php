<?php

namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Email;

use Dvsa\Olcs\Api\Domain\Command\Email\SendEcmtIssued as SendEcmtIssuedCmd;
use Dvsa\Olcs\Api\Domain\CommandHandler\Email\SendEcmtIssued as SendEcmtIssuedHandler;

/**
 * Test the permit app issued email
 */
class SendEcmtIssuedTest extends AbstractEcmtAnnualPermitTest
{
    protected $commandClass = SendEcmtIssuedCmd::class;
    protected $commandHandlerClass = SendEcmtIssuedHandler::class;
    protected $template = 'ecmt-app-issued';
    protected $subject = 'email.ecmt.issued.subject';
    protected $permitApplicationRepo = 'EcmtPermitApplication';
    protected $applicationEntityClass = EcmtPermitApplication::class;
}
