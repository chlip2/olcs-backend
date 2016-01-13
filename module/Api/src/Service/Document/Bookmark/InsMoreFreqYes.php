<?php

namespace Dvsa\Olcs\Api\Service\Document\Bookmark;

use Dvsa\Olcs\Api\Service\Document\Bookmark\Base\DynamicBookmark;
use Dvsa\Olcs\Api\Domain\Query\Bookmark\LicenceBundle as Qry;

/**
 * InsMoreFreqYes bookmark
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class InsMoreFreqYes extends DynamicBookmark
{
    public function getQuery(array $data)
    {
        return Qry::create(['id' => $data['licence']]);
    }

    public function render()
    {
        if ($this->data['safetyInsVaries']) {
            return 'X';
        }
        return '';
    }
}
