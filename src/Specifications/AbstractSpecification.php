<?php

namespace DesiredPatterns\Specifications;

use DesiredPatterns\Contracts\SpecificationContract;

abstract class AbstractSpecification implements SpecificationContract
{
    public function and(SpecificationContract $other): SpecificationContract
    {
        return new \DesiredPatterns\Specifications\Composite\AndSpecification($this, $other);
    }

    public function or(SpecificationContract $other): SpecificationContract
    {
        return new \DesiredPatterns\Specifications\Composite\OrSpecification($this, $other);
    }

    public function not(): SpecificationContract
    {
        return new \DesiredPatterns\Specifications\Composite\NotSpecification($this);
    }
}
