<?php

declare(strict_types=1);

namespace Examples\State\Order\States;

use DesiredPatterns\State\AbstractState;

class PendingState extends AbstractState
{
    public function getName(): string
    {
        return 'pending';
    }

    protected array $allowedTransitions = ['processing', 'cancelled'];

    protected array $validationRules = [
        'order_id' => 'required',
        'total_amount' => 'type:double',
        'items' => 'type:array'
    ];

    public function handle(array $context): array
    {
        // Validate order and check inventory
        return [
            'status' => 'pending',
            'message' => 'Order is being validated',
            'order_id' => $context['order_id'],
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}
