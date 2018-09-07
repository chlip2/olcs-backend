<?php

use Dvsa\Olcs\Api\Domain\QueryHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Permits;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\CanAccessOrganisationWithOrganisation;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\CanAccessLicenceWithLicence;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\NotIsAnonymousUser;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\IsInternalEdit;

return [
    QueryHandler\Permits\SectorsList::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\EcmtCountriesList::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\EcmtPermitApplication::class => CanAccessOrganisationWithOrganisation::class,
    QueryHandler\Permits\ById::class => Permits\CanAccessPermitAppWithId::class,
    QueryHandler\Permits\EcmtPermitFees::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\EcmtApplicationByLicence::class => CanAccessLicenceWithLicence::class,
    CommandHandler\Permits\CreateEcmtPermitApplication::class => CanAccessLicenceWithLicence::class,
    CommandHandler\Permits\UpdateEcmtEmissions::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\CancelEcmtPermitApplication::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateDeclaration::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateEcmtCabotage::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateEcmtPermitsRequired::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateEcmtCheckAnswers::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateDeclaration::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateInternationalJourney::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateEcmtTrips::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateSector::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateEcmtCountries::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateEcmtLicence::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\EcmtSubmitApplication::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateEcmtPermitApplication::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\WithdrawEcmtPermitApplication::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\CreateFullPermitApplication::class => IsInternalEdit::class,
];
