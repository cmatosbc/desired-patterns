<?php

namespace DesiredPatterns\Specifications;

use DesiredPatterns\Contracts\SpecificationContract;

abstract class AbstractSpecification implements SpecificationContract
{
    public function and(SpecificationContract $other): SpecificationContract
    {
        return new AndSpecification($this, $other);
    }

    public function or(SpecificationContract $other): SpecificationContract
    {
        return new OrSpecification($this, $other);
    }

    public function not(): SpecificationContract
    {
        return new NotSpecification($this);
    }
}
