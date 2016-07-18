<?php

namespace Dvsa\Olcs\Api\Service\Data;

use Common\Service\Entity\Exceptions\UnexpectedResponseException;
use Olcs\Logging\Log\Logger;
use Zend\Server\Client as ServerClient;
use Dvsa\Olcs\Api\Domain\Exception\NysiisException;

/**
 * Class IrfoPsvAuthType
 */
class Nysiis
{
    /**
     * @var array
     */
    private $nysiisConfig;

    /**
     * @var \Zend\Soap\Client
     */
    private $soapClient;

    /**
     * Nysiis constructor. Initiates a SOAP client and configuration
     *
     * @param $soapClient
     * @param $config
     */
    public function __construct($soapClient, $config)
    {
        $this->soapClient = $soapClient;
        $this->nysiisConfig = $config;
        if (!$this->soapClient instanceof ServerClient) {
            throw new NysiisException('Unable to make soap request : Invalid SOAP client');
        }
    }

    /**
     * Make SOAP request to NYSIIS to extract the firstName and familyName.
     * @to-do When WSDL/SOAP server known, do the connection proper.
     * For now just throw an exception
     *
     * @param   array   $params
     * @return  mixed
     * @throws NysiisException
     */
    public function getNysiisSearchKeys($params)
    {
        $result = $this->getSoapClient()->GetNYSIISSearchKeys(
            $params['nysiisForename'],
            $params['nysiisFamilyname']
        );

        return $result;
    }

    /**
     * @return array
     */
    public function getNysiisConfig()
    {
        return $this->nysiisConfig;
    }

    /**
     * @return \Zend\Soap\Client
     */
    public function getSoapClient()
    {
        return $this->soapClient;
    }
}