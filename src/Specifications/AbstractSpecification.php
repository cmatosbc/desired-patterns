<?php

namespace DesiredPatterns\Specifications;

use DesiredPatterns\Contracts\SpecificationContract;

/**
 * Abstract class for specifications in the Specification pattern.
 *
 * This class provides the basic structure for specifications, allowing for the combination of
 * multiple specifications using logical operators (AND, OR, NOT). It implements the
 * SpecificationContract interface, ensuring that all specifications can be evaluated against
 * a candidate.
 */
abstract class AbstractSpecification implements SpecificationContract
{
    /**
     * Combine with another specification using AND
     *
     * @param SpecificationContract $other The other specification to combine with.
     * @return SpecificationContract The combined specification.
     */
    public function and(SpecificationContract $other): SpecificationContract
    {
        return new \DesiredPatterns\Specifications\Composite\AndSpecification($this, $other);
    }

    /**
     * Combine with another specification using OR
     *
     * @param SpecificationContract $other The other specification to combine with.
     * @return SpecificationContract The combined specification.
     */
    public function or(SpecificationContract $other): SpecificationContract
    {
        return new \DesiredPatterns\Specifications\Composite\OrSpecification($this, $other);
    }

    /**
     * Negate the specification
     *
     * @return SpecificationContract The negated specification.
     */
    public function not(): SpecificationContract
    {
        return new \DesiredPatterns\Specifications\Composite\NotSpecification($this);
    }
}
