<?php

namespace Examples\Chain\PaymentHandlers;

use DesiredPatterns\Chain\AbstractHandler;

class PayPalHandler extends AbstractHandler
{
    public function handle($request)
    {
        if ($request['type'] === 'paypal') {
            return [
                'status' => 'success',
                'message' => 'Payment processed via PayPal',
                'amount' => $request['amount'],
                'paypal_email' => $request['email'] ?? 'not_provided'
            ];
        }
        
        return parent::handle($request);
    }
}
