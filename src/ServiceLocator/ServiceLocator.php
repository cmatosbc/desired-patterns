<?php

namespace DesiredPatterns\ServiceLocator;

use DesiredPatterns\Contracts\ServiceProviderContract;

/**
 * Service Locator Pattern Implementation
 * 
 * The Service Locator pattern is a design pattern used in software development to encapsulate
 * the processes involved in obtaining a service with a strong abstraction layer.
 */
class ServiceLocator
{
    /**
     * Array of registered service factories
     */
    private array $factories = [];

    /**
     * Array of resolved service instances
     */
    private array $instances = [];

    /**
     * Array of registered service providers
     * 
     * @var ServiceProviderContract[]
     */
    private array $providers = [];

    /**
     * Register a new service factory
     */
    public function register(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
        unset($this->instances[$id]);
    }

    /**
     * Register a service provider
     */
    public function addServiceProvider(ServiceProviderContract $provider): void
    {
        $this->providers[] = $provider;
        $provider->register($this);
    }

    /**
     * Resolve a service
     * 
     * @throws \RuntimeException if the service is not found
     */
    public function resolve(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!$this->has($id)) {
            throw new \RuntimeException("Service '{$id}' not found");
        }

        return $this->instances[$id] = ($this->factories[$id])();
    }

    /**
     * Check if a service exists
     */
    public function has(string $id): bool
    {
        if (isset($this->factories[$id])) {
            return true;
        }

        foreach ($this->providers as $provider) {
            if ($provider->provides($id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extend an existing service
     * 
     * @throws \RuntimeException if the service doesn't exist
     */
    public function extend(string $id, callable $callback): void
    {
        if (!$this->has($id)) {
            throw new \RuntimeException("Cannot extend non-existent service '{$id}'");
        }

        $previous = $this->factories[$id];

        $this->factories[$id] = function () use ($previous, $callback) {
            return $callback($previous());
        };

        unset($this->instances[$id]);
    }

    /**
     * Unregister a service
     */
    public function unregister(string $id): void
    {
        unset($this->factories[$id], $this->instances[$id]);
    }
}
