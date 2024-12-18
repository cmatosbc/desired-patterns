<?php

namespace DesiredPatterns\Traits;

trait Singleton
{
    /**
     * Reference to singleton instance
     */
    private static ?self $instance = null;
    
    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {}

    /**
     * Creates or returns the singleton instance using
     * late static binding
     */
    final public static function getInstance(): static
    {
        return self::$instance ??= new static();
    }

    /**
     * Prevents cloning of the singleton instance
     * @throws \RuntimeException
     */
    private function __clone(): void
    {
        throw new \RuntimeException(
            'Singleton instance cannot be cloned');
    }

    /**
     * Prevents unserializing of the singleton instance
     * @throws \RuntimeException
     */
    public function __wakeup(): void
    {
        throw new \RuntimeException(
            'Singleton instance cannot be unserialized');
    }

    /**
     * Prevents serialization of the singleton instance
     * @throws \RuntimeException
     */
    public function __sleep(): array
    {
        throw new \RuntimeException(
            'Singleton instance cannot be serialized');
    }
}
