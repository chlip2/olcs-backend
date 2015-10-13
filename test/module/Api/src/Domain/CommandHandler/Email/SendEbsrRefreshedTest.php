<?php

/**
 * Send Ebsr Refreshed Email Test
 *
 * @author Craig R <uk@valtech.co.uk>
 */
namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Email;

use Mockery as m;
use Doctrine\ORM\Query;

/**
 * Send Ebsr Refreshed Email Test
 *
 * @author Craig R <uk@valtech.co.uk>
 */
class SendEbsrRefreshedTest extends SendEbsrEmailTestAbstract
{
    protected $template = 'ebsr-refreshed';
    protected $sutClass = '\Dvsa\Olcs\Api\Domain\CommandHandler\Email\SendEbsrRefreshed';
}
