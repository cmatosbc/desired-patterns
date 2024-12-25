<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Examples\State\Order\Order;

// Create a new order
$order = new Order('ORD-123');

try {
    // Process the order with payment
    $result = $order->process([
        'payment_id' => 'PAY-456',
        'payment_status' => 'completed',
        'amount' => 99.99
    ]);
    echo "Order processed: " . $result['message'] . "\n";

    // Ship the order
    $result = $order->ship([
        'tracking_number' => 'TRK-789',
        'shipping_address' => '123 Main St, City, Country',
        'carrier' => 'FedEx'
    ]);
    echo "Order shipped: " . $result['message'] . "\n";

    // Mark as delivered
    $result = $order->deliver([
        'delivery_date' => date('Y-m-d'),
        'signature' => 'John Doe',
        'notes' => 'Left at front door'
    ]);
    echo "Order delivered: " . $result['message'] . "\n";

    // Get the complete state history
    $history = $order->getStateHistory();
    echo "\nOrder State History:\n";
    foreach ($history as $transition) {
        echo sprintf(
            "From %s to %s at %s\n",
            $transition['from'] ?? 'start',
            $transition['to'],
            $transition['timestamp']->format('Y-m-d H:i:s')
        );
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Example of order cancellation
try {
    $order2 = new Order('ORD-124');
    $result = $order2->cancel('Customer requested cancellation');
    echo "\nOrder 2: " . $result['message'] . "\n";
    echo "\nOrder 2 final state: " . $order2->getCurrentState()->getName() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
