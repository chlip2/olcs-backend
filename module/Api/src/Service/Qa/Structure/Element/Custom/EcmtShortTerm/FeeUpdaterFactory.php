<?php

namespace Dvsa\Olcs\Api\Service\Qa\Structure\Element\Custom\EcmtShortTerm;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FeeUpdaterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return FeeUpdater
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new FeeUpdater(
            $serviceLocator->get('RepositoryServiceManager')->get('FeeType'),
            $serviceLocator->get('CqrsCommandCreator'),
            $serviceLocator->get('CommandHandlerManager'),
            $serviceLocator->get('CommonCurrentDateTimeFactory')
        );
    }
}
