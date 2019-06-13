<?php

namespace Dvsa\OlcsTest\Api\Service\Qa\Structure\QuestionText\Custom;

use Dvsa\Olcs\Api\Service\Qa\Structure\QuestionText\Custom\EcmtRemovalNoOfPermitsGenerator;
use Dvsa\Olcs\Api\Service\Qa\Structure\QuestionText\QuestionTextGenerator;
use Dvsa\Olcs\Api\Service\Qa\Structure\QuestionText\QuestionTextGeneratorContext;
use Dvsa\Olcs\Api\Domain\Repository\FeeType as FeeTypeRepository;
use Dvsa\Olcs\Api\Entity\Fee\FeeType as FeeTypeEntity;
use Dvsa\Olcs\Api\Service\Qa\Structure\QuestionText\QuestionText;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * EcmtRemovalNoOfPermitsGeneratorTest
 *
 * @author Jonathan Thomas <jonathan@opalise.co.uk>
 */
class EcmtRemovalNoOfPermitsGeneratorTest extends MockeryTestCase
{
    public function testGenerate()
    {
        $feePerPermit = '18.00';

        $feeTypeEntity = m::mock(FeeTypeEntity::class);

        $questionText = m::mock(QuestionText::class);
        $questionText->shouldReceive('getGuidance->getTranslateableText->setParameter')
            ->with(0, $feePerPermit)
            ->once();

        $questionTextGeneratorContext = m::mock(QuestionTextGeneratorContext::class);
        $questionTextGeneratorContext->shouldReceive('getIrhpApplicationEntity->getFeePerPermit')
            ->with(null, $feeTypeEntity)
            ->andReturn($feePerPermit);

        $questionTextGenerator = m::mock(QuestionTextGenerator::class);
        $questionTextGenerator->shouldReceive('generate')
            ->with($questionTextGeneratorContext)
            ->andReturn($questionText);

        $feeTypeRepo = m::mock(FeeTypeRepository::class);
        $feeTypeRepo->shouldReceive('getLatestByProductReference')
            ->with(FeeTypeEntity::FEE_TYPE_ECMT_REMOVAL_ISSUE_PRODUCT_REF)
            ->andReturn($feeTypeEntity);

        $ecmtRemovalNoOfPermitsGenerator = new EcmtRemovalNoOfPermitsGenerator(
            $questionTextGenerator,
            $feeTypeRepo
        );

        $this->assertSame(
            $questionText,
            $ecmtRemovalNoOfPermitsGenerator->generate($questionTextGeneratorContext)
        );
    }
}
