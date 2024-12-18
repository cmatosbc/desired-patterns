<?php

namespace DesiredPatterns;

use DesiredPatterns\Contracts\ServiceContract;
use DesiredPatterns\Contracts\ServiceProviderContract;
use RuntimeException;

class ServiceLocator
{
    /**
     * Registered services
     */
    private array $services = [];
    
    /**
     * Service providers
     */
    private array $providers = [];
    
    /**
     * Service factories
     */
    private array $factories = [];

    /**
     * Register a service instance
     */
    public function addService(string $id, ServiceContract $service): self
    {
        $this->services[$id] = $service;
        return $this;
    }

    /**
     * Register a service factory
     */
    public function addFactory(string $id, callable $factory): self
    {
        $this->factories[$id] = $factory;
        return $this;
    }

    /**
     * Register a service provider
     */
    public function addProvider(ServiceProviderContract $provider): self
    {
        $this->providers[] = $provider;
        $provider->register($this);
        return $this;
    }

    /**
     * Get a service by ID
     * 
     * @throws RuntimeException if service not found
     */
    public function get(string $id): ServiceContract
    {
        // Check if service instance exists
        if (isset($this->services[$id])) {
            return $this->services[$id];
        }

        // Check if we can create it from a factory
        if (isset($this->factories[$id])) {
            $service = ($this->factories[$id])($this);
            $this->addService($id, $service);
            return $service;
        }

        // Check if any provider can create it
        foreach ($this->providers as $provider) {
            if ($provider->provides($id)) {
                return $this->get($id);
            }
        }

        throw new RuntimeException("Service '$id' not found");
    }

    /**
     * Check if a service exists
     */
    public function has(string $id): bool
    {
        if (isset($this->services[$id]) || isset($this->factories[$id])) {
            return true;
        }

        foreach ($this->providers as $provider) {
            if ($provider->provides($id)) {
                return true;
            }
        }

        return false;
    }
}
