<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends Controller
{
    
public function index()
{
    $user = Auth::user();
    $cart = Cart::where('user_id', $user->id)->first();
    if (!$cart || $cart->cartItems->isEmpty()) {
        return response()->json(['message' => 'Cart is empty'], 400);
    }
    Stripe::setApiKey(env('STRIPE_SECRET'));
    $amount = $cart->cartItems->sum(function ($cartItem) {
        return $cartItem->quantity * $cartItem->product->price;
    });

    try {
        $paymentIntent = PaymentIntent::create([
            'amount' => $amount * 100, // Amount in cents
            'currency' => 'MAD',
            'payment_method_types' => ['card'],
            'confirmation_method' => 'automatic',
        ]);

     return response()->json(['client_secret' => $paymentIntent->client_secret]);

    } catch (\Exception $e) {
        return response()->json(['message' => 'Payment failed: ' . $e->getMessage()], 400);
    }
}


}
