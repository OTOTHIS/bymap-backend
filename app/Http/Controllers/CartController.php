<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::with(['cartItems' => function($query) {
            $query->select('id', 'cart_id', 'product_id', 'quantity');
        }, 'cartItems.product' => function($query) {
            $query->select('id', 'title', 'price', 'images', 'magazin_id','category_id');
        }, 'cartItems.product.magazins' => function($query) {
            $query->select('id', 'name');
        }, 'cartItems.product.category' => function($query) {
            $query->select('id','name');
        }])->where('user_id', $user->id)->first();
    
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
    
        $totalPrice = 0;
        $totalItems = 0;
        $cartTaxe = 0 ;
    
        foreach ($cart->cartItems as $cartItem) {
            $totalPrice += $cartItem->product->price * $cartItem->quantity;
            $totalItems += $cartItem->quantity;
            $cartTaxe += $cartItem->product->price * (2 / 100) ;
        }
    
        $cart->totalPrice = $totalPrice;
        $cart->totalItems = $totalItems;
        $cart->cartTaxe = round($cartTaxe,2);
        $cart->total = round($cartTaxe + $totalPrice,2);

    
        return response()->json($cart);
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        
        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity', 1); // Default quantity is 1 if not specified
    
        // Check if the product already exists in the cart
        $existingCartItem = $cart->cartItems()->where('product_id', $product_id)->first();
    
        if ($existingCartItem) {
            // If the item exists, update the quantity
            $existingCartItem->quantity += $quantity;
            $existingCartItem->save();
            return response()->json($existingCartItem, 200);
        } else {
            // If the item does not exist, create a new cart item
            $cartItem = $cart->cartItems()->create([
                'product_id' => $product_id,
                'quantity' => $quantity,
                // Add other fields from the request as needed
            ]);
            return response()->json($cartItem, 201);
        }
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'new_quantity' => 'required|integer|min:1'
        ]);
    
        $cartItem = CartItem::findOrFail($id);
        $cartItem->quantity = $request->new_quantity ;
        $cartItem->save();
    
        return response()->json(['message' => 'Quantity updated successfully']);
    }
    
    
    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();
        return response()->json(null, 204);
    }
}
