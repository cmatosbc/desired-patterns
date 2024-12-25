<?php

declare(strict_types=1);

namespace Tests\State;

use DesiredPatterns\State\AbstractState;
use DesiredPatterns\State\StateMachineTrait;
use DesiredPatterns\State\StateException;
use PHPUnit\Framework\TestCase;

class MockState extends AbstractState
{
    private string $name;
    
    public function __construct(string $name, array $transitions = [], array $rules = [])
    {
        $this->name = $name;
        $this->allowedTransitions = $transitions;
        $this->validationRules = $rules;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function handle(array $context): array
    {
        // Merge passed context with any additional data
        $mergedContext = array_merge($this->options, $context);
        return [
            'state' => $this->name,
            'context' => $mergedContext
        ];
    }
}

class MockStateMachine
{
    use StateMachineTrait;
}

class StateMachineTest extends TestCase
{
    private MockStateMachine $stateMachine;

    protected function setUp(): void
    {
        $this->stateMachine = new MockStateMachine();
    }

    public function testAddState(): void
    {
        $state = new MockState('initial');
        $result = $this->stateMachine->addState($state, true);

        $this->assertSame($this->stateMachine, $result);
        $this->assertSame($state, $this->stateMachine->getCurrentState());
    }

    public function testAddMultipleStates(): void
    {
        $initial = new MockState('initial', ['pending']);
        $pending = new MockState('pending', ['processing', 'cancelled']);
        $processing = new MockState('processing', ['completed']);
        $completed = new MockState('completed');
        $cancelled = new MockState('cancelled');

        $this->stateMachine
            ->addState($initial, true)
            ->addState($pending)
            ->addState($processing)
            ->addState($completed)
            ->addState($cancelled);

        $this->assertSame($initial, $this->stateMachine->getCurrentState());
        $this->assertContains('pending', $this->stateMachine->getAvailableTransitions());
    }

    public function testValidStateTransition(): void
    {
        $initial = new MockState('initial', ['pending']);
        $pending = new MockState('pending');

        $this->stateMachine
            ->addState($initial, true)
            ->addState($pending);

        $result = $this->stateMachine->transitionTo('pending');

        $this->assertTrue($result);
        $this->assertSame('pending', $this->stateMachine->getCurrentState()->getName());
    }

    public function testInvalidStateTransition(): void
    {
        $this->expectException(StateException::class);

        $initial = new MockState('initial', []);
        $pending = new MockState('pending');

        $this->stateMachine
            ->addState($initial, true)
            ->addState($pending);

        $this->stateMachine->transitionTo('pending');
    }

    public function testTransitionToNonexistentState(): void
    {
        $this->expectException(StateException::class);

        $initial = new MockState('initial');
        $this->stateMachine->addState($initial, true);

        $this->stateMachine->transitionTo('nonexistent');
    }

    public function testContextValidation(): void
    {
        $initial = new MockState('initial', ['pending'], [
            'order_id' => 'required',
            'amount' => 'type:double'
        ]);
        $pending = new MockState('pending');

        $this->stateMachine->addState($initial, true);
        $this->stateMachine->addState($pending);

        // Test missing required field
        try {
            $this->stateMachine->updateContext(['amount' => 99.99]);
            $this->fail('Expected StateException for missing required field');
        } catch (StateException $e) {
            $this->assertStringContainsString('Invalid context', $e->getMessage());
        }

        // Test invalid type
        try {
            $this->stateMachine->updateContext([
                'order_id' => '123',
                'amount' => 'not-a-number'
            ]);
            $this->fail('Expected StateException for invalid type');
        } catch (StateException $e) {
            $this->assertStringContainsString('Invalid context', $e->getMessage());
        }

        // Test valid context
        $this->stateMachine->updateContext([
            'order_id' => '123',
            'amount' => 99.99
        ]);

        // Should be able to transition with valid context
        $this->assertTrue($this->stateMachine->transitionTo('pending'));
    }

    public function testContextUpdate(): void
    {
        $initial = new MockState('initial');
        $this->stateMachine->addState($initial, true);

        // Initial context update
        $this->stateMachine->updateContext(['key1' => 'value1']);
        $context = $this->stateMachine->getContext();
        $this->assertEquals('value1', $context['key1']);

        // Merge new context
        $this->stateMachine->updateContext(['key2' => 'value2']);
        $context = $this->stateMachine->getContext();
        $this->assertEquals('value1', $context['key1']);
        $this->assertEquals('value2', $context['key2']);

        // Override existing key
        $this->stateMachine->updateContext(['key1' => 'new_value']);
        $context = $this->stateMachine->getContext();
        $this->assertEquals('new_value', $context['key1']);
    }

    public function testStateHistory(): void
    {
        $initial = new MockState('initial', ['pending']);
        $pending = new MockState('pending', ['completed']);
        $completed = new MockState('completed');

        $this->stateMachine
            ->addState($initial, true)
            ->addState($pending)
            ->addState($completed);

        $this->stateMachine->transitionTo('pending', ['order_id' => '123']);
        $this->stateMachine->transitionTo('completed', ['status' => 'done']);

        $history = $this->stateMachine->getStateHistory();

        $this->assertCount(2, $history);
        $this->assertEquals('initial', $history[0]['from']);
        $this->assertEquals('pending', $history[0]['to']);
        $this->assertEquals('completed', $history[1]['to']);
    }

    public function testGetAvailableTransitions(): void
    {
        $state = new MockState('test', ['state1', 'state2']);
        $this->stateMachine->addState($state, true);

        $transitions = $this->stateMachine->getAvailableTransitions();

        $this->assertCount(2, $transitions);
        $this->assertContains('state1', $transitions);
        $this->assertContains('state2', $transitions);
    }
}
