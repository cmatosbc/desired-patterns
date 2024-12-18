<?php

namespace DesiredPatterns\Contracts;

use DesiredPatterns\ServiceLocator\ServiceLocator;

interface ServiceProviderContract
{
    /**
     * Register services with the service locator
     */
    public function register(ServiceLocator $locator): void;

    /**
     * Check if this provider provides a specific service
     */
    public function provides(string $id): bool;
}
