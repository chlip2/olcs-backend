<?php

/**
 * Delete Conviction
 */

namespace Dvsa\Olcs\Api\Domain\CommandHandler\Cases\Prohibition;

use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractDeleteCommandHandler;
use Dvsa\Olcs\Api\Entity as Entities;
use Doctrine\ORM\Query;

/**
 * Delete Conviction
 */
final class Delete extends AbstractDeleteCommandHandler
{
    protected $repoServiceName = 'Prohibition';
}
