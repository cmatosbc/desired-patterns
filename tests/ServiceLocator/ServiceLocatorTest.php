<?php

namespace Tests\ServiceLocator;

use PHPUnit\Framework\TestCase;
use DesiredPatterns\ServiceLocator\ServiceLocator;
use DesiredPatterns\Contracts\ServiceProviderContract;

class ServiceLocatorTest extends TestCase
{
    private ServiceLocator $locator;

    protected function setUp(): void
    {
        $this->locator = new ServiceLocator();
    }

    public function testCanRegisterAndResolveService(): void
    {
        // Create a test service
        $service = new class() {
            public function getName(): string
            {
                return 'TestService';
            }
        };

        $this->locator->register('test.service', fn() => $service);
        
        $resolvedService = $this->locator->resolve('test.service');
        $this->assertSame($service, $resolvedService);
    }

    public function testServiceIsSingleton(): void
    {
        $this->locator->register('test.service', fn() => new \stdClass());

        $service1 = $this->locator->resolve('test.service');
        $service2 = $this->locator->resolve('test.service');

        $this->assertSame($service1, $service2);
    }

    public function testCanRegisterServiceProvider(): void
    {
        // Create a test service provider
        $provider = new class implements ServiceProviderContract {
            public function register(ServiceLocator $locator): void
            {
                $locator->register('provider.service', fn() => new \stdClass());
            }

            public function provides(string $id): bool
            {
                return $id === 'provider.service';
            }
        };

        $this->locator->addServiceProvider($provider);
        
        $this->assertTrue($this->locator->has('provider.service'));
        $this->assertInstanceOf(\stdClass::class, $this->locator->resolve('provider.service'));
    }

    public function testThrowsExceptionOnUnknownService(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->locator->resolve('unknown.service');
    }

    public function testCanCheckIfServiceExists(): void
    {
        $this->locator->register('test.service', fn() => new \stdClass());

        $this->assertTrue($this->locator->has('test.service'));
        $this->assertFalse($this->locator->has('unknown.service'));
    }

    public function testCanExtendExistingService(): void
    {
        $originalService = new \stdClass();
        $originalService->value = 'original';

        $this->locator->register('test.service', fn() => $originalService);

        $this->locator->extend('test.service', function($service) {
            $service->value = 'modified';
            return $service;
        });

        $modifiedService = $this->locator->resolve('test.service');
        $this->assertEquals('modified', $modifiedService->value);
    }

    public function testThrowsExceptionWhenExtendingNonExistentService(): void
    {
        $this->expectException(\RuntimeException::class);
        
        $this->locator->extend('unknown.service', function($service) {
            return $service;
        });
    }

    public function testCanUnregisterService(): void
    {
        $this->locator->register('test.service', fn() => new \stdClass());
        $this->assertTrue($this->locator->has('test.service'));

        $this->locator->unregister('test.service');
        $this->assertFalse($this->locator->has('test.service'));
    }
}
