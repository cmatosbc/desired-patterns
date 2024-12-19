<?php

namespace Examples\Chain\PaymentHandlers;

use DesiredPatterns\Chain\AbstractHandler;

class CashHandler extends AbstractHandler
{
    public function handle($request)
    {
        if ($request['type'] === 'cash') {
            return [
                'status' => 'success',
                'message' => 'Payment processed via cash',
                'amount' => $request['amount']
            ];
        }
        
        return parent::handle($request);
    }
}
