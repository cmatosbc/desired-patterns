<?php

declare(strict_types=1);

namespace Examples\State\Order\States;

use DesiredPatterns\State\AbstractState;

class DeliveredState extends AbstractState
{
    public function getName(): string
    {
        return 'delivered';
    }

    protected array $allowedTransitions = [];  // Final state

    protected array $validationRules = [
        'order_id' => 'required',
        'delivery_date' => 'required',
        'signature' => 'required'
    ];

    public function handle(array $context): array
    {
        // Complete the order and trigger post-delivery actions
        return [
            'status' => 'delivered',
            'message' => 'Order has been delivered',
            'order_id' => $context['order_id'],
            'delivery_date' => $context['delivery_date'],
            'signature' => $context['signature'],
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}
