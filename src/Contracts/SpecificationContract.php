<?php

namespace DesiredPatterns\Contracts;

interface SpecificationContract
{
    /**
     * Check if candidate satisfies the specification
     */
    public function isSatisfiedBy(mixed $candidate): bool;

    /**
     * Combine with another specification using AND
     */
    public function and(self $other): self;

    /**
     * Combine with another specification using OR
     */
    public function or(self $other): self;

    /**
     * Negate the specification
     */
    public function not(): self;
}
