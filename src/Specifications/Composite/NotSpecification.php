<?php

namespace DesiredPatterns\Specifications\Composite;

use DesiredPatterns\Contracts\SpecificationContract;
use DesiredPatterns\Specifications\AbstractSpecification;

/**
 * Specification that negates another specification.
 *
 * This class checks if a candidate does not satisfy the specified condition of the wrapped
 * specification.
 */
class NotSpecification extends AbstractSpecification
{
    /**
     * Initializes the NotSpecification with the wrapped specification.
     *
     * @param SpecificationContract $specification The wrapped specification.
     */
    public function __construct(
        private SpecificationContract $specification
    ) {
    }

    /**
     * Checks if the candidate satisfies the negated specification.
     *
     * @param mixed $candidate The candidate to check.
     * @return bool True if the candidate does not satisfy the specification, false otherwise.
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return !$this->specification->isSatisfiedBy($candidate);
    }
}
