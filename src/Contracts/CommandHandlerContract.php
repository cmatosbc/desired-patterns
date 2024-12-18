<?php

namespace DesiredPatterns\Contracts;

interface CommandHandlerContract
{
    /**
     * Handle a command
     *
     * @template T
     * @param CommandContract $command
     * @return T
     */
    public function handle(CommandContract $command): mixed;
}
