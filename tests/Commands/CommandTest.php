<?php

namespace Tests\Commands;

use PHPUnit\Framework\TestCase;
use DesiredPatterns\Commands\AbstractCommand;
use DesiredPatterns\Commands\AbstractCommandHandler;
use DesiredPatterns\Contracts\CommandContract;

class CommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        $command = new class(['test' => 'value']) extends AbstractCommand {};
        
        $this->assertEquals(['test' => 'value'], $command->getPayload());
        $this->assertEquals(get_class($command), $command->getName());
    }

    public function testCommandHandler(): void
    {
        // Create a test command
        $command = new class(['value' => 42]) extends AbstractCommand {};

        // Create a handler
        $handler = new class extends AbstractCommandHandler {
            protected function process(CommandContract $command): int {
                return $command->getPayload()['value'];
            }

            protected function supports(CommandContract $command): bool {
                return true;
            }
        };

        $result = $handler->handle($command);
        $this->assertEquals(42, $result);
    }

    public function testUnsupportedCommand(): void
    {
        $this->expectException(\RuntimeException::class);

        $command = new class([]) extends AbstractCommand {};
        
        $handler = new class extends AbstractCommandHandler {
            protected function process(CommandContract $command): mixed {
                return null;
            }

            protected function supports(CommandContract $command): bool {
                return false;
            }
        };

        $handler->handle($command);
    }

    public function testCommandPayloadAccess(): void
    {
        $payload = [
            'id' => 1,
            'name' => 'Test',
            'data' => ['key' => 'value']
        ];

        $command = new class($payload) extends AbstractCommand {};
        
        $this->assertEquals(1, $command->getPayload()['id']);
        $this->assertEquals('Test', $command->getPayload()['name']);
        $this->assertEquals(['key' => 'value'], $command->getPayload()['data']);
    }
}
