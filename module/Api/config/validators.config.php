<?php

use Dvsa\Olcs\Api\Domain\Validation\Validators;

return [
    'factories' => [
        'isOwner' => Validators\IsOwner::class,

        'doesOwnLicence' => Validators\DoesOwnLicence::class,
        'doesOwnApplication' => Validators\DoesOwnApplication::class,
        'doesOwnCompanySubsidiary' => Validators\DoesOwnCompanySubsidiary::class,
        'doesOwnOrganisation' => Validators\DoesOwnOrganisation::class,
        'doesOwnOrganisationPerson' => Validators\DoesOwnOrganisationPerson::class,
        'doesOwnPerson' => Validators\DoesOwnPerson::class,

        'canAccessLicence' => Validators\CanAccessLicence::class,
        'canAccessApplication' => Validators\CanAccessApplication::class,
        'canAccessCompanySubsidiary' => Validators\CanAccessCompanySubsidiary::class,
        'canAccessOrganisation' => Validators\CanAccessOrganisation::class,
        'canAccessOrganisationPerson' => Validators\CanAccessOrganisationPerson::class,
        'canAccessTransportManagerApplication' => Validators\CanAccessTransportManagerApplication::class,
        'canAccessPreviousConviction' => Validators\CanAccessPreviousConviction::class,
        'canAccessTrailer' => Validators\CanAccessTrailer::class,
        'canAccessApplicationOperatingCentre' => Validators\CanAccessApplicationOperatingCentre::class,
        'canAccessLicenceOperatingCentre' => Validators\CanAccessLicenceOperatingCentre::class,
        'canAccessPerson' => Validators\CanAccessPerson::class,
        'canAccessPsvDisc' => Validators\CanAccessPsvDisc::class,
        'canAccessOtherLicence' => Validators\CanAccessOtherLicence::class,
        'canAccessTransportManagerLicence' => Validators\CanAccessTransportManagerLicence::class,
        'canAccessUser' => Validators\CanAccessUser::class,
        'canAccessLicenceVehicle' => Validators\CanAccessLicenceVehicle::class,
        'canAccessCorrespondenceInbox' => Validators\CanAccessCorrespondenceInbox::class,
        'canAccessDocument' => Validators\CanAccessDocument::class,
        'canAccessSubmission' => Validators\CanAccessSubmission::class,
        'canAccessCase' => Validators\CanAccessCase::class,
        'canAccessTransportManager' => Validators\CanAccessTransportManager::class,
        'canAccessOperatingCentre' => Validators\CanAccessOperatingCentre::class,
        'canAccessBusReg' => Validators\CanAccessBusReg::class,
        'canAccessStatement' => Validators\CanAccessStatement::class,
        'canAccessTransaction' => Validators\CanAccessTransaction::class,
        'canAccessFee' => Validators\CanAccessFee::class,
    ]
];
