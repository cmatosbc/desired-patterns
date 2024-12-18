<?php

namespace Examples\Command\User;

use DesiredPatterns\Commands\AbstractCommandHandler;
use DesiredPatterns\Contracts\CommandContract;
use DesiredPatterns\Traits\CommandHandlerTrait;

class CreateUserHandler extends AbstractCommandHandler
{
    use CommandHandlerTrait;

    protected function supportedCommandClass(): string
    {
        return CreateUserCommand::class;
    }

    protected function process(CommandContract $command): User
    {
        assert($command instanceof CreateUserCommand);
        
        // In a real application, this would interact with a database
        return new User(
            email: $command->email,
            password: password_hash($command->password, PASSWORD_DEFAULT),
            name: $command->name
        );
    }
}
