<?php

namespace Dvsa\OlcsTest\Api\Service\Permits\AnswersSummary;

use DateTime;
use Dvsa\Olcs\Api\Domain\Repository\IrhpPermitStock as IrhpPermitStockRepository;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitApplication as IrhpPermitApplicationEntity;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitStock as IrhpPermitStockEntity;
use Dvsa\Olcs\Api\Service\Permits\AnswersSummary\AnswersSummary;
use Dvsa\Olcs\Api\Service\Permits\AnswersSummary\AnswersSummaryRow;
use Dvsa\Olcs\Api\Service\Permits\AnswersSummary\AnswersSummaryRowFactory;
use Dvsa\Olcs\Api\Service\Permits\AnswersSummary\AnswersSummaryRowsAdderInterface;
use Dvsa\Olcs\Api\Service\Permits\AnswersSummary\BilateralIpaAnswersSummaryRowsAdder;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Zend\View\Renderer\RendererInterface;

/**
 * BilateralIpaAnswersSummaryRowsAdderTest
 */
class BilateralIpaAnswersSummaryRowsAdderTest extends MockeryTestCase
{
    /**
     * @dataProvider dpAddRows
     */
    public function testAddRows($isSnapshot, $availableStocks, $expectedSlug)
    {
        $countryId = 'NO';
        $periodNameKey = 'period-key';
        $periodFormattedAnswer = 'formatted period';
        $periodAnswersSummaryRow = m::mock(AnswersSummaryRow::class);

        $answersSummary = m::mock(AnswersSummary::class);
        $answersSummary->shouldReceive('addRow')
            ->with($periodAnswersSummaryRow)
            ->once();

        $irhpPermitStock = m::mock(IrhpPermitStockEntity::class);
        $irhpPermitStock->shouldReceive('getPeriodNameKey')
            ->withNoArgs()
            ->andReturn($periodNameKey)
            ->shouldReceive('getCountry->getId')
            ->withNoArgs()
            ->andReturn($countryId);

        $irhpPermitApplication = m::mock(IrhpPermitApplicationEntity::class);
        $irhpPermitApplication->shouldReceive('getIrhpPermitWindow->getIrhpPermitStock')
            ->withNoArgs()
            ->andReturn($irhpPermitStock);

        $answersSummaryRowFactory = m::mock(AnswersSummaryRowFactory::class);
        $answersSummaryRowFactory->shouldReceive('create')
            ->with('permits.page.bilateral.which-period-required', $periodFormattedAnswer, $expectedSlug)
            ->once()
            ->andReturn($periodAnswersSummaryRow);

        $expectedPeriodTemplateVariables = [
            'answer' => $periodNameKey,
        ];

        $viewRenderer = m::mock(RendererInterface::class);
        $viewRenderer->shouldReceive('render')
            ->with('answers-summary/generic', $expectedPeriodTemplateVariables)
            ->once()
            ->andReturn($periodFormattedAnswer);

        $answersSummaryRowsAdderInterface = m::mock(AnswersSummaryRowsAdderInterface::class);
        $answersSummaryRowsAdderInterface->shouldReceive('addRows')
            ->with($answersSummary, $irhpPermitApplication, $isSnapshot)
            ->once();

        $irhpPermitStockRepository = m::mock(IrhpPermitStockRepository::class);
        $irhpPermitStockRepository->shouldReceive('fetchOpenBilateralStocksByCountry')
            ->with($countryId, m::type(DateTime::class))
            ->times($isSnapshot ? 0 : 1)
            ->andReturn($availableStocks);

        $sut = new BilateralIpaAnswersSummaryRowsAdder(
            $answersSummaryRowFactory,
            $viewRenderer,
            $answersSummaryRowsAdderInterface,
            $irhpPermitStockRepository
        );

        $sut->addRows($answersSummary, $irhpPermitApplication, $isSnapshot);
    }

    public function dpAddRows()
    {
        $noStocks = [];
        $oneStock = [['id' => 1]];
        $multipleStocks = [['id' => 1], ['id' => 2]];

        return [
            [true, $noStocks, null],
            [true, $oneStock, null],
            [true, $multipleStocks, null],
            [false, $noStocks, null],
            [false, $oneStock, null],
            [false, $multipleStocks, 'period'],
        ];
    }
}
