<?php

namespace DesiredPatterns\NullObject\Logger;

use DesiredPatterns\NullObject\AbstractNullObject;

class NullLogger extends AbstractNullObject implements LoggerInterface
{
    public function log(string $level, string $message, array $context = []): void
    {
        // Do nothing
    }

    public function getLogs(): array
    {
        return [];
    }
}
