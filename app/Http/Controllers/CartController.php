<?php

/*
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
            $query->select('id', 'cart_id', 'product_id', 'quantity','taille');
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
    $taille = $request->input('taille'); // Get size from request

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
            'taille' => $taille
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

*/

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
        $cart = Cart::with(['cartItems' => function ($query) {
            $query->select('id', 'cart_id', 'product_id', 'quantity', 'taille');
        }, 'cartItems.product' => function ($query) {
            $query->select('id', 'title', 'price', 'images', 'magazin_id', 'category_id');
        }, 'cartItems.product.magazins' => function ($query) {
            $query->select('id', 'name');
        }, 'cartItems.product.category' => function ($query) {
            $query->select('id', 'name');
        }])->where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $totalPrice = 0;
        $totalItems = 0;
        $cartTaxe = 0;

        foreach ($cart->cartItems as $cartItem) {
            $totalPrice += $cartItem->product->price * $cartItem->quantity;
            $totalItems += $cartItem->quantity;
            $cartTaxe += $cartItem->product->price * (2 / 100);
        }

        return response()->json([
            'id' => $cart->id,
            'user_id' => $cart->user_id,
            'cart_items' => $cart->cartItems,
            'totalPrice' => $totalPrice,
            'totalItems' => $totalItems,
            'cartTaxe' => round($cartTaxe, 2),
            'total' => round($cartTaxe + $totalPrice, 2),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
    
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:1000',
            'taille' => 'nullable|string'
        ]);
    
        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity', 1); // Default quantity is 1 if not specified
        $taille = $request->input('taille'); // Get size from request
    
        // Check if the product already exists in the cart
        $existingCartItem = $cart->cartItems()->where('product_id', $product_id)->where('taille', $taille)->first();
    
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
                'taille' => $taille
            ]);
            return response()->json($cartItem, 201);
        }
    }

    




    // public function store(Request $request)
    // {
    //     $user = Auth::user();
    //     $cart = Cart::firstOrCreate(['user_id' => $user->id]);
    
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'quantity' => 'required|integer|min:1|max:1000',
    //         'taille' => 'nullable|string'
    //     ]);
    
    //     $product_id = $request->input('product_id');
    //     $quantity = $request->input('quantity', 1); // Default quantity is 1 if not specified
    //     $taille = $request->input('taille'); // Get size from request
    
    //     // Check if the product already exists in the cart
    //     $existingCartItem = $cart->cartItems()->where('product_id', $product_id)->where('taille', $taille)->first();
    
    //     if ($existingCartItem) {
    //         // If the item exists, update the quantity
    //         $existingCartItem->quantity += $quantity;
    //         $existingCartItem->save();
    //         return response()->json($existingCartItem, 200);
    //     } else {
    //         // If the item does not exist, create a new cart item
    //         $cartItem = $cart->cartItems()->create([
    //             'product_id' => $product_id,
    //             'quantity' => $quantity,
    //             'taille' => $taille
    //         ]);
    //         return response()->json($cartItem, 201);
    //     }
    // }
    



    // public function store(Request $request)
    // {
    //     $user = Auth::user();
    //     $cart = Cart::firstOrCreate(['user_id' => $user->id]);

    //     $product_id = $request->input('product_id');
    //     $quantity = $request->input('quantity', 1); // Default quantity is 1 if not specified
    //     $taille = $request->input('taille'); // Get size from request

    //     // Check if the product already exists in the cart
    //     $existingCartItem = $cart->cartItems()->where('product_id', $product_id)->first();

    //     if ($existingCartItem) {
    //         // If the item exists, update the quantity
    //         $existingCartItem->quantity += $quantity;
    //         $existingCartItem->save();
    //         return response()->json($existingCartItem, 200);
    //     } else {
    //         // If the item does not exist, create a new cart item
    //         $cartItem = $cart->cartItems()->create([
    //             'product_id' => $product_id,
    //             'quantity' => $quantity,
    //             'taille' => $taille
    //         ]);
    //         return response()->json($cartItem, 201);
    //     }
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'new_quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::findOrFail($id);
        $cartItem->quantity = $request->new_quantity;
        $cartItem->save();

        return response()->json(['message' => 'Quantity updated successfully']);
    }

    public function destroy(CartItem $cartItem)
    {
        // Ensure that the authenticated user owns the cart item
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        
        if ($cart && $cartItem->cart_id === $cart->id) {
 
            $cartItem->delete();
    
            return response()->json(null, 204);
        }
    

        // If the user does not own the cart item or the cart item does not exist
        return response()->json(['message' => 'CartItem not found or does not belong to the user'], 404);
    }
    
}
