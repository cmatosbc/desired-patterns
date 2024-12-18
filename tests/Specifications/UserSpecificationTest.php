<?php

namespace Tests\Specifications;

use PHPUnit\Framework\TestCase;
use DesiredPatterns\Specifications\AbstractSpecification;

class UserSpecificationTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        // Create a test user class
        $this->user = new class {
            public function __construct(
                public int $age = 25,
                public bool $isVerified = true,
                public array $roles = ['user']
            ) {}
        };
    }

    public function testAgeSpecification(): void
    {
        $isAdult = new class extends AbstractSpecification {
            public function isSatisfiedBy(mixed $candidate): bool {
                return $candidate->age >= 18;
            }
        };

        $this->assertTrue($isAdult->isSatisfiedBy($this->user));

        $this->user = new class {
            public function __construct(
                public int $age = 16,
                public bool $isVerified = true,
                public array $roles = ['user']
            ) {}
        };
        $this->assertFalse($isAdult->isSatisfiedBy($this->user));
    }

    public function testVerificationSpecification(): void
    {
        $isVerified = new class extends AbstractSpecification {
            public function isSatisfiedBy(mixed $candidate): bool {
                return $candidate->isVerified;
            }
        };

        $this->assertTrue($isVerified->isSatisfiedBy($this->user));

        $this->user = new class {
            public function __construct(
                public int $age = 25,
                public bool $isVerified = false,
                public array $roles = ['user']
            ) {}
        };
        $this->assertFalse($isVerified->isSatisfiedBy($this->user));
    }

    public function testRoleSpecification(): void
    {
        $hasRole = new class('admin') extends AbstractSpecification {
            public function __construct(private string $role) {}

            public function isSatisfiedBy(mixed $candidate): bool {
                return in_array($this->role, $candidate->roles);
            }
        };

        $this->assertFalse($hasRole->isSatisfiedBy($this->user));

        $this->user = new class {
            public function __construct(
                public int $age = 25,
                public bool $isVerified = true,
                public array $roles = ['user', 'admin']
            ) {}
        };
        $this->assertTrue($hasRole->isSatisfiedBy($this->user));
    }

    public function testComplexSpecification(): void
    {
        // Create specifications
        $isAdult = new class extends AbstractSpecification {
            public function isSatisfiedBy(mixed $candidate): bool {
                return $candidate->age >= 18;
            }
        };

        $isVerified = new class extends AbstractSpecification {
            public function isSatisfiedBy(mixed $candidate): bool {
                return $candidate->isVerified;
            }
        };

        $isAdmin = new class extends AbstractSpecification {
            public function isSatisfiedBy(mixed $candidate): bool {
                return in_array('admin', $candidate->roles);
            }
        };

        // Test complex combination
        $canAccessAdmin = $isAdult
            ->and($isVerified)
            ->and($isAdmin);

        $this->assertFalse($canAccessAdmin->isSatisfiedBy($this->user));

        $this->user = new class {
            public function __construct(
                public int $age = 25,
                public bool $isVerified = true,
                public array $roles = ['user', 'admin']
            ) {}
        };
        $this->assertTrue($canAccessAdmin->isSatisfiedBy($this->user));

        $this->user = new class {
            public function __construct(
                public int $age = 25,
                public bool $isVerified = false,
                public array $roles = ['user', 'admin']
            ) {}
        };
        $this->assertFalse($canAccessAdmin->isSatisfiedBy($this->user));
    }
}
