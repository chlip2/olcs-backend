<?php

/**
 * ResetToValid.php
 *
 * @author Joshua Curtis <josh.curtis@valtech.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler\Licence;

use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler\TransactionedInterface;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Entity\Licence\Licence;

/**
 * Reset a licence to valid.
 *
 * @author Joshua Curtis <josh.curtis@valtech.co.uk>
 */
final class ResetToValid extends AbstractCommandHandler implements TransactionedInterface
{
    protected $repoServiceName = 'Licence';

    public function handleCommand(CommandInterface $command)
    {
        $licence = $this->getRepo()->fetchById($command->getId());

        $licence->setStatus(
            $this->getRepo()->getRefdataReference(Licence::LICENCE_STATUS_VALID)
        );
        $licence->setRevokedDate(null);
        $licence->setCurtailedDate(null);
        $licence->setSuspendedDate(null);

        $this->getRepo()->save($licence);

        $result = new Result();
        $result->addMessage("Licence ID {$licence->getId()} reset to valid");

        return $result;
    }
}
