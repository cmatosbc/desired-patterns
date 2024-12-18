<?php

namespace DesiredPatterns\Commands;

use DesiredPatterns\Contracts\CommandContract;
use DesiredPatterns\Contracts\CommandHandlerContract;

abstract class AbstractCommandHandler implements CommandHandlerContract
{
    /**
     * @template T
     * @param CommandContract $command
     * @return T
     */
    public function handle(CommandContract $command): mixed
    {
        if (!$this->supports($command)) {
            throw new \RuntimeException(sprintf(
                'Handler %s does not support command %s',
                static::class,
                $command::class
            ));
        }

        return $this->process($command);
    }

    /**
     * Process the command
     *
     * @template T
     * @param CommandContract $command
     * @return T
     */
    abstract protected function process(CommandContract $command): mixed;

    /**
     * Check if this handler supports the given command
     */
    abstract protected function supports(CommandContract $command): bool;
}
