<?php

namespace App\Http\Controllers\Front;

use Log;
use App\Models\Order;
use App\Models\Payment;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class PaymentsController extends Controller
{
    //
    public function create(Order $order)
    {
        return view('front.payments.create', [
            'order' => $order,
        ]);
    }

    public function createStripePaymentIntent(Order $order)
    {
        try {
            // Validate that the order exists and has items
            if (!$order || $order->items->isEmpty()) {
                return response()->json(['error' => 'Order does not have any items'], 400);
            }
    
            // Calculate the total amount in the smallest currency unit (e.g., cents for USD)
            $amount = $order->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });
    
            // Ensure the amount is an integer (convert to cents for USD)
            $amountInCents = round($amount * 100);
    
            // Initialize the Stripe client
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret_key'));
    
            // Create the Payment Intent
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
            ]);
    
            // Return the client secret
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Stripe Payment Intent Creation Failed: ' . $e->getMessage());
    
            // Return an error response
            return response()->json(['error' => 'Failed to create payment intent: ' . $e->getMessage()], 500);
        }
    }

    public function confirm(Request $request, Order $order)
    {
          // Initialize the Stripe client
          $stripe = new \Stripe\StripeClient(config('services.stripe.secret_key'));

        $paymentIntent = $stripe->paymentIntents->retrieve($request->query('payment_intent'),[] );

    
        if($paymentIntent->status == 'succeeded'){

        $payment = new Payment();
            // Update the order status to "paid"
            $payment->forceFill([
                'order_id' =>$order->id,
                'payment_amount' => $paymentIntent->amount /100,
                'payment_currency' => $paymentIntent->currency,
                'payment_status' => 'completed',
                'transaction_id' => $paymentIntent->id,
                'transaction_data' => json_encode($paymentIntent),
                'payment_method' => 'stripe',

           ]);

           $payment->save();

           event('payment.created' , $payment->id);

           return redirect()->route('home',[
            'status' => 'payment-succeeded',
           ]);
        }


        
    }

}
