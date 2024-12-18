<?php

namespace Examples\Command\User;

use DesiredPatterns\Commands\AbstractCommand;

class CreateUserCommand extends AbstractCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $name = null
    ) {
        parent::__construct([
            'email' => $email,
            'password' => $password,
            'name' => $name,
        ]);
    }
}
