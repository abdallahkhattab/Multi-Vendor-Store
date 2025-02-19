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
            if (!$order || $order->items->isEmpty()) {
                return response()->json(['error' => 'Order does not have any items'], 400);
            }

            $amount = $order->items->sum(fn($item) => $item->price * $item->quantity);
            $amountInCents = round($amount * 100);

            $stripe = new StripeClient(config('services.stripe.secret_key'));

            // Create Stripe PaymentIntent
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
            ]);

            // Save Payment Record Before Confirmation
            $payment = new Payment();
            $payment->forceFill([
                'order_id' => $order->id,
                'payment_amount' => $amount,
                'payment_currency' => 'usd',
                'payment_status' => 'pending', // Mark as pending until confirmed
                'transaction_id' => $paymentIntent->id,
                'transaction_data' => json_encode($paymentIntent),
                'payment_method' => 'stripe',
            ]);
            $payment->save();

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe Payment Intent Creation Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create payment intent'], 500);
        }
    }

    public function confirm(Request $request, Order $order)
    {
        try {
            $stripe = new StripeClient(config('services.stripe.secret_key'));

            // Retrieve the payment intent from Stripe
            $paymentIntent = $stripe->paymentIntents->retrieve($request->query('payment_intent'), []);

            if ($paymentIntent->status === 'succeeded') {
                // Find the corresponding payment record and update its status
                $payment = Payment::where('transaction_id', $paymentIntent->id)->first();

                if ($payment) {
                    $payment->update([
                        'payment_status' => 'completed',
                        'transaction_data' => json_encode($paymentIntent),
                    ]);

                    event('payment.created', $payment->id);
                }

                return redirect()->route('home', ['status' => 'payment-succeeded']);
            } else {
                return redirect()->route('home', ['status' => 'payment-failed']);
            }
        } catch (\Exception $e) {
            Log::error('Stripe Payment Confirmation Failed: ' . $e->getMessage());
            return redirect()->route('home', ['status' => 'payment-error']);
        }
    }
}
