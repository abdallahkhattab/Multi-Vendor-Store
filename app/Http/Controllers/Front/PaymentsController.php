<?php

namespace App\Http\Controllers\Front;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentsController extends Controller
{
    //
    public function create(Order $order){
        return view('front.payments.create',[
            'order' => $order
        ]);
    }

    public function createStripePaymentIntent(Order $order){
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret_key'));
        $amount = $order->items->sum(function($item){
            return $item->price * $item->quantity;
        });

        $paymentIntent = $stripe->paymentIntents->create([
        'amount' => $amount,
        'currency' => 'usd',
        'payment_method_types' => ['card'],
        'metadata' => ['order_id' => '6735'],
        ]);

        return [
            'client_secret' => $paymentIntent->client_secret,
        ];

    } 
}
