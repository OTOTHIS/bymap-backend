<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){

    }
  


    public function getOrderMagazin()
    {
        // Retrieve orders belonging to the authenticated user with items and user eager loaded
        $user = Auth::user();
        $orders = $user->orders()
                       ->with(['items', 'items.product']) // Eager load items, products, and user
                       ->orderByDesc('created_at')
                       ->get();
    
        // Check if any orders were found
        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found'], 404);
        }
    
        // Prepare the response array with orders, total price, and customer username
        $ordersWithTotalAndUsername = $orders->map(function ($order) {
            $totalPrice = $order->items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });
    
            return [
                'order' => $order,
                'total_price' => $totalPrice,
                'customer_firstname' => $order->user->firstname, // Retrieve first name from user relationship
                'customer_lastname' => $order->user->lastname, // Retrieve last name from user relationship
            ];
        });
    
        return response()->json(['orders' => $ordersWithTotalAndUsername]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }











public function store(Request $request)
{
   


    try {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
$order = $user->orders()->create();
    
// Convert cart items to order items
foreach ($cart->cartItems as $cartItem) {
    $order->items()->create([
        'product_id' => $cartItem->product_id,
        'quantity' => $cartItem->quantity,
    ]);
}
$cart->cartItems()->delete();
return response()->json(['order' => $order  , 'message'=>"payment succes" ]);



        

        // return response()->json(['client_secret' => $paymentIntent->client_secret]);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Payment failed: ' . $e->getMessage()], 400);
    }
}








    /**
     * Display the specified resource.
     */
    public function show($id)
    {

    
        // Get the latest order for the authenticated user
        $latestOrder = Order::findOrFail($id);
    
        // Check if an order exists
        if (!$latestOrder) {
            return response()->json(['message' => 'No orders found'], 404);
        }
    
        // Retrieve the items for the latest order
        $items = $latestOrder->items()
                             ->orderBy('created_at')
                             ->get();
    
        // Return the latest order with its items
        return response()->json([
            'order' => $latestOrder,
            'items' => $items
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
//     public function update(Request $request, Order $order)
// {
//     // Retrieve the new quantity from the request
//     $newQuantity = $request->input('quantity');

//     // Retrieve the cart item from the order
//     $cartItem = $order->cart_item;

//     // Calculate the updated quantity
//     $updatedQuantity = $cartItem->quantity + $newQuantity;

//     // Ensure the updated quantity is at least 1
//     if ($updatedQuantity < 1) {
//         $updatedQuantity = 1;
//     }

//     // Update the cart item's quantity
//     $cartItem->quantity = $updatedQuantity;
//     $cartItem->save();

//     // You might return a response indicating success or failure
//     return response()->json(['message' => 'Cart item quantity updated successfully']);
// }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            return response()->json(['message' => 'Order deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Order deletion failed: ' . $e->getMessage()], 500);
        }
    }
}
