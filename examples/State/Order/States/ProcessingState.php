<?php

declare(strict_types=1);

namespace Examples\State\Order\States;

use DesiredPatterns\State\AbstractState;

class ProcessingState extends AbstractState
{
    public function getName(): string
    {
        return 'processing';
    }

    protected array $allowedTransitions = ['shipped', 'cancelled'];

    protected array $validationRules = [
        'order_id' => 'required',
        'payment_id' => 'required',
        'payment_status' => 'required'
    ];

    public function handle(array $context): array
    {
        // Process payment and prepare for shipping
        return [
            'status' => 'processing',
            'message' => 'Payment verified, preparing shipment',
            'order_id' => $context['order_id'],
            'payment_id' => $context['payment_id'],
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}
