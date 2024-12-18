<?php

namespace DesiredPatterns\Contracts;

use DesiredPatterns\ServiceLocator;

interface ServiceProviderContract
{
    public function register(ServiceLocator $locator): void;
    public function provides(string $id): bool;
}
