<?php

namespace Dvsa\Olcs\Api\Service\Submission\Sections;

use Dvsa\Olcs\Api\Entity\Cases\Cases as CasesEntity;
use Dvsa\Olcs\Api\Service\Submission\Sections\AbstractSection;

/**
 * Class CaseOutline
 * @package Dvsa\Olcs\Api\Service\Submission\Sections
 * @author Shaun Lizzio <shaun.lizzio@valtech.co.uk>
 */
final class CaseOutline extends AbstractSection
{
    public function generateSection(CasesEntity $case, \ArrayObject $context = null)
    {
        return ['data' => ['text' => $case->getDescription()]];
    }
}
