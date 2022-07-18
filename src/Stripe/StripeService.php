<?php

namespace App\Stripe;

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
    public function getPaymentIntent($amount)
    {

        \Stripe\Stripe::setApiKey('sk_test_51KltkGDEBqijHsAmi00vC2n0OErlGsuUVDj5uqtPLhptQ1SyGMNW46EZxA16VOyNmbQPFxq0PHNkzgoqmacVAc6h0035b7ypdR');
     
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
