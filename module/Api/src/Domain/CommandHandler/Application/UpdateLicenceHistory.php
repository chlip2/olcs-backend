<?php

/**
 * Update Licence History
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler\Application;

use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Doctrine\ORM\Query;
use Dvsa\Olcs\Api\Entity\Application\Application;
use Dvsa\Olcs\Api\Entity\OtherLicence\OtherLicence;
use Dvsa\Olcs\Api\Domain\Exception\ValidationException;

/**
 * Update Licence History
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
final class UpdateLicenceHistory extends AbstractCommandHandler
{
    protected $repoServiceName = 'Application';

    public function handleCommand(CommandInterface $command)
    {
        $result = new Result();

        /** @var Application $application */
        $application = $this->getRepo()->fetchUsingId($command, Query::HYDRATE_OBJECT, $command->getVersion());

        if (!$command->getInProgress()) {
            $this->validateOtherLicences($command, $application);
        }

        $application->setPrevHasLicence(
            $command->getPrevHasLicence()
        );
        $application->setPrevHadLicence(
            $command->getPrevHadLicence()
        );
        $application->setPrevBeenRefused(
            $command->getPrevBeenRefused()
        );
        $application->setPrevBeenRevoked(
            $command->getPrevBeenRevoked()
        );
        $application->setPrevBeenAtPi(
            $command->getPrevBeenAtPi()
        );
        $application->setPrevBeenDisqualifiedTc(
            $command->getPrevBeenDisqualifiedTc()
        );
        $application->setPrevPurchasedAssets(
            $command->getPrevPurchasedAssets()
        );

        $this->getRepo()->save($application);
        $result->addMessage('Licence history section has been updated');
        return $result;
    }

    private function validateOtherLicences($command, $application)
    {
        $errors = [];
        $fields = [
            OtherLicence::TYPE_CURRENT => 'prevHadLicence',
            OtherLicence::TYPE_APPLIED => 'prevHadLicence',
            OtherLicence::TYPE_REFUSED => 'prevBeenRefused',
            OtherLicence::TYPE_REVOKED => 'prevBeenRevoked',
            OtherLicence::TYPE_PUBLIC_INQUIRY => 'prevBeenAtPi',
            OtherLicence::TYPE_DISQUALIFIED => 'prevBeenDisqualifiedTc',
            OtherLicence::TYPE_HELD => 'prevPurchasedAssets'
        ];

        foreach ($fields as $type => $field) {
            $errors = $this->validateField($errors, $field, $type, $application, $command);
        }
        if (count($errors)) {
            throw new ValidationException($errors);
        }
    }

    private function validateField($errors, $field, $type, $application, $command)
    {
        $method = 'get' . ucfirst($field);
        if ($command->$method() === 'Y' &&
            !$this->hasOtherLicences($application, $type)) {
            $errors[] = [
                $field => [
                    'No licence added'
                ]
            ];
        }
        return $errors;
    }

    private function hasOtherLicences($application, $type)
    {
        $hasLicences = false;
        $otherLicences = $application->getOtherLicences();
        foreach ($otherLicences as $licence) {
            $previousLicencetype = $licence->getPreviousLicenceType();
            if (!empty($previousLicencetype) && $licence->getPreviousLicenceType()->getId() === $type) {
                $hasLicences = true;
                break;
            }
        }
        return $hasLicences;
    }
}
