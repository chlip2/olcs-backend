<?php

namespace Dvsa\Olcs\Api\Domain\CommandHandler\Permits;

use Dvsa\Olcs\Api\Domain\AuthAwareInterface;
use Dvsa\Olcs\Api\Domain\AuthAwareTrait;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\Exception\ForbiddenException;
use Dvsa\Olcs\Api\Domain\ToggleAwareTrait;
use Dvsa\Olcs\Api\Domain\ToggleRequiredInterface;
use Dvsa\Olcs\Api\Entity\Fee\Fee;
use Dvsa\Olcs\Api\Entity\Licence\Licence;
use Dvsa\Olcs\Api\Entity\Permits\EcmtPermitApplication;
use Dvsa\Olcs\Api\Entity\System\FeatureToggle;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Domain\Command\Fee\CancelFee;

/**
 * Update ECMT Application Licence
 *
 * @author Andy Newton
 */
final class UpdateEcmtLicence extends AbstractCommandHandler implements ToggleRequiredInterface, AuthAwareInterface
{
    use ToggleAwareTrait;
    use AuthAwareTrait;

    const LICENCE_INVALID_MSG = 'Licence ID %s with number %s is unable to make an ECMT application';
    const LICENCE_ORG_MSG = 'Licence does not belong to this organisation';

    protected $toggleConfig = [FeatureToggle::BACKEND_PERMITS];
    protected $repoServiceName = 'EcmtPermitApplication';

    protected $extraRepos = ['Licence', 'IrhpPermitApplication'];

    /**
     * Handle command
     *
     * @param CommandInterface $command command
     *
     * @return Result
     * @throws ForbiddenException
     */
    public function handleCommand(CommandInterface $command)
    {
        $result = new Result();

        /** @var Licence $licence */
        $licence = $this->getRepo('Licence')->fetchById($command->getLicence());

        if (!$this->licenceBelongsToOrganisation($licence)) {
            throw new ForbiddenException(self::LICENCE_ORG_MSG);
        }

        /** @var EcmtPermitApplication $application */
        $application = $this->getRepo()->fetchById($command->getId());

        if (!$licence->canMakeEcmtApplication($application->getAssociatedStock(), $application)) {
            $message = sprintf(self::LICENCE_INVALID_MSG, $licence->getId(), $licence->getLicNo());
            throw new ForbiddenException($message);
        }

        // Update the licence but reset the previously answers questions to NULL
        $application->updateLicence($licence);
        $irhpPermitApplication = $application->getFirstIrhpPermitApplication();
        $irhpPermitApplication->updateLicence($licence);
        $fees = $application->getFees();

        /** @var Fee $fee */
        foreach ($fees as $fee) {
            if ($fee->isOutstanding()) {
                $this->result->merge($this->handleSideEffect(CancelFee::create(['id' => $fee->getId()])));
            }
        }

        $this->getRepo()->save($application);
        $this->getRepo('IrhpPermitApplication')->save($irhpPermitApplication);
        $result->addId('ecmtPermitApplication', $application->getId());
        $result->addMessage('EcmtPermitApplication Licence Updated successfully');

        return $result;
    }
}
