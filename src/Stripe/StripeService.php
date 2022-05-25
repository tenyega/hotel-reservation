<?php

namespace App\Stripe;

use App\Repository\ProductRepository;


class StripeService
{
    protected $secretKey;
    protected $publicKey;
    protected $productRepository;

    public function __construct(string $secretKey, string $publicKey)
    {

        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
    public function getPaymentIntent($amount, $reservation)
    {

        \Stripe\Stripe::setApiKey('sk_test_51KltkGDEBqijHsAmi00vC2n0OErlGsuUVDj5uqtPLhptQ1SyGMNW46EZxA16VOyNmbQPFxq0PHNkzgoqmacVAc6h0035b7ypdR');
        // \Stripe\Stripe::setApiKey($this->secretKey);


        // $paymentIntent = \Stripe\PaymentIntent::create([
        //     'amount' => $purchase->getTotal($this->productRepository),
        //     'currency' => 'usd',
        // ]);
        header('Content-Type: application/json');
        
        return \Stripe\PaymentIntent::create([
            'amount' =>$amount,
            'currency' => "EUR",
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);
    }
}
