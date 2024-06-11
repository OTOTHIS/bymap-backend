<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::with(['carts.cartItems.product' => function($query) {
            $query->select('id', 'title', 'category_id', 'image', 'price');
        }, 'carts.cartItems.product.category'])->findOrFail($id);

        return $user ;
    }
    public function update(Request $request){
      try {
        $user = User::findOrFail(auth()->id())->update($request->all());
      return $user ;
      } catch (\Throwable $th) {
        throw $th;
      }
    }
    
}
