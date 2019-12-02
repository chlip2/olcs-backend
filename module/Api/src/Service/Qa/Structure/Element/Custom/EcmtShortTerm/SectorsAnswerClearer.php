<?php

namespace Dvsa\Olcs\Api\Service\Qa\Structure\Element\Custom\EcmtShortTerm;

use Dvsa\Olcs\Api\Domain\Repository\IrhpApplication as IrhpApplicationRepository;
use Dvsa\Olcs\Api\Entity\Generic\ApplicationStep as ApplicationStepEntity;
use Dvsa\Olcs\Api\Entity\Permits\IrhpApplication as IrhpApplicationEntity;
use Dvsa\Olcs\Api\Service\Qa\Structure\Element\AnswerClearerInterface;

class SectorsAnswerClearer implements AnswerClearerInterface
{
    /** @var IrhpApplicationRepository */
    private $irhpApplicationRepo;

    /**
     * Create service instance
     *
     * @param IrhpApplicationRepository $irhpApplicationRepo
     *
     * @return SectorsAnswerClearer
     */
    public function __construct(IrhpApplicationRepository $irhpApplicationRepo)
    {
        $this->irhpApplicationRepo = $irhpApplicationRepo;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(ApplicationStepEntity $applicationStepEntity, IrhpApplicationEntity $irhpApplicationEntity)
    {
        $irhpApplicationEntity->clearSectors();
        $this->irhpApplicationRepo->save($irhpApplicationEntity);
    }
}
