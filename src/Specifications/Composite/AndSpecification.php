<?php

namespace DesiredPatterns\Specifications\Composite;

use DesiredPatterns\Contracts\SpecificationContract;
use DesiredPatterns\Specifications\AbstractSpecification;

/**
 * Specification that evaluates to true only if both wrapped specifications are satisfied.
 *
 * This class combines two specifications using the logical AND operator.
 */
class AndSpecification extends AbstractSpecification
{
    /**
     * Initializes the AndSpecification with two specifications.
     *
     * @param SpecificationContract $left  The first specification.
     * @param SpecificationContract $right The second specification.
     */
    public function __construct(
        private SpecificationContract $left,
        private SpecificationContract $right
    ) {
    }

    /**
     * Checks if the candidate satisfies both wrapped specifications.
     *
     * @param mixed $candidate The candidate to check.
     * @return bool True if the candidate satisfies both specifications, false otherwise.
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate) && $this->right->isSatisfiedBy($candidate);
    }
}
