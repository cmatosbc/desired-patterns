<?php

namespace Tests\Traits;

use PHPUnit\Framework\TestCase;
use DesiredPatterns\Traits\Singleton;

class SingletonTest extends TestCase
{
    public function testOnlyOneInstanceIsCreated()
    {
        $instance1 = TestSingleton::getInstance();
        $instance2 = TestSingleton::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    public function testInstanceAccessibility()
    {
        $instance = TestSingleton::getInstance();
        $instance->someProperty = 'test value';

        $this->assertEquals('test value', $instance->someProperty);
    }

    public function testCloneThrowsException()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Call to private Tests\Traits\TestSingleton::__clone() from scope Tests\Traits\SingletonTest');
        
        $instance = TestSingleton::getInstance();
        clone $instance;
    }

    public function testSerializationThrowsException()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Singleton instance cannot be serialized');
        
        $instance = TestSingleton::getInstance();
        serialize($instance);
    }
}

class TestSingleton
{
    use Singleton;

    public $someProperty;
}
