<?php

namespace DesiredPatterns\Traits;

trait Multiton
{
    /**
     * Store of multiton instances
     */
    private static array $instances = [];
    
    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {}

    /**
     * Creates or returns a named instance using late static binding
     */
    final public static function getInstance(string $key): static
    {
        return self::$instances[$key] ??= new static($key);
    }

    /**
     * Check if a named instance exists
     */
    final public static function hasInstance(string $key): bool
    {
        return isset(self::$instances[$key]);
    }

    /**
     * Remove a named instance
     */
    final public static function removeInstance(string $key): void
    {
        unset(self::$instances[$key]);
    }

    /**
     * Get all instance keys
     */
    final public static function getInstanceKeys(): array
    {
        return array_keys(self::$instances);
    }

    /**
     * Prevents cloning of instances
     * @throws \RuntimeException
     */
    private function __clone(): void
    {
        throw new \RuntimeException(
            'Multiton instances cannot be cloned');
    }

    /**
     * Prevents unserializing of instances
     * @throws \RuntimeException
     */
    public function __wakeup(): void
    {
        throw new \RuntimeException(
            'Multiton instances cannot be unserialized');
    }

    /**
     * Prevents serialization of instances
     * @throws \RuntimeException
     */
    public function __sleep(): array
    {
        throw new \RuntimeException(
            'Multiton instances cannot be serialized');
    }
}
