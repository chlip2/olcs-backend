<?php

namespace Dvsa\OlcsTest\Api\Service\Ebsr\XmlValidator;

use Dvsa\Olcs\Api\Service\Ebsr\XmlValidator\Operator;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Class OperatorTest
 * @package OlcsTest\Ebsr\src\Validator\Structure
 */
class OperatorTest extends TestCase
{
    /**
     * @param $xml
     * @param $valid
     * @dataProvider isValidProvider
     */
    public function testIsValid($xml, $valid)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $sut = new Operator();

        $this->assertEquals($valid, $sut->isValid($dom));
    }

    public function isValidProvider()
    {
        return [
            ['<Operators></Operators>', false],
            [
                '<Operators><LicensedOperator></LicensedOperator><LicensedOperator></LicensedOperator></Operators>',
                false
            ],
            ['<LicensedOperators><LicensedOperator></LicensedOperator></LicensedOperators>', true]
        ];
    }
}
