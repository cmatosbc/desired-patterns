<?php

namespace DesiredPatterns\Specifications\Composite;

use DesiredPatterns\Contracts\SpecificationContract;
use DesiredPatterns\Specifications\AbstractSpecification;

/**
 * Specification that evaluates to true if either of the wrapped specifications is satisfied.
 *
 * This class combines two specifications using the logical OR operator.
 */
class OrSpecification extends AbstractSpecification
{
    /**
     * Initializes the OrSpecification with two specifications.
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
     * Checks if the candidate satisfies either of the wrapped specifications.
     *
     * @param mixed $candidate The candidate to check.
     * @return bool True if the candidate satisfies either specification, false otherwise.
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate) || $this->right->isSatisfiedBy($candidate);
    }
}
