<?php

namespace Dvsa\Olcs\Api\Service\Qa\Structure\QuestionText\Custom\EcmtShortTerm;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PermitUsageGeneratorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return PermitUsageGenerator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PermitUsageGenerator(
            $serviceLocator->get('QaQuestionTextGenerator')
        );
    }
}