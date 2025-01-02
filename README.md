# Modern PHP Design Patterns

[![PHP Lint](https://github.com/cmatosbc/desired-patterns/actions/workflows/lint.yml/badge.svg)](https://github.com/cmatosbc/desired-patterns/actions/workflows/lint.yml) [![PHPUnit Tests](https://github.com/cmatosbc/desired-patterns/actions/workflows/phpunit.yml/badge.svg)](https://github.com/cmatosbc/desired-patterns/actions/workflows/phpunit.yml) [![PHP Composer](https://github.com/cmatosbc/desired-patterns/actions/workflows/composer.yml/badge.svg)](https://github.com/cmatosbc/desired-patterns/actions/workflows/composer.yml) ![Code Coverage](https://img.shields.io/endpoint?url=https://gist.githubusercontent.com/cmatosbc/664fd72a90f996481f161d1d3a2f7285/raw/coverage.json)

A collection of modern PHP design patterns implemented using PHP 8.2+ features. Sexier than older implementations and more readable than ever.

## Requirements

- PHP 8.2 or higher
- Composer

## Installation

```bash
composer require cmatosbc/desired-patterns
```

## Patterns Implemented

Quick Links:
- [1. Singleton Pattern](#1-singleton-pattern)
- [2. Multiton Pattern](#2-multiton-pattern)
- [3. Command Pattern](#3-command-pattern)
- [4. Chain of Responsibility Pattern](#4-chain-of-responsibility-pattern)
- [5. Registry Pattern](#5-registry-pattern)
- [6. Service Locator Pattern](#6-service-locator-pattern)
- [7. Specification Pattern](#7-specification-pattern)
- [8. Strategy Pattern](#8-strategy-pattern)
- [9. State Pattern](#9-state-pattern)
- [10. Pipeline Pattern](#10-pipeline-pattern)
- [11. Object Pool Pattern](#11-object-pool-pattern)
- [12. Null Object Pattern](#12-null-object-pattern)

### 1. Singleton Pattern
The Singleton pattern ensures a class has only one instance and provides a global point of access to it. Our implementation uses a trait to make it reusable.

```php
use DesiredPatterns\Traits\Singleton;

class Database
{
    use Singleton;

    private function __construct()
    {
        // Initialize database connection
    }

    public function query(string $sql): array
    {
        // Execute query
    }
}

// Usage
$db = Database::getInstance();
```

### 2. Multiton Pattern
The Multiton pattern is similar to Singleton but maintains a map of named instances. This is useful when you need multiple named instances of a class.

```php
use DesiredPatterns\Traits\Multiton;

class Configuration
{
    use Multiton;

    private string $environment;

    private function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }
}

// Usage
$devConfig = Configuration::getInstance('development');
$prodConfig = Configuration::getInstance('production');

// Check if instance exists
if (Configuration::hasInstance('testing')) {
    // ...
}
```

### 3. Command Pattern
The Command pattern encapsulates a request as an object, thereby letting you parameterize clients with different requests, queue or log requests, and support undoable operations.

```php
use DesiredPatterns\Commands\AbstractCommand;
use DesiredPatterns\Commands\AbstractCommandHandler;

// Command
class CreateUserCommand extends AbstractCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $email
    ) {}
}

// Handler
class CreateUserHandler extends AbstractCommandHandler
{
    public function handle(CreateUserCommand $command): void
    {
        // Create user logic here
    }

    public function supports(object $command): bool
    {
        return $command instanceof CreateUserCommand;
    }
}

// Usage
$command = new CreateUserCommand('John Doe', 'john@example.com');
$handler = new CreateUserHandler();
$handler->handle($command);
```

### 4. Chain of Responsibility Pattern
The Chain of Responsibility pattern lets you pass requests along a chain of handlers. Upon receiving a request, each handler decides either to process the request or to pass it to the next handler in the chain.

```php
use DesiredPatterns\Chain\AbstractHandler;

// Create concrete handlers
class PayPalHandler extends AbstractHandler
{
    public function handle($request)
    {
        if ($request['type'] === 'paypal') {
            return [
                'status' => 'success',
                'message' => 'Payment processed via PayPal'
            ];
        }
        
        return parent::handle($request);
    }
}

class CreditCardHandler extends AbstractHandler
{
    public function handle($request)
    {
        if ($request['type'] === 'credit_card') {
            return [
                'status' => 'success',
                'message' => 'Payment processed via credit card'
            ];
        }
        
        return parent::handle($request);
    }
}

// Usage
$paypalHandler = new PayPalHandler();
$creditCardHandler = new CreditCardHandler();

// Build the chain
$paypalHandler->setNext($creditCardHandler);

// Process payment
$result = $paypalHandler->handle([
    'type' => 'credit_card',
    'amount' => 100.00
]);
```

### 5. Registry Pattern
The Registry pattern provides a global point of access to objects or services throughout an application.

```php
use DesiredPatterns\Registry\Registry;

// Store a value
Registry::set('config.database', [
    'host' => 'localhost',
    'name' => 'myapp'
]);

// Retrieve a value
$dbConfig = Registry::get('config.database');

// Check if key exists
if (Registry::has('config.database')) {
    // ...
}

// Remove a key
Registry::remove('config.database');

// Get all keys
$keys = Registry::keys();
```

### 6. Service Locator Pattern
The Service Locator pattern is a design pattern used to encapsulate the processes involved in obtaining a service with a strong abstraction layer.

```php
use DesiredPatterns\ServiceLocator\ServiceLocator;

class DatabaseService
{
    public function connect(): void
    {
        // Connection logic
    }
}

// Create a service locator
$locator = new ServiceLocator();

// Register a service
$locator->register('database', fn() => new DatabaseService());

// Resolve the service
$db = $locator->resolve('database');

// Check if service exists
if ($locator->has('database')) {
    // ...
}

// Extend an existing service
$locator->extend('database', function($service) {
    // Modify or decorate the service
    return $service;
});
```

### 7. Specification Pattern
The Specification pattern is used to create business rules that can be combined using boolean logic.

```php
use DesiredPatterns\Specifications\AbstractSpecification;
use DesiredPatterns\Specifications\Composite\{AndSpecification, OrSpecification, NotSpecification};

// Create specifications
class AgeSpecification extends AbstractSpecification
{
    public function __construct(private int $minAge) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate->age >= $this->minAge;
    }
}

class VerifiedSpecification extends AbstractSpecification
{
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate->isVerified;
    }
}

// Usage
$isAdult = new AgeSpecification(18);
$isVerified = new VerifiedSpecification();

// Combine specifications
$canAccessContent = $isAdult->and($isVerified);

// Check if specifications are met
$user = new stdClass();
$user->age = 25;
$user->isVerified = true;

if ($canAccessContent->isSatisfiedBy($user)) {
    // Allow access
}
```

### 8. Strategy Pattern

The Strategy pattern defines a family of algorithms, encapsulates each one, and makes them interchangeable. It lets the algorithm vary independently from clients that use it.

### Real-World Examples

1. **Payment Processing**
   - Different payment methods (Credit Card, PayPal, Cryptocurrency)
   - Each payment method has its own validation and processing logic
   - System can switch between payment strategies based on user selection

2. **Data Export**
   - Multiple export formats (CSV, JSON, XML, PDF)
   - Each format has specific formatting requirements
   - Choose export strategy based on user preference or file type

3. **Shipping Calculation**
   - Various shipping providers (FedEx, UPS, DHL)
   - Each provider has unique rate calculation algorithms
   - Select provider based on destination, weight, or cost

### Complete Example

```php

namespace Examples\Strategy\Payment;

use DesiredPatterns\Strategy\AbstractStrategy;
use DesiredPatterns\Traits\ConfigurableStrategyTrait;

/**
 * Credit Card Payment Strategy
 */
class CreditCardStrategy extends AbstractStrategy
{
    use ConfigurableStrategyTrait;

    protected array $requiredOptions = ['api_key'];

    public function supports(array $data): bool
    {
        return isset($data['payment_method']) 
            && $data['payment_method'] === 'credit_card';
    }

    public function validate(array $data): bool
    {
        return isset($data['card_number'])
            && isset($data['expiry'])
            && isset($data['cvv']);
    }

    public function execute(array $data): array
    {
        // Process credit card payment
        return [
            'status' => 'success',
            'transaction_id' => uniqid('cc_'),
            'method' => 'credit_card',
            'amount' => $data['amount']
        ];
    }
}

/**
 * PayPal Payment Strategy
 */
class PayPalStrategy extends AbstractStrategy
{
    use ConfigurableStrategyTrait;

    protected array $requiredOptions = ['client_id', 'client_secret'];

    public function supports(array $data): bool
    {
        return isset($data['payment_method']) 
            && $data['payment_method'] === 'paypal';
    }

    public function validate(array $data): bool
    {
        return isset($data['paypal_email']) 
            && isset($data['amount']);
    }

    public function execute(array $data): array
    {
        // Process PayPal payment
        return [
            'status' => 'success',
            'transaction_id' => uniqid('pp_'),
            'method' => 'paypal',
            'amount' => $data['amount']
        ];
    }
}

/**
 * Cryptocurrency Payment Strategy
 */
class CryptoStrategy extends AbstractStrategy
{
    use ConfigurableStrategyTrait;

    protected array $requiredOptions = ['wallet_address'];

    public function supports(array $data): bool
    {
        return isset($data['payment_method']) 
            && $data['payment_method'] === 'crypto';
    }

    public function validate(array $data): bool
    {
        return isset($data['crypto_address']) 
            && isset($data['crypto_currency']);
    }

    public function execute(array $data): array
    {
        // Process crypto payment
        return [
            'status' => 'success',
            'transaction_id' => uniqid('crypto_'),
            'method' => 'crypto',
            'amount' => $data['amount'],
            'currency' => $data['crypto_currency']
        ];
    }
}

// Usage Example
$context = new StrategyContext();

// Configure payment strategies
$context->addStrategy(
    new CreditCardStrategy(),
    ['api_key' => 'sk_test_123']
)
->addStrategy(
    new PayPalStrategy(),
    [
        'client_id' => 'client_123',
        'client_secret' => 'secret_456'
    ]
)
->addStrategy(
    new CryptoStrategy(),
    ['wallet_address' => '0x123...']
);

// Process a credit card payment
$ccPayment = $context->executeStrategy([
    'payment_method' => 'credit_card',
    'amount' => 99.99,
    'card_number' => '4242424242424242',
    'expiry' => '12/25',
    'cvv' => '123'
]);

// Process a PayPal payment
$ppPayment = $context->executeStrategy([
    'payment_method' => 'paypal',
    'amount' => 149.99,
    'paypal_email' => 'customer@example.com'
]);

// Process a crypto payment
$cryptoPayment = $context->executeStrategy([
    'payment_method' => 'crypto',
    'amount' => 199.99,
    'crypto_address' => '0x456...',
    'crypto_currency' => 'ETH'
]);
```

### 9. State Pattern
The State pattern allows an object to alter its behavior when its internal state changes. The object will appear to change its class. Our implementation provides a flexible and type-safe way to handle state transitions with context validation.

```php
use DesiredPatterns\State\StateMachineTrait;
use DesiredPatterns\State\AbstractState;

// Define your states
class PendingState extends AbstractState
{
    public function getName(): string
    {
        return 'pending';
    }

    protected array $allowedTransitions = ['processing', 'cancelled'];
    
    protected array $validationRules = [
        'order_id' => 'required',
        'amount' => 'type:double'
    ];

    public function handle(array $context): array
    {
        return [
            'status' => 'pending',
            'message' => 'Order is being validated',
            'order_id' => $context['order_id']
        ];
    }
}

// Create your state machine
class Order
{
    use StateMachineTrait;

    public function __construct(string $orderId)
    {
        // Initialize states
        $this->addState(new PendingState(), true)
            ->addState(new ProcessingState())
            ->addState(new ShippedState());

        // Set initial context
        $this->updateContext([
            'order_id' => $orderId,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function process(array $paymentDetails): array
    {
        $this->transitionTo('processing', $paymentDetails);
        return $this->getCurrentState()->handle($this->getContext());
    }
}

// Usage
$order = new Order('ORD-123');

try {
    $result = $order->process([
        'payment_id' => 'PAY-456',
        'amount' => 99.99
    ]);
    echo $result['message']; // "Payment verified, preparing shipment"
} catch (StateException $e) {
    echo "Error: " . $e->getMessage();
}
```

#### Real-World Example: Order Processing System

The State pattern is perfect for managing complex workflows like order processing. Each state encapsulates its own rules and behaviors:

1. **States**:
   - `PendingState`: Initial state, validates order details
   - `ProcessingState`: Handles payment verification
   - `ShippedState`: Manages shipping details
   - `DeliveredState`: Handles delivery confirmation
   - `CancelledState`: Manages order cancellation

2. **Features**:
   - Context validation per state
   - Type-safe state transitions
   - State history tracking
   - Fluent interface for state machine setup

3. **Benefits**:
   - Clean separation of concerns
   - Easy to add new states
   - Type-safe state transitions
   - Automatic context validation
   - Comprehensive state history

4. **Use Cases**:
   - Order Processing Systems
   - Document Workflow Management
   - Game State Management
   - Payment Processing
   - Task Management Systems

### 10. Pipeline Pattern

The Pipeline pattern allows you to process data through a series of operations, where each operation takes input from the previous operation and produces output for the next one. This pattern is particularly useful for data transformation, validation, and processing workflows.

#### Features
- Fluent interface for operation chaining
- Built-in error handling
- Input validation
- Type-safe operations with PHP 8.2+ generics
- Side effect management
- Conditional processing
- Operation composition

#### Basic Usage

```php
use DesiredPatterns\Pipeline\Pipeline;

// Basic pipeline
$result = Pipeline::of(5)
    ->pipe(fn($x) => $x * 2)    // 10
    ->pipe(fn($x) => $x + 1)    // 11
    ->get();                     // Returns: 11

// Pipeline with error handling
$result = Pipeline::of($value)
    ->try(
        fn($x) => processData($x),
        fn(\Throwable $e) => handleError($e)
    )
    ->get();

// Pipeline with validation
$result = Pipeline::of($data)
    ->when(
        fn($x) => $x > 0,
        fn($x) => sqrt($x)
    )
    ->get();
```

#### Advanced Usage with PipelineBuilder

The PipelineBuilder provides a more structured way to create complex pipelines with validation and error handling:

```php
use DesiredPatterns\Pipeline\PipelineBuilder;

$builder = new PipelineBuilder();
$result = $builder
    ->withValidation(fn($x) => $x > 0, 'Value must be positive')
    ->withValidation(fn($x) => $x < 100, 'Value must be less than 100')
    ->withErrorHandling(fn(\Throwable $e) => handleValidationError($e))
    ->add(fn($x) => $x * 2)
    ->add(fn($x) => "Result: $x")
    ->build(50)
    ->get();
```

#### Real-World Example: Data Processing Pipeline

Here's a real-world example of using the Pipeline pattern for processing user data:

```php
class UserDataProcessor
{
    private PipelineBuilder $pipeline;

    public function __construct()
    {
        $this->pipeline = new PipelineBuilder();
        $this->pipeline
            ->withValidation(
                fn($data) => isset($data['email']),
                'Email is required'
            )
            ->withValidation(
                fn($data) => filter_var($data['email'], FILTER_VALIDATE_EMAIL),
                'Invalid email format'
            )
            ->withErrorHandling(fn(\Throwable $e) => [
                'success' => false,
                'error' => $e->getMessage()
            ])
            ->add(function($data) {
                // Normalize email
                $data['email'] = strtolower($data['email']);
                return $data;
            })
            ->add(function($data) {
                // Hash password if present
                if (isset($data['password'])) {
                    $data['password'] = password_hash(
                        $data['password'],
                        PASSWORD_DEFAULT
                    );
                }
                return $data;
            })
            ->add(function($data) {
                // Add metadata
                $data['created_at'] = new DateTime();
                $data['status'] = 'active';
                return $data;
            });
    }

    public function process(array $userData): array
    {
        return $this->pipeline
            ->build($userData)
            ->get();
    }
}

// Usage
$processor = new UserDataProcessor();

// Successful case
$result = $processor->process([
    'email' => 'user@example.com',
    'password' => 'secret123'
]);
// Returns: [
//     'email' => 'user@example.com',
//     'password' => '$2y$10$...',
//     'created_at' => DateTime,
//     'status' => 'active'
// ]

// Error case
$result = $processor->process([
    'email' => 'invalid-email'
]);
// Returns: [
//     'success' => false,
//     'error' => 'Invalid email format'
// ]
```

#### Benefits
1. **Separation of Concerns**: Each operation in the pipeline has a single responsibility.
2. **Maintainability**: Easy to add, remove, or modify processing steps without affecting other parts.
3. **Reusability**: Pipeline operations can be reused across different contexts.
4. **Error Handling**: Built-in error handling makes it easy to manage failures.
5. **Validation**: Input validation can be added at any point in the pipeline.
6. **Type Safety**: PHP 8.2+ generics provide type safety throughout the pipeline.
7. **Testability**: Each operation can be tested in isolation.

#### Use Cases
- Data transformation and normalization
- Form validation and processing
- API request/response handling
- Image processing workflows
- ETL (Extract, Transform, Load) operations
- Document processing pipelines
- Multi-step validation processes

### 11. Object Pool Pattern
The Object Pool pattern manages a fixed set of reusable objects that are expensive to create or consume significant resources. Our implementation uses PHP 8.2 features for type-safe object management and automatic cleanup.

```php
use DesiredPatterns\Contracts\PoolableInterface;
use DesiredPatterns\Pool\ObjectPool;
use DesiredPatterns\Pool\PoolFactory;

// Define a poolable resource
class DatabaseConnection implements PoolableInterface
{
    private ?PDO $connection = null;
    
    public function __construct(
        private readonly string $dsn,
        private readonly string $username,
        private readonly string $password
    ) {}
    
    public function reset(): void
    {
        if ($this->connection) {
            $this->connection->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
        }
    }
    
    public function validate(): bool
    {
        try {
            $this->connection?->query('SELECT 1');
            return true;
        } catch (PDOException) {
            $this->connection = null;
            return false;
        }
    }
}

// Use the pool
$pool = PoolFactory::getPool(
    'database',
    DatabaseConnection::class,
    [
        'min_instances' => 2,
        'max_instances' => 10,
        'constructor_args' => [
            'mysql:host=localhost;dbname=test',
            'username',
            'password'
        ]
    ]
);

// Acquire and use a connection
$connection = $pool->acquire();
try {
    // Use the connection
} finally {
    $pool->release($connection);
}
```

1. **Key Features**:
   - Type-safe resource management
   - Automatic resource cleanup using WeakMap
   - Configurable pool sizes
   - Resource validation and reset
   - Usage statistics tracking

2. **Use Cases**:
   - Database connection pooling
   - File handle management
   - Network socket management
   - Thread/Process pooling
   - Memory-intensive object reuse

### 12. Null Object Pattern
The Null Object pattern provides an object with neutral ("null") behavior as an alternative to null references. Our implementation uses PHP 8.2 features for type-safe null handling and interface contracts.

```php
use DesiredPatterns\NullObject\NullableInterface;
use DesiredPatterns\NullObject\AbstractNullObject;

// Define the interface
interface LoggerInterface extends NullableInterface
{
    public function log(string $level, string $message, array $context = []): void;
    public function getLogs(): array;
}

// Real implementation
class FileLogger implements LoggerInterface
{
    public function __construct(
        private readonly string $logFile
    ) {}
    
    public function log(string $level, string $message, array $context = []): void
    {
        file_put_contents(
            $this->logFile,
            "[$level] $message " . json_encode($context) . PHP_EOL,
            FILE_APPEND
        );
    }
    
    public function getLogs(): array
    {
        return file($this->logFile);
    }
    
    public function isNull(): bool
    {
        return false;
    }
}

// Null implementation
class NullLogger extends AbstractNullObject implements LoggerInterface
{
    public function log(string $level, string $message, array $context = []): void
    {
        // Do nothing
    }
    
    public function getLogs(): array
    {
        return [];
    }
}

// Usage
class UserService
{
    public function __construct(
        private readonly LoggerInterface $logger = new NullLogger()
    ) {}
    
    public function createUser(string $username): void
    {
        // Create user...
        $this->logger->log('info', 'User created', ['username' => $username]);
    }
}
```

1. **Key Features**:
   - Type-safe null object implementation
   - Interface-based contracts
   - Abstract base class for null objects
   - Explicit null checking through interface
   - Zero-impact performance for null operations

2. **Use Cases**:
   - Optional service dependencies
   - Testing and development environments
   - Feature toggles and graceful degradation
   - Default behavior implementation
   - Error handling and logging

## Testing

Run the test suite using PHPUnit :

```bash
vendor/bin/phpunit
```

Or run it updating the coverage report:

```bash
vendor/bin/phpunit --coverage-html coverage
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This library is licensed under the GNU General Public License v3.0 - see the LICENSE file for details.
