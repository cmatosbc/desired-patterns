<?php

namespace Examples\Command\User;

readonly class User
{
    public function __construct(
        public string $email,
        public string $password,
        public ?string $name = null
    ) {}
}
