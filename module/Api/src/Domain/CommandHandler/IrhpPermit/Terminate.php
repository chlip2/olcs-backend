<?php

namespace Dvsa\Olcs\Api\Domain\CommandHandler\IrhpPermit;

use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\Exception\ForbiddenException;
use Dvsa\Olcs\Api\Domain\ToggleAwareTrait;
use Dvsa\Olcs\Api\Domain\ToggleRequiredInterface;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermit;
use Dvsa\Olcs\Api\Entity\System\FeatureToggle;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Domain\Command\Permits\ExpireEcmtPermitApplication;

/**
 * Terminate Permit
 *
 * @author Tonci Vidovic <tonci.vidovic@capgemini.com>
 */
class Terminate extends AbstractCommandHandler implements ToggleRequiredInterface
{
    use ToggleAwareTrait;

    protected $toggleConfig = [FeatureToggle::BACKEND_ECMT];
    protected $repoServiceName = 'IrhpPermit';

    /**
     * Handle terminate permit command
     *
     * @param CommandInterface $command
     * @return Result
     */
    public function handleCommand(CommandInterface $command): Result
    {
        $permit = $this->getRepo()->fetchById($command->getId());

        if (!$permit->getIrhpPermitRange()->getIrhpPermitStock()->getIrhpPermitType()->isEcmtAnnual()) {
            throw new ForbiddenException('Only ECMT Permits can be terminated.');
        }

        $terminatedStatus = $this->refData(IrhpPermit::STATUS_TERMINATED);

        try {
            $permit->proceedToStatus($terminatedStatus);
        } catch (ForbiddenException $exception) {
            $this->result->addMessage('You cannot terminate an inactive permit.');
            return $this->result;
        }

        $this->getRepo()->save($permit);

        $this->result->addId('IrhpPermit', $permit->getId());
        $this->result->addMessage('The selected permit has been terminated.');

        if (!$permit->getIrhpPermitApplication()->hasValidPermits()) {
            $applicationId = $permit->getIrhpPermitApplication()->getEcmtPermitApplication()->getId();
            $this->result->merge(
                $this->handleSideEffect(
                    ExpireEcmtPermitApplication::create(
                        [
                            'id' => $applicationId
                        ]
                    )
                )
            );
        }
        return $this->result;
    }
}