<?php

use Dvsa\Olcs\Api\Domain\QueryHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\IsInternalUser;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\IsSideEffect;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Permits;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\CanAccessOrganisationWithOrganisation;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\CanAccessLicenceWithLicence;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\NotIsAnonymousUser;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\IsInternalEdit;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\IsInternalAdmin;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\IsInternalOrSystemUser;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\IsSystemAdmin;

return [
    QueryHandler\IrhpApplication\ById::class => Permits\CanAccessIrhpApplicationWithId::class,
    QueryHandler\IrhpApplication\QuestionAnswer::class => Permits\CanAccessIrhpApplicationWithId::class,
    QueryHandler\IrhpApplication\GetList::class => CanAccessOrganisationWithOrganisation::class,
    QueryHandler\IrhpApplication\MaxStockPermits::class => CanAccessLicenceWithLicence::class,
    QueryHandler\IrhpApplication\MaxStockPermitsByApplication::class => Permits\CanAccessIrhpApplicationWithId::class,
    QueryHandler\IrhpApplication\FeeBreakdown::class => Permits\CanAccessIrhpApplicationWithId::class,
    QueryHandler\IrhpApplication\FeePerPermit::class => Permits\CanAccessIrhpApplicationWithId::class,
    QueryHandler\IrhpApplication\ApplicationStep::class => Permits\CanAccessIrhpApplicationWithId::class,
    QueryHandler\IrhpApplication\ApplicationPath::class => Permits\CanAccessIrhpApplicationWithId::class,
    QueryHandler\IrhpApplication\PermitsAvailable::class => Permits\CanAccessIrhpApplicationWithId::class,
    QueryHandler\IrhpApplication\PermitsAvailableByYear::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\Sectors::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\EcmtConstrainedCountriesList::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\EcmtPermitApplication::class => CanAccessOrganisationWithOrganisation::class,
    QueryHandler\Permits\ById::class => Permits\CanAccessPermitAppWithId::class,
    QueryHandler\Permits\EcmtPermitFees::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\EcmtApplicationByLicence::class => CanAccessLicenceWithLicence::class,
    QueryHandler\Permits\ValidEcmtPermits::class => CanAccessLicenceWithLicence::class,
    QueryHandler\Permits\UnpaidEcmtPermits::class => Permits\CanAccessPermitAppWithId::class,
    QueryHandler\IrhpPermitStock\AvailableCountries::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\AvailableLicences::class => Permits\CanAccessPermitAppWithId::class,
    QueryHandler\Permits\AvailableTypes::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\AvailableYears::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\AvailableStocks::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\EmissionsByYear::class => IsInternalUser::class,
    QueryHandler\Permits\OpenWindows::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\QueueRunScoringPermitted::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\CheckRunScoringPrerequisites::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\QueueAcceptScoringPermitted::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\CheckAcceptScoringPrerequisites::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\StockScoringPermitted::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\StockAcceptPermitted::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\StockOperationsPermitted::class => NotIsAnonymousUser::class,
    QueryHandler\Permits\GetScoredPermitList::class => IsInternalAdmin::class,
    QueryHandler\Permits\ReadyToPrintType::class => IsInternalAdmin::class,
    QueryHandler\Permits\ReadyToPrintCountry::class => IsInternalAdmin::class,
    QueryHandler\Permits\ReadyToPrintStock::class => IsInternalAdmin::class,
    QueryHandler\Permits\ReadyToPrint::class => IsInternalAdmin::class,
    QueryHandler\Permits\ReadyToPrintConfirm::class => IsInternalAdmin::class,
    QueryHandler\Permits\DeviationData::class => IsInternalOrSystemUser::class,
    QueryHandler\Permits\EcmtApplicationIssueFeePerPermit::class => Permits\CanAccessPermitAppWithId::class,
    QueryHandler\IrhpPermitWindow\OpenByCountry::class => NotIsAnonymousUser::class,
    CommandHandler\IrhpApplication\UpdateCheckAnswers::class => Permits\CanEditIrhpApplicationWithId::class,
    CommandHandler\IrhpApplication\Cancel::class => Permits\CanEditIrhpApplicationWithId::class,
    CommandHandler\IrhpApplication\Withdraw::class => Permits\CanEditIrhpApplicationWithId::class,
    CommandHandler\IrhpApplication\Grant::class => IsInternalUser::class,
    CommandHandler\IrhpApplication\ReviveFromWithdrawn::class => IsInternalUser::class,
    CommandHandler\IrhpApplication\UpdateCountries::class => Permits\CanEditIrhpApplicationWithId::class,
    CommandHandler\IrhpApplication\UpdateMultipleNoOfPermits::class => Permits\CanEditIrhpApplicationWithId::class,
    CommandHandler\IrhpApplication\SubmitApplicationStep::class => Permits\CanEditIrhpApplicationWithId::class,
    CommandHandler\IrhpApplication\SubmitApplicationPath::class => Permits\CanEditIrhpApplicationWithId::class,
    CommandHandler\Permits\CreateEcmtPermitApplication::class => CanAccessLicenceWithLicence::class,
    CommandHandler\IrhpApplication\Create::class => CanAccessLicenceWithLicence::class,
    CommandHandler\IrhpApplication\UpdateDeclaration::class => Permits\CanEditIrhpApplicationWithId::class,
    CommandHandler\IrhpApplication\SubmitApplication::class => Permits\CanEditIrhpApplicationWithId::class,
    CommandHandler\IrhpApplication\RegenerateApplicationFee::class => IsSideEffect::class,
    CommandHandler\IrhpApplication\RegenerateIssueFee::class => IsSideEffect::class,
    CommandHandler\Permits\CreateEcmtPermitApplication::class => CanAccessLicenceWithLicence::class,
    CommandHandler\Permits\UpdateEcmtEmissions::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\CancelEcmtPermitApplication::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateDeclaration::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateEcmtCabotage::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\UpdateEcmtRoadworthiness::class => Permits\CanEditPermitAppWithId::class,
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
    CommandHandler\Permits\DeclineEcmtPermits::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\AcceptEcmtPermits::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\AcceptIrhpPermits::class => Permits\CanEditIrhpApplicationWithId::class,
    CommandHandler\Permits\CreateFullPermitApplication::class => IsInternalEdit::class,
    CommandHandler\Permits\CreateIrhpPermitApplication::class => IsSideEffect::class,
    CommandHandler\Permits\UpdatePermitFee::class => IsSideEffect::class,
    CommandHandler\Permits\CompleteIssuePayment::class => Permits\CanEditPermitAppWithId::class,
    CommandHandler\Permits\GeneratePermitDocuments::class => IsSideEffect::class,
    CommandHandler\Permits\PrintPermits::class => IsInternalAdmin::class,
    CommandHandler\Permits\ProceedToStatus::class => IsSideEffect::class,
    CommandHandler\Permits\ExpireEcmtPermitApplication::class => IsSideEffect::class,
    CommandHandler\Permits\ReviveEcmtPermitApplicationFromWithdrawn::class => IsInternalUser::class,

    CommandHandler\Permits\QueueRunScoring::class => IsSystemAdmin::class,
    CommandHandler\Permits\QueueAcceptScoring::class => IsSystemAdmin::class,

    CommandHandler\Permits\StoreEcmtPermitApplicationSnapshot::class => Permits\CanEditPermitAppWithId::class,
];
