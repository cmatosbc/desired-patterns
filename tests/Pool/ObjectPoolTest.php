<?php

namespace DesiredPatterns\Tests\Pool;

use DesiredPatterns\Pool\ObjectPool;
use DesiredPatterns\Tests\Mock\MockPoolableResource;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

class ObjectPoolTest extends TestCase
{
    private ObjectPool $pool;
    
    protected function setUp(): void
    {
        $this->pool = new ObjectPool(
            className: MockPoolableResource::class,
            minInstances: 2,
            maxInstances: 5,
            constructorArgs: ['test-id']
        );
    }
    
    public function testInitialPoolSize(): void
    {
        $stats = $this->pool->getStatistics();
        $this->assertEquals(2, $stats['created'], 'Pool should create minimum instances');
        $this->assertEquals(2, $stats['available'], 'All created instances should be available');
        $this->assertEquals(0, $stats['in_use'], 'No instances should be in use');
    }
    
    public function testAcquireAndRelease(): void
    {
        // Acquire an object
        $obj1 = $this->pool->acquire();
        $this->assertInstanceOf(MockPoolableResource::class, $obj1);
        
        $stats = $this->pool->getStatistics();
        $this->assertEquals(1, $stats['in_use'], 'One object should be in use');
        $this->assertEquals(1, $stats['available'], 'One object should be available');
        
        // Release the object
        $this->pool->release($obj1);
        $stats = $this->pool->getStatistics();
        $this->assertEquals(0, $stats['in_use'], 'No objects should be in use');
        $this->assertEquals(2, $stats['available'], 'All objects should be available');
    }
    
    public function testMaxPoolSize(): void
    {
        $objects = [];
        
        // Acquire maximum number of objects
        for ($i = 0; $i < 5; $i++) {
            $objects[] = $this->pool->acquire();
        }
        
        // Try to acquire one more
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Pool exhausted');
        $this->pool->acquire();
    }
    
    public function testObjectReuse(): void
    {
        // Acquire and release an object
        $obj1 = $this->pool->acquire();
        $this->pool->release($obj1);
        
        // Acquire again - should get the same object
        $obj2 = $this->pool->acquire();
        $this->assertSame($obj1, $obj2, 'Pool should reuse released objects');
        
        $stats = $this->pool->getStatistics();
        $this->assertGreaterThan(0, $stats['reused'], 'Object should be marked as reused');
    }
    
    public function testInvalidObjectType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must implement PoolableInterface');
        new ObjectPool(stdClass::class, constructorArgs: []);
    }
    
    public function testObjectValidation(): void
    {
        /** @var MockPoolableResource $obj */
        $obj = $this->pool->acquire();
        $obj->invalidate();
        
        // Release invalid object
        $this->pool->release($obj);
        
        $stats = $this->pool->getStatistics();
        $this->assertEquals(1, $stats['available'], 'Invalid object should not be returned to pool');
    }
    
    public function testObjectReset(): void
    {
        /** @var MockPoolableResource $obj */
        $obj = $this->pool->acquire();
        $this->pool->release($obj);
        
        $this->assertEquals(1, $obj->getResetCount(), 'Object should be reset when released');
        $this->assertEquals(1, $obj->getValidateCount(), 'Object should be validated when released');
    }
    
    public function testReleaseUnknownObject(): void
    {
        $obj = new MockPoolableResource('unknown');
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Object does not belong to this pool');
        $this->pool->release($obj);
    }
}
