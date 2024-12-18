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
