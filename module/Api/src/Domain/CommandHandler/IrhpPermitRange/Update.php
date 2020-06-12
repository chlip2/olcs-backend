<?php

namespace Dvsa\Olcs\Api\Domain\CommandHandler\IrhpPermitRange;

use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\ToggleRequiredInterface;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitRange as RangeEntity;
use Dvsa\Olcs\Api\Entity\System\RefData;
use Dvsa\Olcs\Transfer\Command\IrhpPermitRange\Update as UpdateRangeCmd;
use Dvsa\Olcs\Api\Entity\ContactDetails\Country;
use Dvsa\Olcs\Api\Domain\Exception\ValidationException;
use Dvsa\Olcs\Api\Domain\ToggleAwareTrait;
use Dvsa\Olcs\Api\Entity\System\FeatureToggle;

/**
 * Update an IRHP Permit Range
 *
 * @author Scott Callaway <scott.callaway@capgemini.com>
 */
final class Update extends AbstractCommandHandler implements ToggleRequiredInterface
{
    use IrhpPermitRangeOverlapTrait;
    use ToggleAwareTrait;

    protected $toggleConfig = [FeatureToggle::ADMIN_PERMITS];
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

        if ($this->numberOfOverlappingRanges($command->getIrhpPermitStock(), $command->getFromNo(), $command->getToNo(), $range) !== 0) {
            throw new ValidationException(['This Permit Number Range overlaps with another for this stock']);
        }

        $permitStock = $this->getRepo('IrhpPermitStock')->fetchById($command->getIrhpPermitStock());
        $permitType = $permitStock->getIrhpPermitType();

        if (($permitType->isEcmtShortTerm() || $permitType->isEcmtAnnual())
            && $command->getEmissionsCategory() == RefData::EMISSIONS_CATEGORY_NA_REF) {
            throw new ValidationException(['Emissions Category: N/A not valid for Short-term/Annual ECMT Stock']);
        }

        if ($permitType->isBilateral() && !$command->getJourney()) {
            throw new ValidationException(['Journey type is required.']);
        }

        $countrys = [];
        foreach ($command->getRestrictedCountries() as $country) {
            $countrys[] = $this->getRepo('Country')->getReference(Country::class, $country);
        }

        $range->update(
            $permitStock,
            $this->refData($command->getEmissionsCategory()),
            $command->getPrefix(),
            $command->getFromNo(),
            $command->getToNo(),
            $command->getSsReserve(),
            $command->getIsLostReplacement(),
            $countrys,
            $this->refDataOrNull($command->getJourney()),
            $command->getCabotage()
        );

        $this->getRepo()->save($range);

        $this->result->addId('Irhp Permit Range', $range->getId());
        $this->result->addMessage("Irhp Permit Range '{$range->getId()}' updated");

        return $this->result;
    }
}
