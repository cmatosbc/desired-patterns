<?php

namespace Tests\Specifications;

use PHPUnit\Framework\TestCase;
use DesiredPatterns\Specifications\Composite\{
    AndSpecification,
    OrSpecification,
    NotSpecification
};

class CompositeSpecificationTest extends TestCase
{
    private $trueSpec;
    private $falseSpec;

    protected function setUp(): void
    {
        // Create test specifications
        $this->trueSpec = new class extends \DesiredPatterns\Specifications\AbstractSpecification {
            public function isSatisfiedBy(mixed $candidate): bool {
                return true;
            }
        };

        $this->falseSpec = new class extends \DesiredPatterns\Specifications\AbstractSpecification {
            public function isSatisfiedBy(mixed $candidate): bool {
                return false;
            }
        };
    }

    public function testAndSpecification(): void
    {
        $spec = new AndSpecification($this->trueSpec, $this->trueSpec);
        $this->assertTrue($spec->isSatisfiedBy(null));

        $spec = new AndSpecification($this->trueSpec, $this->falseSpec);
        $this->assertFalse($spec->isSatisfiedBy(null));

        $spec = new AndSpecification($this->falseSpec, $this->falseSpec);
        $this->assertFalse($spec->isSatisfiedBy(null));
    }

    public function testOrSpecification(): void
    {
        $spec = new OrSpecification($this->trueSpec, $this->trueSpec);
        $this->assertTrue($spec->isSatisfiedBy(null));

        $spec = new OrSpecification($this->trueSpec, $this->falseSpec);
        $this->assertTrue($spec->isSatisfiedBy(null));

        $spec = new OrSpecification($this->falseSpec, $this->falseSpec);
        $this->assertFalse($spec->isSatisfiedBy(null));
    }

    public function testNotSpecification(): void
    {
        $spec = new NotSpecification($this->trueSpec);
        $this->assertFalse($spec->isSatisfiedBy(null));

        $spec = new NotSpecification($this->falseSpec);
        $this->assertTrue($spec->isSatisfiedBy(null));
    }

    public function testChainedSpecifications(): void
    {
        $spec = $this->trueSpec
            ->and($this->trueSpec)
            ->or($this->falseSpec)
            ->not();

        $this->assertFalse($spec->isSatisfiedBy(null));
    }
}
