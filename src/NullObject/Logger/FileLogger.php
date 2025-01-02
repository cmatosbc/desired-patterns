<?php

namespace DesiredPatterns\NullObject\Logger;

class FileLogger implements LoggerInterface
{
    /** @var array<array{level: string, message: string, context: array}> */
    private array $logs = [];

    public function __construct(
        private readonly string $logFile
    ) {
    }

    public function log(string $level, string $message, array $context = []): void
    {
        $logEntry = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $this->logs[] = $logEntry;

        file_put_contents(
            $this->logFile,
            json_encode($logEntry) . PHP_EOL,
            FILE_APPEND
        );
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function isNull(): bool
    {
        return false;
    }
}
