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
}

class TestSingleton
{
    use Singleton;

    public $someProperty;
}
