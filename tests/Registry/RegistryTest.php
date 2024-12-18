<?php

namespace Tests\Registry;

use PHPUnit\Framework\TestCase;
use DesiredPatterns\Registry\Registry;

class RegistryTest extends TestCase
{
    protected function setUp(): void
    {
        Registry::reset();
    }

    public function testCanSetAndGetValue(): void
    {
        $key = 'test.key';
        $value = 'test.value';

        Registry::set($key, $value);
        $this->assertEquals($value, Registry::get($key));
    }

    public function testCanCheckIfKeyExists(): void
    {
        $key = 'test.key';
        $value = 'test.value';

        Registry::set($key, $value);
        $this->assertTrue(Registry::has($key));
        $this->assertFalse(Registry::has('non.existent.key'));
    }

    public function testCanRemoveKey(): void
    {
        $key = 'test.key';
        $value = 'test.value';

        Registry::set($key, $value);
        $this->assertTrue(Registry::has($key));

        Registry::remove($key);
        $this->assertFalse(Registry::has($key));
    }

    public function testThrowsExceptionOnNonExistentKey(): void
    {
        $this->expectException(\RuntimeException::class);
        Registry::get('non.existent.key');
    }

    public function testCanClearAllValues(): void
    {
        Registry::set('key1', 'value1');
        Registry::set('key2', 'value2');

        Registry::clear();

        $this->assertFalse(Registry::has('key1'));
        $this->assertFalse(Registry::has('key2'));
    }

    public function testCanGetAllKeys(): void
    {
        Registry::set('key1', 'value1');
        Registry::set('key2', 'value2');

        $keys = Registry::keys();

        $this->assertCount(2, $keys);
        $this->assertContains('key1', $keys);
        $this->assertContains('key2', $keys);
    }
}
