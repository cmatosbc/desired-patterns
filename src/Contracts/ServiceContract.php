<?php

namespace DesiredPatterns\Contracts;

/**
 * Interface for service providers.
 *
 * This interface defines the method for retrieving the name of the service.
 */
interface ServiceContract
{
    public function getName(): string;
}
