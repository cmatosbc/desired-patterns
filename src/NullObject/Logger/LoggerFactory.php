<?php

namespace DesiredPatterns\NullObject\Logger;

class LoggerFactory
{
    public static function create(?string $logFile = null): LoggerInterface
    {
        if ($logFile === null) {
            return new NullLogger();
        }

        try {
            $directory = dirname($logFile);
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            return new FileLogger($logFile);
        } catch (\Throwable) {
            return new NullLogger();
        }
    }
}
