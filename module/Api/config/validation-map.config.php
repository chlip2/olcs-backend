<?php

use Dvsa\Olcs\Api\Domain\QueryHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler;
use Dvsa\Olcs\Cli\Domain\CommandHandler as CliCommandHandler;
use Dvsa\Olcs\Email\Domain\CommandHandler as EmailCommandHandler;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Standard;

/**
 * @NOTE When you implement one of the following rules, please move it to the (or create a) relevant
 * validation-map/*.config.php. Eventually this file should be empty
 */
// @codingStandardsIgnoreStart
$map = [
    CommandHandler\Application\CancelApplication::class                           => Standard::class, // @todo
    CommandHandler\Application\CreateApplication::class                           => Standard::class, // @todo
    CommandHandler\Application\CreateSnapshot::class                              => Standard::class, // @todo
    CommandHandler\Application\CreateTaxiPhv::class                               => Standard::class, // @todo
    CommandHandler\Application\DeleteApplication::class                           => Standard::class, // @todo
    CommandHandler\Application\DeleteTaxiPhv::class                               => Standard::class, // @todo
    CommandHandler\Application\EndInterim::class                                  => Standard::class, // @todo
    CommandHandler\Application\GenerateLicenceNumber::class                       => Standard::class, // @todo
    CommandHandler\Application\GenerateOrganisationName::class                    => Standard::class, // @todo
    CommandHandler\Application\Overview::class                                    => Standard::class, // @todo
    CommandHandler\Application\PrintInterimDocument::class                        => Standard::class, // @todo
    CommandHandler\Application\Publish::class                                     => Standard::class, // @todo
    CommandHandler\Application\RefuseApplication::class                           => Standard::class, // @todo
    CommandHandler\Application\RefuseInterim::class                               => Standard::class, // @todo
    CommandHandler\Application\ReviveApplication::class                           => Standard::class, // @todo
    CommandHandler\Application\Schedule41::class                                  => Standard::class, // @todo
    CommandHandler\Application\SubmitApplication::class                           => Standard::class, // @todo
    CommandHandler\Application\UpdateAddresses::class                             => Standard::class, // @todo
    CommandHandler\Application\UpdateApplicationCompletion::class                 => Standard::class, // @todo
    CommandHandler\Application\UpdateAuthSignature::class                         => Standard::class, // @todo
    CommandHandler\Application\UpdateBusinessDetails::class                       => Standard::class, // @todo
    CommandHandler\Application\UpdateDeclaration::class                           => Standard::class, // @todo
    CommandHandler\Application\UpdateFinancialEvidence::class                     => Standard::class, // @todo
    CommandHandler\Application\UpdateFinancialHistory::class                      => Standard::class, // @todo
    CommandHandler\Application\UpdateInterim::class                               => Standard::class, // @todo
    CommandHandler\Application\UpdateTaxiPhv::class                               => Standard::class, // @todo
    CommandHandler\Application\UpdateTypeOfLicence::class                         => Standard::class, // @todo
    CommandHandler\Application\WithdrawApplication::class                         => Standard::class, // @todo
    CommandHandler\Bus\AdminCancelBusReg::class                                   => Standard::class, // @todo
    CommandHandler\Bus\CreateBus::class                                           => Standard::class, // @todo
    CommandHandler\Bus\CreateCancellation::class                                  => Standard::class, // @todo
    CommandHandler\Bus\CreateVariation::class                                     => Standard::class, // @todo
    CommandHandler\Bus\DeleteBus::class                                           => Standard::class, // @todo
    CommandHandler\Bus\Ebsr\ProcessPacks::class                                   => Standard::class, // @todo
    CommandHandler\Bus\Ebsr\ProcessRequestMap::class                              => Standard::class, // @todo
    CommandHandler\Bus\Ebsr\RequestMapQueue::class                                => Standard::class, // @todo
    CommandHandler\Bus\Ebsr\CreateTxcInbox::class                                 => Standard::class, // @todo
    CommandHandler\Bus\Ebsr\UpdateTxcInbox::class                                 => Standard::class, // @todo
    CommandHandler\Bus\Ebsr\UpdateTxcInboxPdf::class                              => Standard::class, // @todo
    CommandHandler\Bus\GrantBusReg::class                                         => Standard::class, // @todo
    CommandHandler\Bus\RefuseBusReg::class                                        => Standard::class, // @todo
    CommandHandler\Bus\RefuseBusRegByShortNotice::class                           => Standard::class, // @todo
    CommandHandler\Bus\ResetBusReg::class                                         => Standard::class, // @todo
    CommandHandler\Bus\UpdateQualitySchemes::class                                => Standard::class, // @todo
    CommandHandler\Bus\UpdateServiceDetails::class                                => Standard::class, // @todo
    CommandHandler\Bus\UpdateServiceRegister::class                               => Standard::class, // @todo
    CommandHandler\Bus\UpdateShortNotice::class                                   => Standard::class, // @todo
    CommandHandler\Bus\UpdateStops::class                                         => Standard::class, // @todo
    CommandHandler\Bus\UpdateTaAuthority::class                                   => Standard::class, // @todo
    CommandHandler\Bus\WithdrawBusReg::class                                      => Standard::class, // @todo
    CommandHandler\ChangeOfEntity\CreateChangeOfEntity::class                     => Standard::class, // @todo
    CommandHandler\ChangeOfEntity\DeleteChangeOfEntity::class                     => Standard::class, // @todo
    CommandHandler\ChangeOfEntity\UpdateChangeOfEntity::class                     => Standard::class, // @todo
    CommandHandler\CommunityLic\Application\Create::class                         => Standard::class, // @todo
    CommandHandler\CommunityLic\Application\CreateOfficeCopy::class               => Standard::class, // @todo
    CommandHandler\CommunityLic\Licence\Create::class                             => Standard::class, // @todo
    CommandHandler\CommunityLic\Licence\CreateOfficeCopy::class                   => Standard::class, // @todo
    CommandHandler\CommunityLic\Reprint::class                                    => Standard::class, // @todo
    CommandHandler\CommunityLic\Restore::class                                    => Standard::class, // @todo
    CommandHandler\CommunityLic\Stop::class                                       => Standard::class, // @todo
    CommandHandler\CommunityLic\Void::class                                       => Standard::class, // @todo
    CommandHandler\CompaniesHouse\CloseAlerts::class                              => Standard::class, // @todo
    CommandHandler\Complaint\CreateComplaint::class                               => Standard::class, // @todo
    CommandHandler\Complaint\DeleteComplaint::class                               => Standard::class, // @todo
    CommandHandler\Complaint\UpdateComplaint::class                               => Standard::class, // @todo
    CommandHandler\ConditionUndertaking\Create::class                             => Standard::class, // @todo
    CommandHandler\ConditionUndertaking\Delete::class                             => Standard::class, // @todo
    CommandHandler\ConditionUndertaking\DeleteList::class                         => Standard::class, // @todo
    CommandHandler\ConditionUndertaking\Update::class                             => Standard::class, // @todo
    CommandHandler\ContinuationDetail\PrepareContinuations::class                 => Standard::class, // @todo
    CommandHandler\ContinuationDetail\Queue::class                                => Standard::class, // @todo
    CommandHandler\ContinuationDetail\Update::class                               => Standard::class, // @todo
    CommandHandler\Continuation\Create::class                                     => Standard::class, // @todo
    CommandHandler\Correspondence\AccessCorrespondence::class                     => Standard::class, // @todo
    CommandHandler\Cpms\DownloadReport::class                                     => Standard::class, // @todo
    CommandHandler\Cpms\RequestReport::class                                      => Standard::class, // @todo
    CommandHandler\Disqualification\Create::class                                 => Standard::class, // @todo
    CommandHandler\Disqualification\Update::class                                 => Standard::class, // @todo
    CommandHandler\Document\CopyDocument::class                                   => Standard::class, // @todo
    CommandHandler\Document\CreateDocument::class                                 => Standard::class, // @todo
    CommandHandler\Document\CreateLetter::class                                   => Standard::class, // @todo
    CommandHandler\Document\DeleteDocument::class                                 => Standard::class, // @todo
    CommandHandler\Document\DeleteDocuments::class                                => Standard::class, // @todo
    CommandHandler\Document\GenerateAndStore::class                               => Standard::class, // @todo
    CommandHandler\Document\MoveDocument::class                                   => Standard::class, // @todo
    CommandHandler\Document\PrintLetter::class                                    => Standard::class, // @todo
    CommandHandler\Document\UpdateDocumentLinks::class                            => Standard::class, // @todo
    CommandHandler\Document\Upload::class                                         => Standard::class, // @todo
    CommandHandler\Email\SendTmApplication::class                                 => Standard::class, // @todo
    CommandHandler\EnvironmentalComplaint\CreateEnvironmentalComplaint::class     => Standard::class, // @todo
    CommandHandler\EnvironmentalComplaint\DeleteEnvironmentalComplaint::class     => Standard::class, // @todo
    CommandHandler\EnvironmentalComplaint\UpdateEnvironmentalComplaint::class     => Standard::class, // @todo
    CommandHandler\Fee\ApproveWaive::class                                        => Standard::class, // @todo
    CommandHandler\Fee\CreateFee::class                                           => Standard::class, // @todo
    CommandHandler\Fee\RecommendWaive::class                                      => Standard::class, // @todo
    CommandHandler\Fee\RefundFee::class                                           => Standard::class, // @todo
    CommandHandler\Fee\RejectWaive::class                                         => Standard::class, // @todo
    CommandHandler\GoodsDisc\ConfirmPrinting::class                               => Standard::class, // @todo
    CommandHandler\GoodsDisc\PrintDiscs::class                                    => Standard::class, // @todo
    CommandHandler\GracePeriod\CreateGracePeriod::class                           => Standard::class, // @todo
    CommandHandler\GracePeriod\DeleteGracePeriod::class                           => Standard::class, // @todo
    CommandHandler\GracePeriod\UpdateGracePeriod::class                           => Standard::class, // @todo
    CommandHandler\InspectionRequest\Create::class                                => Standard::class, // @todo
    CommandHandler\InspectionRequest\CreateFromGrant::class                       => Standard::class, // @todo
    CommandHandler\InspectionRequest\Delete::class                                => Standard::class, // @todo
    CommandHandler\InspectionRequest\Update::class                                => Standard::class, // @todo
    CommandHandler\LicenceStatusRule\CreateLicenceStatusRule::class               => Standard::class, // @todo
    CommandHandler\LicenceStatusRule\DeleteLicenceStatusRule::class               => Standard::class, // @todo
    CommandHandler\LicenceStatusRule\UpdateLicenceStatusRule::class               => Standard::class, // @todo
    CommandHandler\Licence\ContinueLicence::class                                 => Standard::class, // @todo
    CommandHandler\Licence\CreateVariation::class                                 => Standard::class, // @todo
    CommandHandler\Licence\Curtail::class                                         => Standard::class, // @todo
    CommandHandler\Licence\Overview::class                                        => Standard::class, // @todo
    CommandHandler\Licence\PrintLicence::class                                    => Standard::class, // @todo
    CommandHandler\Licence\ResetToValid::class                                    => Standard::class, // @todo
    CommandHandler\Licence\Revoke::class                                          => Standard::class, // @todo
    CommandHandler\Licence\Surrender::class                                       => Standard::class, // @todo
    CommandHandler\Licence\Suspend::class                                         => Standard::class, // @todo
    CommandHandler\Licence\UpdateAddresses::class                                 => Standard::class, // @todo
    CommandHandler\Licence\UpdateBusinessDetails::class                           => Standard::class, // @todo
    CommandHandler\Licence\UpdateOperatingCentres::class                          => Standard::class, // @todo
    CommandHandler\Licence\UpdateTotalCommunityLicences::class                    => Standard::class, // @todo
    CommandHandler\Licence\UpdateTrafficArea::class                               => Standard::class, // @todo
    CommandHandler\Licence\UpdateTypeOfLicence::class                             => Standard::class, // @todo
    CommandHandler\Operator\CreateUnlicensed::class                               => Standard::class, // @todo
    CommandHandler\Operator\SaveOperator::class                                   => Standard::class, // @todo
    CommandHandler\Operator\UpdateUnlicensed::class                               => Standard::class, // @todo
    CommandHandler\Opposition\CreateOpposition::class                             => Standard::class, // @todo
    CommandHandler\Opposition\DeleteOpposition::class                             => Standard::class, // @todo
    CommandHandler\Opposition\UpdateOpposition::class                             => Standard::class, // @todo
    CommandHandler\Organisation\CpidOrganisationExport::class                     => Standard::class, // @todo
    CommandHandler\Organisation\TransferTo::class                                 => Standard::class, // @todo
    CommandHandler\Organisation\UpdateBusinessType::class                         => Standard::class, // @todo
    CommandHandler\OtherLicence\CreateForTm::class                                => Standard::class, // @todo
    CommandHandler\OtherLicence\CreateForTma::class                               => Standard::class, // @todo
    CommandHandler\OtherLicence\CreateForTml::class                               => Standard::class, // @todo
    CommandHandler\OtherLicence\CreatePreviousLicence::class                      => Standard::class, // @todo
    CommandHandler\OtherLicence\UpdateForTma::class                               => Standard::class, // @todo
    CommandHandler\PrivateHireLicence\Create::class                               => Standard::class, // @todo
    CommandHandler\PrivateHireLicence\DeleteList::class                           => Standard::class, // @todo
    CommandHandler\PrivateHireLicence\Update::class                               => Standard::class, // @todo
    CommandHandler\Processing\Note\Create::class                                  => Standard::class, // @todo
    CommandHandler\Processing\Note\Delete::class                                  => Standard::class, // @todo
    CommandHandler\Processing\Note\Update::class                                  => Standard::class, // @todo
    CommandHandler\Publication\Application::class                                 => Standard::class, // @todo
    CommandHandler\Publication\Bus::class                                         => Standard::class, // @todo
    CommandHandler\Publication\CreateRecipient::class                             => Standard::class, // @todo
    CommandHandler\Publication\DeletePublicationLink::class                       => Standard::class, // @todo
    CommandHandler\Publication\DeleteRecipient::class                             => Standard::class, // @todo
    CommandHandler\Publication\Generate::class                                    => Standard::class, // @todo
    CommandHandler\Publication\Publish::class                                     => Standard::class, // @todo
    CommandHandler\Publication\UpdatePublicationLink::class                       => Standard::class, // @todo
    CommandHandler\Publication\UpdateRecipient::class                             => Standard::class, // @todo
    CommandHandler\Scan\CreateContinuationSeparatorSheet::class                   => Standard::class, // @todo
    CommandHandler\Scan\CreateDocument::class                                     => Standard::class, // @todo
    CommandHandler\Scan\CreateSeparatorSheet::class                               => Standard::class, // @todo
    CommandHandler\Task\CloseTasks::class                                         => Standard::class, // @todo
    CommandHandler\Task\CreateTask::class                                         => Standard::class, // @todo
    CommandHandler\Task\ReassignTasks::class                                      => Standard::class, // @todo
    CommandHandler\Task\UpdateTask::class                                         => Standard::class, // @todo
    CommandHandler\TmEmployment\Create::class                                     => Standard::class, // @todo
    CommandHandler\TmEmployment\DeleteList::class                                 => Standard::class, // @todo
    CommandHandler\TmEmployment\Update::class                                     => Standard::class, // @todo
    CommandHandler\TmQualification\Create::class                                  => Standard::class, // @todo
    CommandHandler\TmQualification\Delete::class                                  => Standard::class, // @todo
    CommandHandler\TmQualification\Update::class                                  => Standard::class, // @todo
    CommandHandler\Tm\Create::class                                               => Standard::class, // @todo
    CommandHandler\Tm\CreateNewUser::class                                        => Standard::class, // @todo
    CommandHandler\Tm\Merge::class                                                => Standard::class, // @todo
    CommandHandler\Tm\Remove::class                                               => Standard::class, // @todo
    CommandHandler\Tm\Unmerge::class                                              => Standard::class, // @todo
    CommandHandler\Tm\Update::class                                               => Standard::class, // @todo
    CommandHandler\Transaction\CompleteTransaction::class                         => Standard::class, // @todo
    CommandHandler\Transaction\PayOutstandingFees::class                          => Standard::class, // @todo
    CommandHandler\Transaction\ResolvePayment::class                              => Standard::class, // @todo
    CommandHandler\Transaction\ResolveOutstandingPayments::class                  => Standard::class, // @todo
    CommandHandler\User\CreateUser::class                                         => Standard::class, // @todo
    CommandHandler\User\CreateUserSelfserve::class                                => Standard::class, // @todo
    CommandHandler\User\DeleteUser::class                                         => Standard::class, // @todo
    CommandHandler\User\DeleteUserSelfserve::class                                => Standard::class, // @todo
    CommandHandler\User\UpdateUser::class                                         => Standard::class, // @todo
    CommandHandler\User\UpdateUserSelfserve::class                                => Standard::class, // @todo
    CommandHandler\Variation\DeleteListConditionUndertaking::class                => Standard::class, // @todo
    CommandHandler\Variation\RestoreListConditionUndertaking::class               => Standard::class, // @todo
    CommandHandler\Variation\UpdateAddresses::class                               => Standard::class, // @todo
    CommandHandler\Variation\UpdateConditionUndertaking::class                    => Standard::class, // @todo
    CommandHandler\Variation\UpdateTypeOfLicence::class                           => Standard::class, // @todo
    QueryHandler\ApplicationOperatingCentre\ApplicationOperatingCentre::class     => Standard::class, // @todo
    QueryHandler\Application\Application::class                                   => Standard::class, // @todo
    QueryHandler\Application\Declaration::class                                   => Standard::class, // @todo
    QueryHandler\Application\DeclarationUndertakings::class                       => Standard::class, // @todo
    QueryHandler\Application\EnforcementArea::class                               => Standard::class, // @todo
    QueryHandler\Application\FinancialEvidence::class                             => Standard::class, // @todo
    QueryHandler\Application\FinancialHistory::class                              => Standard::class, // @todo
    QueryHandler\Application\GetList::class                                       => Standard::class, // @todo
    QueryHandler\Application\Interim::class                                       => Standard::class, // @todo
    QueryHandler\Application\NotTakenUpList::class                                => Standard::class, // @todo
    QueryHandler\Application\Overview::class                                      => Standard::class, // @todo
    QueryHandler\Application\Publish::class                                       => Standard::class, // @todo
    QueryHandler\Application\Review::class                                        => Standard::class, // @todo
    QueryHandler\Application\Schedule41Approve::class                             => Standard::class, // @todo
    QueryHandler\Application\Summary::class                                       => Standard::class, // @todo
    QueryHandler\Application\TaxiPhv::class                                       => Standard::class, // @todo
    QueryHandler\Bus\Bus::class                                                   => Standard::class, // @todo
    QueryHandler\Bus\BusRegDecision::class                                        => Standard::class, // @todo
    QueryHandler\Bus\ByLicenceRoute::class                                        => Standard::class, // @todo
    QueryHandler\Bus\Ebsr\BusRegWithTxcInbox::class                               => Standard::class, // @todo
    QueryHandler\Bus\HistoryList::class                                           => Standard::class, // @todo
    QueryHandler\Bus\RegistrationHistoryList::class                               => Standard::class, // @todo
    QueryHandler\Bus\SearchViewList::class                                        => Standard::class, // @todo
    QueryHandler\Bus\ShortNoticeByBusReg::class                                   => Standard::class, // @todo
    QueryHandler\ChangeOfEntity\ChangeOfEntity::class                             => Standard::class, // @todo
    QueryHandler\CompaniesHouse\AlertList::class                                  => Standard::class, // @todo
    QueryHandler\CompaniesHouse\GetList::class                                    => Standard::class, // @todo
    QueryHandler\Complaint\Complaint::class                                       => Standard::class, // @todo
    QueryHandler\Complaint\ComplaintList::class                                   => Standard::class, // @todo
    QueryHandler\ConditionUndertaking\Get::class                                  => Standard::class, // @todo
    QueryHandler\ConditionUndertaking\GetList::class                              => Standard::class, // @todo
    QueryHandler\ContinuationDetail\ChecklistReminders::class                     => Standard::class, // @todo
    QueryHandler\ContinuationDetail\GetList::class                                => Standard::class, // @todo
    QueryHandler\Correspondence\Correspondence::class                             => Standard::class, // @todo
    QueryHandler\Correspondence\Correspondences::class                            => Standard::class, // @todo
    QueryHandler\Cpms\ReportList::class                                           => Standard::class, // @todo
    QueryHandler\Cpms\ReportStatus::class                                         => Standard::class, // @todo
    QueryHandler\Cpms\StoredCardList::class                                       => Standard::class, // @todo
    QueryHandler\DiscSequence\DiscPrefixes::class                                 => Standard::class, // @todo
    QueryHandler\DiscSequence\DiscsNumbering::class                               => Standard::class, // @todo
    QueryHandler\Document\Document::class                                         => Standard::class, // @todo
    QueryHandler\Document\DocumentList::class                                     => Standard::class, // @todo
    QueryHandler\Document\Download::class                                         => Standard::class, // @todo
    QueryHandler\Document\Letter::class                                           => Standard::class, // @todo
    QueryHandler\Document\TemplateParagraphs::class                               => Standard::class, // @todo
    QueryHandler\EnvironmentalComplaint\EnvironmentalComplaint::class             => Standard::class, // @todo
    QueryHandler\EnvironmentalComplaint\EnvironmentalComplaintList::class         => Standard::class, // @todo
    QueryHandler\Fee\Fee::class                                                   => Standard::class, // @todo
    QueryHandler\Fee\FeeList::class                                               => Standard::class, // @todo
    QueryHandler\Fee\FeeType::class                                               => Standard::class, // @todo
    QueryHandler\Fee\FeeTypeList::class                                           => Standard::class, // @todo
    QueryHandler\GracePeriod\GracePeriod::class                                   => Standard::class, // @todo
    QueryHandler\GracePeriod\GracePeriods::class                                  => Standard::class, // @todo
    QueryHandler\InspectionRequest\ApplicationInspectionRequestList::class        => Standard::class, // @todo
    QueryHandler\InspectionRequest\InspectionRequest::class                       => Standard::class, // @todo
    QueryHandler\InspectionRequest\LicenceInspectionRequestList::class            => Standard::class, // @todo
    QueryHandler\LicenceStatusRule\LicenceStatusRule::class                       => Standard::class, // @todo
    QueryHandler\Licence\Addresses::class                                         => Standard::class, // @todo
    QueryHandler\Licence\BusinessDetails::class                                   => Standard::class, // @todo
    QueryHandler\Licence\ConditionUndertaking::class                              => Standard::class, // @todo
    QueryHandler\Licence\ContinuationDetail::class                                => Standard::class, // @todo
    QueryHandler\Licence\ContinuationNotSoughtList::class                         => Standard::class, // @todo
    QueryHandler\Licence\EnforcementArea::class                                   => Standard::class, // @todo
    QueryHandler\Licence\GetList::class                                           => Standard::class, // @todo
    QueryHandler\Licence\Licence::class                                           => Standard::class, // @todo
    QueryHandler\Licence\LicenceByNumber::class                                   => Standard::class, // @todo
    QueryHandler\Licence\LicenceDecisions::class                                  => Standard::class, // @todo
    QueryHandler\Licence\LicenceRegisteredAddress::class                          => Standard::class, // @todo
    QueryHandler\Licence\Markers::class                                           => Standard::class, // @todo
    QueryHandler\Licence\OtherActiveLicences::class                               => Standard::class, // @todo
    QueryHandler\Licence\Overview::class                                          => Standard::class, // @todo
    QueryHandler\Licence\TaxiPhv::class                                           => Standard::class, // @todo
    QueryHandler\Licence\TypeOfLicence::class                                     => Standard::class, // @todo
    QueryHandler\Operator\BusinessDetails::class                                  => Standard::class, // @todo
    QueryHandler\Operator\UnlicensedBusinessDetails::class                        => Standard::class, // @todo
    QueryHandler\Opposition\Opposition::class                                     => Standard::class, // @todo
    QueryHandler\Opposition\OppositionList::class                                 => Standard::class, // @todo
    QueryHandler\Organisation\BusinessDetails::class                              => Standard::class, // @todo
    QueryHandler\Organisation\CpidOrganisation::class                             => Standard::class, // @todo
    QueryHandler\Organisation\Dashboard::class                                    => Standard::class, // @todo
    QueryHandler\Organisation\Organisation::class                                 => Standard::class, // @todo
    QueryHandler\Organisation\OutstandingFees::class                              => Standard::class, // @todo
    QueryHandler\OtherLicence\GetList::class                                      => Standard::class, // @todo
    QueryHandler\Processing\History::class                                        => Standard::class, // @todo
    QueryHandler\Processing\Note::class                                           => Standard::class, // @todo
    QueryHandler\Processing\NoteList::class                                       => Standard::class, // @todo
    QueryHandler\Publication\PendingList::class                                   => Standard::class, // @todo
    QueryHandler\Publication\PublicationLink::class                               => Standard::class, // @todo
    QueryHandler\Publication\PublicationLinkByTm::class                           => Standard::class, // @todo
    QueryHandler\Publication\PublicationLinkList::class                           => Standard::class, // @todo
    QueryHandler\Publication\Recipient::class                                     => Standard::class, // @todo
    QueryHandler\Publication\RecipientList::class                                 => Standard::class, // @todo
    QueryHandler\Search\Licence::class                                            => Standard::class, // @todo
    QueryHandler\System\FinancialStandingRate::class                              => Standard::class, // @todo
    QueryHandler\System\FinancialStandingRateList::class                          => Standard::class, // @todo
    QueryHandler\Task\Task::class                                                 => Standard::class, // @todo
    QueryHandler\Task\TaskDetails::class                                          => Standard::class, // @todo
    QueryHandler\Task\TaskList::class                                             => Standard::class, // @todo
    QueryHandler\TmEmployment\GetList::class                                      => Standard::class, // @todo
    QueryHandler\TmEmployment\GetSingle::class                                    => Standard::class, // @todo
    QueryHandler\TmQualification\TmQualification::class                           => Standard::class, // @todo
    QueryHandler\TmQualification\TmQualificationsList::class                      => Standard::class, // @todo
    QueryHandler\TmResponsibilities\GetDocumentsForResponsibilities::class        => Standard::class, // @todo
    QueryHandler\TmResponsibilities\TmResponsibilitiesList::class                 => Standard::class, // @todo
    QueryHandler\Tm\Documents::class                                              => Standard::class, // @todo
    QueryHandler\Transaction\Transaction::class                                   => Standard::class, // @todo
    QueryHandler\Transaction\TransactionByReference::class                        => Standard::class, // @todo
    QueryHandler\User\User::class                                                 => Standard::class, // @todo
    QueryHandler\User\UserList::class                                             => Standard::class, // @todo
    QueryHandler\User\UserListSelfserve::class                                    => Standard::class, // @todo
    QueryHandler\User\UserSelfserve::class                                        => Standard::class, // @todo
    QueryHandler\Variation\TypeOfLicence::class                                   => Standard::class, // @todo
    QueryHandler\Variation\Variation::class                                       => Standard::class, // @todo
    CommandHandler\Application\UpdatePrivateHireLicence::class                    => Standard::class, // @todo
    CommandHandler\Tm\UndoDisqualification::class                                 => Standard::class, // @todo
    QueryHandler\Bus\PaginatedRegistrationHistoryList::class                      => Standard::class, // @todo
    CliCommandHandler\RemoveReadAudit::class                                      => Standard::class, // @todo
    EmailCommandHandler\SendEmail::class                                          => Standard::class, // @todo
    EmailCommandHandler\ProcessInspectionRequestEmail::class                      => Standard::class, // @todo
    EmailCommandHandler\UpdateInspectionRequest::class                            => Standard::class, // @todo
    QueryHandler\Fee\GetLatestFeeType::class                                      => Standard::class, // @todo
];
// @codingStandardsIgnoreEnd

// Merge all other validation maps
foreach (glob(__DIR__ . '/validation-map/*.config.php') as $filename) {
    $map += include($filename);
}

return $map;
