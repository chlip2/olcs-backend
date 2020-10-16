<?php

namespace Dvsa\Olcs\Api\Domain\CommandHandler\Licence;

use Doctrine\ORM\Query;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler\TransactionedInterface;
use Dvsa\Olcs\Api\Domain\Command\Licence\EndIrhpApplicationsAndPermits as EndIrhpApplicationsAndPermitsCmd;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Entity\IrhpInterface;
use Dvsa\Olcs\Api\Entity\WithdrawableInterface;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\CancelApplication;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\Withdraw;
use Dvsa\Olcs\Transfer\Command\IrhpPermit\Terminate;
use Dvsa\Olcs\Transfer\Query\IrhpPermit\GetListByLicence;

/**
 * End IRHP applications and permits relating to a licence
 *
 * @author Jonathan Thomas <jonathan@opalise.co.uk>
 */
class EndIrhpApplicationsAndPermits extends AbstractCommandHandler implements TransactionedInterface
{
    protected $repoServiceName = 'Licence';

    protected $extraRepos = ['IrhpPermit'];

    /**
     * Handle command
     *
     * @param EndIrhpApplicationsAndPermitsCmd|CommandInterface $command command
     *
     * @return Result
     */
    public function handleCommand(CommandInterface $command)
    {
        $licenceId = $command->getId();

        $licence = $this->getRepo()->fetchById($licenceId);

        foreach ($licence->getOngoingIrhpApplications() as $irhpApplication) {
            switch ($irhpApplication->getStatus()->getId()) {
                case IrhpInterface::STATUS_NOT_YET_SUBMITTED:
                    $this->result->merge(
                        $this->handleSideEffect(
                            CancelApplication::create(['id' => $irhpApplication->getId()])
                        )
                    );
                    break;
                case IrhpInterface::STATUS_UNDER_CONSIDERATION:
                    $this->result->merge(
                        $this->handleSideEffect(
                            Withdraw::create(
                                [
                                    'id' => $irhpApplication->getId(),
                                    'reason' => WithdrawableInterface::WITHDRAWN_REASON_BY_USER
                                ]
                            )
                        )
                    );
                    break;
            }
        }

        $activeIrhpPermitsQuery = GetListByLicence::create(
            [
                'licence' => $command->getId(),
                'validOnly' => true
            ]
        );

        $activeIrhpPermits = $this->getRepo('IrhpPermit')->fetchList($activeIrhpPermitsQuery, Query::HYDRATE_OBJECT);

        foreach ($activeIrhpPermits as $irhpPermit) {
            $this->result->merge(
                $this->handleSideEffect(
                    Terminate::create(
                        ['id' => $irhpPermit->getId()]
                    )
                )
            );
        }

        $this->result->addMessage('Cleared IRHP applications and permits for licence ' . $licenceId);

        return $this->result;
    }
}
