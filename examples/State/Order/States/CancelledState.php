<?php

declare(strict_types=1);

namespace Examples\State\Order\States;

use DesiredPatterns\State\AbstractState;

class CancelledState extends AbstractState
{
    public function getName(): string
    {
        return 'cancelled';
    }

    protected array $allowedTransitions = [];  // Final state

    protected array $validationRules = [
        'order_id' => 'required',
        'cancellation_reason' => 'required'
    ];

    public function handle(array $context): array
    {
        // Process refund and cleanup
        return [
            'status' => 'cancelled',
            'message' => 'Order has been cancelled',
            'order_id' => $context['order_id'],
            'reason' => $context['cancellation_reason'],
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}
