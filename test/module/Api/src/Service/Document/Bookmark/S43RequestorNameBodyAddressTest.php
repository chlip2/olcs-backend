<?php

/**
 * Class
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */

namespace Dvsa\OlcsTest\Api\Service\Document\Bookmark;

use Dvsa\Olcs\Api\Service\Document\Bookmark as BookmarkNs;
use PHPUnit\Framework\TestCase as TestCase;

/**
 * Class
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class S43RequestorNameBodyAddressTest extends TestCase
{
    public function testAlias()
    {
        $sut = new BookmarkNs\S43RequestorNameBodyAddress();

        $this->assertInstanceOf('Dvsa\Olcs\Api\Service\Document\Bookmark\StatementNameBodyAddress', $sut);
    }
}
