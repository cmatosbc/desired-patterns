<?php

namespace DesiredPatterns\Pool;

class PoolFactory
{
    private static array $pools = [];

    public static function getPool(
        string $poolName,
        string $className,
        array $config = []
    ): ObjectPool {
        if (!isset(self::$pools[$poolName])) {
            self::$pools[$poolName] = new ObjectPool(
                className: $className,
                minInstances: $config['min_instances'] ?? 0,
                maxInstances: $config['max_instances'] ?? 10,
                constructorArgs: $config['constructor_args'] ?? []
            );
        }

        return self::$pools[$poolName];
    }
}
