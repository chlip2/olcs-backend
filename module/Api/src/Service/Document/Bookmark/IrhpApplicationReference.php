<?php

namespace Dvsa\Olcs\Api\Service\Document\Bookmark;

use Dvsa\Olcs\Api\Domain\Query\Bookmark\IrhpApplicationBundle as Qry;

/**
 * IrhpApplicationReference
 */
class IrhpApplicationReference extends SingleValueAbstract
{
    public const FIELD = 'applicationRef';
    public const SRCH_FLD_KEY = 'id';
    public const SRCH_VAL_KEY = 'irhpAppId';
    public const QUERY_CLASS = Qry::class;
}
