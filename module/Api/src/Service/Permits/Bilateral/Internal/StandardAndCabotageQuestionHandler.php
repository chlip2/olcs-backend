<?php

namespace Dvsa\Olcs\Api\Service\Permits\Bilateral\Internal;

use Dvsa\Olcs\Api\Entity\Generic\Answer;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitApplication;
use Dvsa\Olcs\Api\Service\Qa\AnswerSaver\GenericAnswerWriter;
use Dvsa\Olcs\Api\Service\Qa\QaContext;

class StandardAndCabotageQuestionHandler implements QuestionHandlerInterface
{
    /** @var PermitUsageSelectionGenerator */
    private $permitUsageSelectionGenerator;

    /** @var BilateralRequiredGenerator */
    private $bilateralRequiredGenerator;

    /** @var GenericAnswerWriter */
    private $genericAnswerWriter;

    /**
     * Create service instance
     *
     * @param PermitUsageSelectionGenerator $permitUsageSelectionGenerator
     * @param BilateralRequiredGenerator $bilateralRequiredGenerator
     * @param GenericAnswerWriter $genericAnswerWriter
     *
     * @return StandardAndCabotageQuestionHandler
     */
    public function __construct(
        PermitUsageSelectionGenerator $permitUsageSelectionGenerator,
        BilateralRequiredGenerator $bilateralRequiredGenerator,
        GenericAnswerWriter $genericAnswerWriter
    ) {
        $this->permitUsageSelectionGenerator = $permitUsageSelectionGenerator;
        $this->bilateralRequiredGenerator = $bilateralRequiredGenerator;
        $this->genericAnswerWriter = $genericAnswerWriter;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(QaContext $qaContext, array $requiredPermits)
    {
        $permitUsageSelection = $this->permitUsageSelectionGenerator->generate($requiredPermits);
        $bilateralRequired = $this->bilateralRequiredGenerator->generate($requiredPermits, $permitUsageSelection);

        $requiredStandard = $bilateralRequired[IrhpPermitApplication::BILATERAL_STANDARD_REQUIRED];
        $requiredCabotage = $bilateralRequired[IrhpPermitApplication::BILATERAL_CABOTAGE_REQUIRED];

        if ($requiredStandard && $requiredCabotage) {
            $answer = Answer::BILATERAL_STANDARD_AND_CABOTAGE;
        } elseif ($requiredStandard) {
            $answer = Answer::BILATERAL_STANDARD_ONLY;
        } else {
            $answer = Answer::BILATERAL_CABOTAGE_ONLY;
        }

        $this->genericAnswerWriter->write($qaContext, $answer);
    }
}
