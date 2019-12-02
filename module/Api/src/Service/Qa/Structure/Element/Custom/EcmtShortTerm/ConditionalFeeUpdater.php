<?php

namespace Dvsa\Olcs\Api\Service\Qa\Structure\Element\Custom\EcmtShortTerm;

use Dvsa\Olcs\Api\Entity\Permits\IrhpApplication as IrhpApplicationEntity;

class ConditionalFeeUpdater
{
    /** @var FeeUpdater */
    private $feeUpdater;

    /**
     * Create service instance
     *
     * @param FeeUpdater $feeUpdater
     *
     * @return ConditionalFeeUpdater
     */
    public function __construct(FeeUpdater $feeUpdater)
    {
        $this->feeUpdater = $feeUpdater;
    }

    /**
     * Update fees for a short term ecmt application if the permit count has changed
     *
     * @param IrhpApplicationEntity $irhpApplicationEntity
     * @param int $oldTotal
     */
    public function updateFees(IrhpApplicationEntity $irhpApplication, $oldTotal)
    {
        $irhpPermitApplication = $irhpApplication->getFirstIrhpPermitApplication();

        $newTotal = $irhpPermitApplication->getTotalEmissionsCategoryPermitsRequired();
        if ($newTotal != $oldTotal) {
            $this->feeUpdater->updateFees($irhpApplication, $newTotal);
        }
    }
}
