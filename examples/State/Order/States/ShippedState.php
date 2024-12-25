<?php

declare(strict_types=1);

namespace Examples\State\Order\States;

use DesiredPatterns\State\AbstractState;

class ShippedState extends AbstractState
{
    public function getName(): string
    {
        return 'shipped';
    }

    protected array $allowedTransitions = ['delivered'];

    protected array $validationRules = [
        'order_id' => 'required',
        'tracking_number' => 'required',
        'shipping_address' => 'required'
    ];

    public function handle(array $context): array
    {
        // Generate shipping label and notify courier
        return [
            'status' => 'shipped',
            'message' => 'Order has been shipped',
            'order_id' => $context['order_id'],
            'tracking_number' => $context['tracking_number'],
            'shipping_address' => $context['shipping_address'],
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}
