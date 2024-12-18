<?php

namespace DesiredPatterns\Contracts;

interface CommandBusContract
{
    /**
     * Dispatch a command
     * 
     * @template T
     * @param CommandContract $command
     * @return T
     */
    public function dispatch(CommandContract $command): mixed;

    /**
     * Register a handler for a command
     * 
     * @param class-string<CommandContract> $commandClass
     * @param CommandHandlerContract $handler
     */
    public function register(string $commandClass, CommandHandlerContract $handler): self;
}
