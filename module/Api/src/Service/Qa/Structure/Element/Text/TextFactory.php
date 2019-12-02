<?php

namespace Dvsa\Olcs\Api\Service\Qa\Structure\Element\Text;

use Dvsa\Olcs\Api\Service\Qa\Structure\TranslateableText;

class TextFactory
{
    /**
     * Create and return a Text instance
     *
     * @param TranslateableText $label
     * @param TranslateableText $hint (optional)
     * @param string $value
     *
     * @return Text
     */
    public function create(TranslateableText $label, ?TranslateableText $hint = null, $value)
    {
        return new Text($label, $hint, $value);
    }
}
