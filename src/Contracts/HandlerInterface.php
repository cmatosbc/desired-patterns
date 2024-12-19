<?php

namespace DesiredPatterns\Contracts;

/**
 * Interface for handling requests in the Chain of Responsibility pattern.
 *
 * This interface defines the methods that all concrete handlers must implement,
 * allowing them to set the next handler in the chain and to process incoming requests.
 */
interface HandlerInterface
{
    public function setNext(HandlerInterface $handler): HandlerInterface;
    public function handle($request);
}
