<?php

namespace DesiredPatterns;

class Registry
{
    /**
     * Storage for registered objects
     */
    private static array $storage = [];

    /**
     * Store an object in the registry
     * 
     * @throws \RuntimeException if key already exists
     */
    public static function set(string $key, mixed $value): mixed
    {
        if (isset(self::$storage[$key])) {
            throw new \RuntimeException("Key '$key' already exists in registry");
        }
        
        self::$storage[$key] = $value;
        return $value;
    }

    /**
     * Get an object from the registry
     * 
     * @throws \RuntimeException if key doesn't exist
     */
    public static function get(string $key): mixed
    {
        if (!isset(self::$storage[$key])) {
            throw new \RuntimeException("No entry found for key '$key'");
        }
        
        return self::$storage[$key];
    }

    /**
     * Check if a key exists in the registry
     */
    public static function has(string $key): bool
    {
        return isset(self::$storage[$key]);
    }

    /**
     * Remove an object from the registry
     * 
     * @throws \RuntimeException if key doesn't exist
     */
    public static function remove(string $key): void
    {
        if (!isset(self::$storage[$key])) {
            throw new \RuntimeException("Cannot remove non-existent key '$key'");
        }
        
        unset(self::$storage[$key]);
    }

    /**
     * Get all keys in the registry
     */
    public static function keys(): array
    {
        return array_keys(self::$storage);
    }

    /**
     * Clear all entries from the registry
     */
    public static function clear(): void
    {
        self::$storage = [];
    }

    /**
     * Prevent instantiation
     */
    private function __construct() {}
}
