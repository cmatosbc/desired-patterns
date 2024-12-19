<?php

namespace DesiredPatterns\Commands;

use DesiredPatterns\Contracts\CommandContract;

/**
 * Abstract class for command implementations.
 *
 * This class provides a base implementation for commands, enforcing the structure of a command
 * with a payload and a name. It implements the CommandContract interface, ensuring that all
 * commands have a defined payload and name.
 */
abstract class AbstractCommand implements CommandContract
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        protected readonly array $payload = []
    ) {
    }

    /**
     * Retrieves the payload of the command.
     *
     * @return array<string, mixed> The command payload.
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * Retrieves the name of the command.
     *
     * The default implementation uses the class name to identify the command.
     *
     * @return string The name of the command.
     */
    public function getName(): string
    {
        return static::class;
    }
}
