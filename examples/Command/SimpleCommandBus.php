<?php

namespace Examples\Command;

use DesiredPatterns\Contracts\CommandBusContract;
use DesiredPatterns\Contracts\CommandContract;
use DesiredPatterns\Contracts\CommandHandlerContract;
use RuntimeException;

class SimpleCommandBus implements CommandBusContract
{
    /**
     * @var array<class-string, CommandHandlerContract>
     */
    private array $handlers = [];

    public function register(string $commandClass, CommandHandlerContract $handler): self
    {
        $this->handlers[$commandClass] = $handler;
        return $this;
    }

    public function dispatch(CommandContract $command): mixed
    {
        $handler = $this->handlers[$command->getName()] 
            ?? throw new RuntimeException(
                sprintf('No handler found for command %s', $command->getName())
            );
            
        return $handler->handle($command);
    }
}
