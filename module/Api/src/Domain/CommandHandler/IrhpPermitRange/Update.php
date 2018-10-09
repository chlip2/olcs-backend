<?php

namespace Dvsa\Olcs\Api\Domain\CommandHandler\IrhpPermitRange;

use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitRange as RangeEntity;
use Dvsa\Olcs\Transfer\Command\IrhpPermitRange\Update as UpdateRangeCmd;
use Dvsa\Olcs\Api\Entity\ContactDetails\Country;

/**
 * Update an IRHP Permit Range
 *
 * @author Scott Callaway <scott.callaway@capgemini.com>
 */
final class Update extends AbstractCommandHandler
{
    protected $repoServiceName = 'IrhpPermitRange';
    protected $extraRepos = ['IrhpPermitStock', 'Country'];

    /**
     * Handle command
     *
     * @param UpdateRangeCmd $command command
     *
     * @return Result
     */
    public function handleCommand(CommandInterface $command): Result
    {
        /**
         * @var IrhpPermitRange $command
         * @var RangeEntity $range
         */
        $range = $this->getRepo()->fetchUsingId($command);

        $permitStock = $this->getRepo('IrhpPermitStock')->fetchById($command->getIrhpPermitStock());

        $countrys = [];
        foreach ($command->getRestrictedCountries() as $country) {
            $countrys[] = $this->getRepo('Country')->getReference(Country::class, $country);
        }

        $range->update(
            $permitStock,
            $command->getPrefix(),
            $command->getFromNo(),
            $command->getToNo(),
            $command->getSsReserve(),
            $command->getIsLostReplacement(),
            $countrys
        );

        $this->getRepo()->save($range);

        $this->result->addId('Irhp Permit Range', $range->getId());
        $this->result->addMessage("Irhp Permit Range '{$range->getId()}' updated");

        return $this->result;
    }
}