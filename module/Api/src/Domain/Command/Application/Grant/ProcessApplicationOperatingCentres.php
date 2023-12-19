<?php

/**
 * Process Application Operating Centres
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */

namespace Dvsa\Olcs\Api\Domain\Command\Application\Grant;

use Dvsa\Olcs\Transfer\Command\AbstractCommand;
use Dvsa\Olcs\Transfer\FieldType\Traits\Identity;

/**
 * Process Application Operating Centres
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
final class ProcessApplicationOperatingCentres extends AbstractCommand
{
    use Identity;
}
