<?php

namespace DesiredPatterns\Registry;

/**
 * Registry Pattern Implementation
 *
 * The Registry pattern provides a global point of access to data throughout an application.
 * It is implemented as a singleton to ensure only one instance exists.
 */
class Registry
{
    private static array $data = [];

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct()
    {
    }

    /**
     * Reset the registry (mainly for testing purposes)
     */
    public static function reset(): void
    {
        self::$data = [];
    }

    /**
     * Set a value in the registry
     */
    public static function set(string $key, mixed $value): void
    {
        self::$data[$key] = $value;
    }

    /**
     * Get a value from the registry
     *
     * @throws \RuntimeException if the key doesn't exist
     */
    public static function get(string $key): mixed
    {
        if (!self::has($key)) {
            throw new \RuntimeException("Key '{$key}' not found in registry");
        }

        return self::$data[$key];
    }

    /**
     * Check if a key exists in the registry
     */
    public static function has(string $key): bool
    {
        return array_key_exists($key, self::$data);
    }

    /**
     * Remove a key from the registry
     */
    public static function remove(string $key): void
    {
        unset(self::$data[$key]);
    }

    /**
     * Clear all values from the registry
     */
    public static function clear(): void
    {
        self::$data = [];
    }

    /**
     * Get all keys in the registry
     */
    public static function keys(): array
    {
        return array_keys(self::$data);
    }
}
