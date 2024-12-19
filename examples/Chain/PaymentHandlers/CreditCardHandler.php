<?php

namespace Examples\Chain\PaymentHandlers;

use DesiredPatterns\Chain\AbstractHandler;

class CreditCardHandler extends AbstractHandler
{
    public function handle($request)
    {
        if ($request['type'] === 'credit_card') {
            return [
                'status' => 'success',
                'message' => 'Payment processed via credit card',
                'amount' => $request['amount'],
                'card_last_four' => $request['card_number'] ?? '****'
            ];
        }
        
        return parent::handle($request);
    }
}
