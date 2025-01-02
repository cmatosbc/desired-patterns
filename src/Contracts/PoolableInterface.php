<?php

namespace DesiredPatterns\Contracts;

interface PoolableInterface
{
    /**
     * Reset the object to its initial state
     */
    public function reset(): void;

    /**
     * Validate if the object is still in a valid state
     */
    public function validate(): bool;
}
