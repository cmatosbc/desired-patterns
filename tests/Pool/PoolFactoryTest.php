<?php

namespace DesiredPatterns\Tests\Pool;

use DesiredPatterns\Pool\ObjectPool;
use DesiredPatterns\Pool\PoolFactory;
use DesiredPatterns\Tests\Mock\MockPoolableResource;
use PHPUnit\Framework\TestCase;

class PoolFactoryTest extends TestCase
{
    public function testPoolCreation(): void
    {
        $pool = PoolFactory::getPool(
            'test',
            MockPoolableResource::class,
            [
                'min_instances' => 1,
                'max_instances' => 3,
                'constructor_args' => ['test-id']
            ]
        );
        
        $this->assertInstanceOf(ObjectPool::class, $pool);
        
        $stats = $pool->getStatistics();
        $this->assertEquals(1, $stats['created'], 'Pool should create minimum instances');
    }
    
    public function testPoolReuse(): void
    {
        $pool1 = PoolFactory::getPool('reuse-test', MockPoolableResource::class);
        $pool2 = PoolFactory::getPool('reuse-test', MockPoolableResource::class);
        
        $this->assertSame($pool1, $pool2, 'Factory should reuse existing pools');
    }
    
    public function testMultiplePools(): void
    {
        $pool1 = PoolFactory::getPool('pool1', MockPoolableResource::class);
        $pool2 = PoolFactory::getPool('pool2', MockPoolableResource::class);
        
        $this->assertNotSame($pool1, $pool2, 'Different pool names should create different pools');
    }
    
    public function testDefaultConfiguration(): void
    {
        $pool = PoolFactory::getPool(
            'default-config',
            MockPoolableResource::class,
            ['constructor_args' => ['default-id']]
        );
        
        $stats = $pool->getStatistics();
        $this->assertEquals(0, $stats['created'], 'Pool should use default min_instances');
        
        // Test we can create up to default max instances (10)
        $objects = [];
        for ($i = 0; $i < 10; $i++) {
            $objects[] = $pool->acquire();
        }
        
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Pool exhausted');
        $pool->acquire();
    }
}
