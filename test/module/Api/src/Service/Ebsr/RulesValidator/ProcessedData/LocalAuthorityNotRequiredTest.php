<?php

namespace Dvsa\OlcsTest\Api\Service\Ebsr\RulesValidator\ProcessedData;

use Dvsa\Olcs\Api\Service\Ebsr\RulesValidator\ProcessedData\LocalAuthorityNotRequired;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Doctrine\Common\Collections\ArrayCollection;
use Dvsa\Olcs\Api\Entity\Bus\LocalAuthority as LaEntity;

/**
 * Class LocalAuthorityNotRequiredTest
 * @package Dvsa\OlcsTest\Api\Service\Ebsr\RulesValidator\ProcessedData
 */
class LocalAuthorityNotRequiredTest extends MockeryTestCase
{
    /**
     * tests whether local authorities not required are correctly identified
     *
     * @dataProvider isValidProvider
     *
     * @param ArrayCollection $la
     * @param ArrayCollection $naptan
     * @param $valid
     */
    public function testIsValid($la, $naptan, $valid)
    {
        $sut = new LocalAuthorityNotRequired();

        $value = [
            'localAuthoritys' => $la,
            'naptanAuthorities' => $naptan
        ];

        $this->assertEquals($valid, $sut->isValid($value));
    }

    /**
     * Provider for testIsValid
     *
     * @return array
     */
    public function isValidProvider()
    {
        $la1 = m::mock(LaEntity::class)->makePartial();
        $la1->setId(1);

        $la2 = m::mock(LaEntity::class)->makePartial();
        $la2->setId(2);

        $la3 = m::mock(LaEntity::class)->makePartial();
        $la3->setId(3);

        return [
            [new ArrayCollection([$la1, $la2]), new ArrayCollection([$la3, $la2]), false],
            [new ArrayCollection([$la3]), new ArrayCollection([$la3, $la2]), true],
            [new ArrayCollection([$la3]), new ArrayCollection([$la3]), true],
        ];
    }
}