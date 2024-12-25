<?php

declare(strict_types=1);

namespace Examples\State\Order;

use DesiredPatterns\State\StateMachineTrait;
use Examples\State\Order\States\{
    PendingState,
    ProcessingState,
    ShippedState,
    DeliveredState,
    CancelledState
};

class Order
{
    use StateMachineTrait;

    private string $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;

        // Initialize all possible states
        $this->addState(new PendingState(), true)  // Initial state
            ->addState(new ProcessingState())
            ->addState(new ShippedState())
            ->addState(new DeliveredState())
            ->addState(new CancelledState());

        // Set initial context
        $this->updateContext([
            'order_id' => $orderId,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function process(array $paymentDetails): array
    {
        $this->transitionTo('processing', $paymentDetails);
        return $this->getCurrentState()->handle($this->getContext());
    }

    public function ship(array $shippingDetails): array
    {
        $this->transitionTo('shipped', $shippingDetails);
        return $this->getCurrentState()->handle($this->getContext());
    }

    public function deliver(array $deliveryDetails): array
    {
        $this->transitionTo('delivered', $deliveryDetails);
        return $this->getCurrentState()->handle($this->getContext());
    }

    public function cancel(string $reason): array
    {
        $this->transitionTo('cancelled', ['cancellation_reason' => $reason]);
        return $this->getCurrentState()->handle($this->getContext());
    }
}
