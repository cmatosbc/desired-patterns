<?php

namespace DesiredPatterns\Tests\NullObject;

use DesiredPatterns\Tests\Mock\MockService;
use DesiredPatterns\Tests\Mock\NullMockService;
use PHPUnit\Framework\TestCase;

class NullObjectTest extends TestCase
{
    private MockService $realService;
    private NullMockService $nullService;
    
    protected function setUp(): void
    {
        $this->realService = new MockService();
        $this->nullService = new NullMockService();
    }
    
    public function testRealServiceBehavior(): void
    {
        $result = $this->realService->performAction('test');
        $this->assertEquals('Performed: test', $result);
        $this->assertEquals('test', $this->realService->getLastAction());
        $this->assertEquals(1, $this->realService->getActionCount());
        $this->assertFalse($this->realService->isNull());
    }
    
    public function testNullServiceBehavior(): void
    {
        $result = $this->nullService->performAction('test');
        $this->assertEquals('', $result);
        $this->assertNull($this->nullService->getLastAction());
        $this->assertEquals(0, $this->nullService->getActionCount());
        $this->assertTrue($this->nullService->isNull());
    }
    
    public function testMultipleActions(): void
    {
        $this->realService->performAction('action1');
        $this->realService->performAction('action2');
        
        $this->assertEquals(2, $this->realService->getActionCount());
        $this->assertEquals('action2', $this->realService->getLastAction());
        
        // Null service should remain unchanged
        $this->nullService->performAction('action1');
        $this->nullService->performAction('action2');
        
        $this->assertEquals(0, $this->nullService->getActionCount());
        $this->assertNull($this->nullService->getLastAction());
    }
    
    public function testTypeCompatibility(): void
    {
        $this->assertInstanceOf(
            \DesiredPatterns\NullObject\NullableInterface::class,
            $this->nullService
        );
        
        $this->assertInstanceOf(
            \DesiredPatterns\Tests\Mock\MockNullableService::class,
            $this->nullService
        );
    }
}
