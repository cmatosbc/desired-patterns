<?php

namespace DesiredPatterns\Traits;

use DesiredPatterns\Contracts\CommandContract;

trait CommandHandlerTrait
{
    /**
     * Check if the handler supports the given command
     *
     * @param class-string $commandClass Command class this handler supports
     */
    protected function supports(CommandContract $command): bool
    {
        $commandClass = $this->supportedCommandClass();
        return $command instanceof $commandClass;
    }

    /**
     * Get the command class this handler supports
     *
     * @return class-string
     */
    abstract protected function supportedCommandClass(): string;
}
