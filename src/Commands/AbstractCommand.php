<?php

namespace DesiredPatterns\Commands;

use DesiredPatterns\Contracts\CommandContract;

abstract class AbstractCommand implements CommandContract
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        protected readonly array $payload = []
    ) {
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * Default implementation uses class name
     */
    public function getName(): string
    {
        return static::class;
    }
}
