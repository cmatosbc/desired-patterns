<?php

namespace DesiredPatterns\NullObject\Logger;

use DesiredPatterns\NullObject\NullableInterface;

interface LoggerInterface extends NullableInterface
{
    /**
     * Log a message with optional context
     *
     * @param string $level   Log level (debug, info, warning, error)
     * @param string $message Log message
     * @param array  $context Additional context data
     */
    public function log(string $level, string $message, array $context = []): void;

    /**
     * Get all logged messages
     *
     * @return array<array{level: string, message: string, context: array}>
     */
    public function getLogs(): array;
}
