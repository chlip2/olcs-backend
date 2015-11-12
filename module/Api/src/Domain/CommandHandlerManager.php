<?php

/**
 * Command Handler Manager
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain;

use Dvsa\Olcs\Api\Domain\CommandHandler\TransactioningCommandHandler;
use Dvsa\Olcs\Api\Domain\Exception\ForbiddenException;
use Olcs\Logging\Log\Logger;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Domain\CommandHandler\CommandHandlerInterface;
use Zend\ServiceManager\Exception\RuntimeException;
use Dvsa\Olcs\Api\Domain\ValidationHandler\ValidationHandlerInterface;

/**
 * Command Handler Manager
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class CommandHandlerManager extends AbstractPluginManager implements CommandHandlerInterface
{
    public function __construct(ConfigInterface $config = null)
    {
        $this->setShareByDefault(false);

        if ($config) {
            $config->configureServiceManager($this);
        }
    }

    public function handleCommand(CommandInterface $command)
    {
        $commandFqcn = get_class($command);

        $commandHandler = $this->get($commandFqcn);

        if ($commandHandler instanceof TransactioningCommandHandler) {
            $validateCommandHandler = $commandHandler->getWrapped();
        } else {
            $validateCommandHandler = $commandHandler;
        }

        Logger::debug(
            'Command Received: ' . $commandFqcn,
            ['data' => ['commandData' => $command->getArrayCopy()]]
        );

        $commandHandlerFqcn = get_class($validateCommandHandler);

        $this->validateDto($command, $commandHandlerFqcn);

        $response = $commandHandler->handleCommand($command);

        Logger::debug(
            'Command Handler Response: ' . $commandHandlerFqcn,
            ['data' => ['response' => (array)$response]]
        );

        return $response;
    }

    public function validatePlugin($plugin)
    {
        if (!($plugin instanceof CommandHandlerInterface)) {
            throw new RuntimeException('Command handler does not implement CommandHandlerInterface');
        }
    }

    protected function validateDto($dto, $queryHandlerFqcl)
    {
        $vhm = $this->getServiceLocator()->get('ValidationHandlerManager');

        /** @var ValidationHandlerInterface $validationHandler */
        $validationHandler = $vhm->get($queryHandlerFqcl);

        if (!$validationHandler->isValid($dto)) {
            throw new ForbiddenException('You do not have access to this resource');
        }
    }
}
