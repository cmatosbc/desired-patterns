<?php

namespace Tests\Traits;

use PHPUnit\Framework\TestCase;
use DesiredPatterns\Traits\Multiton;

class MultitonTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset the instances between tests
        $reflection = new \ReflectionClass(TestMultiton::class);
        $instances = $reflection->getProperty('instances');
        $instances->setAccessible(true);
        $instances->setValue(null, []);
    }

    public function testMultipleNamedInstances()
    {
        $instance1 = TestMultiton::getInstance('first');
        $instance2 = TestMultiton::getInstance('second');

        $this->assertNotSame($instance1, $instance2);
    }

    public function testSameNameReturnsSameInstance()
    {
        $instance1 = TestMultiton::getInstance('first');
        $instance2 = TestMultiton::getInstance('first');

        $this->assertSame($instance1, $instance2);
    }

    public function testHasInstance(): void
    {
        // Register an instance with a specific key
        $instance1 = TestMultiton::getInstance('first');

        // Check if the instance exists
        $this->assertTrue(TestMultiton::hasInstance('first'));

        // Check for a non-existent instance
        $this->assertFalse(TestMultiton::hasInstance('second'));
    }

    public function testCloneThrowsException()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Call to private Tests\Traits\TestMultiton::__clone() from scope Tests\Traits\MultitonTest');
        
        $instance = TestMultiton::getInstance('test');
        clone $instance;
    }

    public function testSerializationThrowsException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Multiton instances cannot be serialized');
        
        $instance = TestMultiton::getInstance('test');
        serialize($instance);
    }
}

class TestMultiton
{
    use Multiton;

    private string $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
