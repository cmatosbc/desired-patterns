<?php

namespace DesiredPatterns\Chain;

use DesiredPatterns\Contracts\HandlerInterface;

/**
 * Abstract base class for implementing the Chain of Responsibility pattern.
 * 
 * This class provides the basic structure for handling requests through a chain
 * of handler objects. Each handler decides either to process the request or
 * to pass it along the chain.
 */
abstract class AbstractHandler implements HandlerInterface
{
    /** @var HandlerInterface|null The next handler in the chain */
    private $nextHandler;

    /**
     * Sets the next handler in the chain.
     *
     * @param HandlerInterface $handler The handler to be set as the next in the chain
     * @return HandlerInterface Returns the handler that was set as next
     */
    public function setNext(HandlerInterface $handler): HandlerInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    /**
     * Handles the incoming request or delegates it to the next handler.
     *
     * @param mixed $request The request to be handled
     * @return mixed The result of handling the request
     * @throws \Exception When no handler in the chain can process the request
     */
    public function handle($request)
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($request);
        }
        
        throw new \Exception(
            sprintf('No handler found for request: %s', json_encode($request))
        );
    }

    /**
     * Checks if there is a next handler in the chain.
     *
     * @return bool True if there is a next handler, false otherwise
     */
    protected function hasNext(): bool
    {
        return $this->nextHandler !== null;
    }

    /**
     * Gets the next handler in the chain.
     *
     * @return HandlerInterface|null The next handler or null if none exists
     */
    protected function getNext(): ?HandlerInterface
    {
        return $this->nextHandler;
    }
}
