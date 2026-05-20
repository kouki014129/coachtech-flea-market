<?php

namespace App\Services;

use App\Models\Item;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PurchaseService
{
    public function createCheckoutSession(Item $item, $user, array $data)
    {
        if ($data['payment_method'] === 'card') {
            $paymentMethodTypes = ['card'];
        } elseif ($data['payment_method'] === 'convenience_store') {
            $paymentMethodTypes = ['konbini'];
        } else {
            return null;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        return Session::create([
            'mode' => 'payment',
            'payment_method_types' => $paymentMethodTypes,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ],
            ],
            'customer_email' => $user->email,
            'metadata' => [
                'user_id' => $user->id,
                'item_id' => $item->id,
                'payment_method' => $data['payment_method'],
                'postal_code' => $data['postal_code'],
                'address' => $data['address'],
                'building' => $data['building'] ?? '',
            ],
            'success_url' => url('/purchase/success/' . $item->id . '?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/purchase/' . $item->id),
        ]);
    }

    public function retrieveCheckoutSession(string $sessionId)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        return Session::retrieve($sessionId);
    }
}