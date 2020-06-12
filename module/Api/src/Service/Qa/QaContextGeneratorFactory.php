<?php

namespace Dvsa\Olcs\Api\Service\Qa;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QaContextGeneratorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return QaContextGenerator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new QaContextGenerator(
            $serviceLocator->get('RepositoryServiceManager')->get('ApplicationStep'),
            $serviceLocator->get('QaEntityProvider'),
            $serviceLocator->get('QaContextFactory')
        );
    }
}
