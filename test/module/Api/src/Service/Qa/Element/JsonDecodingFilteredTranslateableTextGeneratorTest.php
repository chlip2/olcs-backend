<?php

namespace Dvsa\OlcsTest\Api\Service\Qa\Element;

use Dvsa\Olcs\Api\Service\Qa\Element\FilteredTranslateableText;
use Dvsa\Olcs\Api\Service\Qa\Element\FilteredTranslateableTextGenerator;
use Dvsa\Olcs\Api\Service\Qa\Element\JsonDecodingFilteredTranslateableTextGenerator;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * JsonDecodingFilteredTranslateableTextGeneratorTest
 *
 * @author Jonathan Thomas <jonathan@opalise.co.uk>
 */
class JsonDecodingFilteredTranslateableTextGeneratorTest extends MockeryTestCase
{
    private $filteredTranslateableTextGenerator;

    private $jsonDecodingFilteredTranslateableTextGenerator;

    public function setUp()
    {
        $this->filteredTranslateableTextGenerator = m::mock(FilteredTranslateableTextGenerator::class);

        $this->jsonDecodingFilteredTranslateableTextGenerator = new JsonDecodingFilteredTranslateableTextGenerator(
            $this->filteredTranslateableTextGenerator
        );
    }

    public function testFilteredTranslateableTextWhenString()
    {
        $value = '{"value1": "test1", "value2": "test2", "value3": ["test3", "test4"]}';

        $decodedValue = [
            'value1' => 'test1',
            'value2' => 'test2',
            'value3' => [
                'test3',
                'test4'
            ]
        ];

        $filteredTranslateableText = m::mock(FilteredTranslateableText::class);

        $this->filteredTranslateableTextGenerator->shouldReceive('generate')
            ->with($decodedValue)
            ->andReturn($filteredTranslateableText);

        $this->assertSame(
            $filteredTranslateableText,
            $this->jsonDecodingFilteredTranslateableTextGenerator->generate($value)
        );
    }

    public function testReturnNullWhenNotString()
    {
        $this->assertNull(
            $this->jsonDecodingFilteredTranslateableTextGenerator->generate(null)
        );
    }
}
