<?php

namespace Dvsa\Olcs\Api\Service\Permits\Allocate;

use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitRange;

interface RangeMatchingCriteriaInterface
{
    /**
     * Whether the specified range instance matches the stored criteria
     *
     * @param IrhpPermitRange $irhpPermitRange
     *
     * @return bool
     */
    public function matches(IrhpPermitRange $irhpPermitRange);
}
