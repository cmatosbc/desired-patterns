<?php

namespace Tests\Examples\Chain;

use PHPUnit\Framework\TestCase;
use Examples\Chain\PaymentHandlers\CashHandler;
use Examples\Chain\PaymentHandlers\CreditCardHandler;
use Examples\Chain\PaymentHandlers\PayPalHandler;

class PaymentHandlersTest extends TestCase
{
    private $cashHandler;
    private $creditCardHandler;
    private $paypalHandler;

    protected function setUp(): void
    {
        $this->cashHandler = new CashHandler();
        $this->creditCardHandler = new CreditCardHandler();
        $this->paypalHandler = new PayPalHandler();

        // Set up the chain
        $this->cashHandler->setNext($this->creditCardHandler);
        $this->creditCardHandler->setNext($this->paypalHandler);
    }

    public function testCashPaymentHandling()
    {
        $request = [
            'type' => 'cash',
            'amount' => 100.00
        ];

        $result = $this->cashHandler->handle($request);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Payment processed via cash', $result['message']);
        $this->assertEquals(100.00, $result['amount']);
    }

    public function testCreditCardPaymentHandling()
    {
        $request = [
            'type' => 'credit_card',
            'amount' => 250.00,
            'card_number' => '1234'
        ];

        $result = $this->cashHandler->handle($request);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Payment processed via credit card', $result['message']);
        $this->assertEquals(250.00, $result['amount']);
        $this->assertEquals('1234', $result['card_last_four']);
    }

    public function testPayPalPaymentHandling()
    {
        $request = [
            'type' => 'paypal',
            'amount' => 75.50,
            'email' => 'test@example.com'
        ];

        $result = $this->cashHandler->handle($request);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Payment processed via PayPal', $result['message']);
        $this->assertEquals(75.50, $result['amount']);
        $this->assertEquals('test@example.com', $result['paypal_email']);
    }

    public function testUnknownPaymentType()
    {
        $request = [
            'type' => 'bitcoin',
            'amount' => 500.00
        ];

        $this->expectException(\Exception::class);

        $this->cashHandler->handle($request);
    }

    public function testChainOrder()
    {
        // Test that handlers are called in the correct order
        $request = [
            'type' => 'paypal',
            'amount' => 150.00,
            'email' => 'test@example.com'
        ];

        // Mock handlers to verify order
        $mockCash = $this->createMock(CashHandler::class);
        $mockCreditCard = $this->createMock(CreditCardHandler::class);
        $mockPayPal = $this->createMock(PayPalHandler::class);

        $mockCash->expects($this->once())
            ->method('handle')
            ->willReturnCallback(function($req) use ($mockCreditCard) {
                return $mockCreditCard->handle($req);
            });

        $mockCreditCard->expects($this->once())
            ->method('handle')
            ->willReturnCallback(function($req) use ($mockPayPal) {
                return $mockPayPal->handle($req);
            });

        $mockPayPal->expects($this->once())
            ->method('handle')
            ->willReturn([
                'status' => 'success',
                'message' => 'Payment processed via PayPal',
                'amount' => 150.00,
                'paypal_email' => 'test@example.com'
            ]);

        // Set up the chain with mocks
        $mockCash->setNext($mockCreditCard);
        $mockCreditCard->setNext($mockPayPal);

        $result = $mockCash->handle($request);
        $this->assertEquals('success', $result['status']);
    }
}
