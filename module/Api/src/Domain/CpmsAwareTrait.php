<?php

namespace Dvsa\Olcs\Api\Domain;

use Dvsa\Olcs\Api\Service\CpmsHelperInterface;

/**
 * CpmsAwareTrait
 */
trait CpmsAwareTrait
{
    protected $cpmsService;

    /**
     * @param CpmsHelperInterface $service
     */
    public function setCpmsService(CpmsHelperInterface $service)
    {
        $this->cpmsService = $service;
    }

    /**
     * @return CpmsHelperInterface
     */
    public function getCpmsService()
    {
        return $this->cpmsService;
    }
}
