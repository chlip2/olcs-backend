<?php

/**
 * Goods Discs Set isPrinting off and discNo
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\Repository\Query\Discs;

use Dvsa\Olcs\Api\Domain\Repository\Query\AbstractRawQuery;
use Dvsa\Olcs\Api\Entity\Vehicle\GoodsDisc;
use Dvsa\Olcs\Api\Domain\Util\DateTime\DateTime;

/**
 * Goods Discs Set isPrinting off and discNo
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class GoodsDiscsSetIsPrintingOffAndDiscNo extends AbstractRawQuery
{
    protected $templateMap = [
        'gd' => GoodsDisc::class
    ];

    protected $queryTemplate = 'UPDATE {gd},
       (SELECT @n := :startNumber - 1) m
          SET {gd.isPrinting} = 0, {gd.discNo} = @n := @n + 1, {gd.issuedDate} = :issuedDate,
            {gd.lastModifiedOn} = NOW(), {gd.lastModifiedBy} = :currentUserId
          WHERE {gd.id} IN (:ids)';

    /**
     * {@inheritdoc}
     */
    protected function getParams()
    {
        $today = new DateTime();

        return [
            'issuedDate' => $today->format('Y-m-d H:i:s')
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getParamTypes()
    {
        return [
            'issuedDate' => \Pdo::PARAM_STR
        ];
    }
}
