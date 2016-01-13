<?php

namespace Dvsa\Olcs\Api\Service\Submission\Sections;

use Dvsa\Olcs\Api\Entity\Application\Application;
use Dvsa\Olcs\Api\Entity\Cases\Cases as CasesEntity;

/**
 * Class AuthRequestedAppliedFor
 * @package Dvsa\Olcs\Api\Service\Submission\Sections
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */
final class AuthRequestedAppliedFor extends AbstractSection
{
    public function generateSection(CasesEntity $case)
    {
        $data = [];
        $licence = $case->getLicence();
        $applications = !empty($licence) ? $licence->getApplications() : [];

        /** @var Application $application */
        foreach ($applications as $application) {
            $thisData = array();
            $thisData['id'] = $application->getId();
            $thisData['version'] = $application->getVersion();

            $thisData['currentVehiclesInPossession'] = '0';
            $thisData['currentTrailersInPossession'] = '0';
            $thisData['currentVehicleAuthorisation'] = '0';
            $thisData['currentTrailerAuthorisation'] = '0';

            if ($application->isVariation()) {
                $thisData['currentVehiclesInPossession'] = $licence->getActiveVehiclesCount();
                $thisData['currentTrailersInPossession'] = $licence->getTotAuthTrailers();

                $thisData['currentVehicleAuthorisation'] =
                    !empty($licence->getTotAuthVehicles()) ? $licence->getTotAuthVehicles() : '0';
                $thisData['currentTrailerAuthorisation'] =
                    !empty($licence->getTotAuthTrailers()) ? $licence->getTotAuthTrailers() : '0';
            }

            $thisData['requestedVehicleAuthorisation'] =
                !empty($application->getTotAuthVehicles()) ? $application->getTotAuthVehicles() : '0';
            $thisData['requestedTrailerAuthorisation'] =
                !empty($application->getTotAuthTrailers()) ? $application->getTotAuthTrailers() : '0';
            $data[] = $thisData;
        }

        return [
            'data' => [
                'tables' => [
                    'auth-requested-applied-for' => $data
                ]
            ]
        ];
    }
}
