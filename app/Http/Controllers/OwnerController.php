<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreownerRequest;
use App\Http\Requests\UpdateownerRequest;
use App\Http\Resources\OwnerResource;
use App\Models\magazin;
use App\Models\Owner;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OwnerController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
      $data = OwnerResource::collection(Owner::all());
      return response()->json( $data, 200);
  }


  public function register(Request $request)
  {
      // Validate the request
      $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:owners',
          'password' => 'required|string|min:8|confirmed',
      ]);

      if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
      }

      // Create the owner
      $owner = Owner::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => Hash::make($request->password),
      ]);

      // Generate token for the owner
      $token = $owner->createToken('auth_token', ['owner'])->plainTextToken;

      return response()->json([
          'access_token' => $token,
          'token_type' => 'Bearer',
          'owner' => $owner,
      ]);
  }


  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreownerRequest $request)
  {
    $formFields = $request->validated();
    $formFields['password'] = Hash::make($formFields['password']);
    $formFields['last_login_date'] = new \DateTime();
    $owners= Owner::create($formFields);

    return new ownerResource($owners);
  }


  // public function getProductsByOwnerAndMagazin($magazinId=20)
  // {
  //    $ownerId = auth()->user()->getAuthIdentifier() ;
  //     // Get the owner by ID
  //     $owner = Owner::findOrFail($ownerId);

  //     // Get the magazin by ID, associated with the specified owner
  //     $magazin = $owner->magazins()->findOrFail($magazinId);

  //     // Get all products with categories for the specified magazin
  //     $products = $magazin->products()->with(['category' => function ($query) {
  //       $query->select('id', 'name');
  //   }])->get();

  //     return response()->json(['data' => $products]);
  // }


  // public function getProductsByOwner()
  // {
  //     $ownerId = auth()->user()->getAuthIdentifier();
  //     $owner = Owner::findOrFail($ownerId);
  
  //     $magazins = $owner->magazins; // Get the actual collection of magazins
  
  //     // Initialize an empty array to hold products
  //     $allProducts = [];
  
  //     foreach ($magazins as $magazin) {
  //         $products = $magazin->products()
  //             ->with(['category' => function ($query) {
  //                 $query->select('id', 'name');
  //             }])
  //             ->with('subcategory:id,name') // Load the subcategory relationship
  //             ->get();
  
  
  //         $allProducts = array_merge($allProducts, $products->toArray());
  //     }
  
  //     return response()->json(['data' => $allProducts]);
  // }

  public function getProductsByOwner()
  {
      $ownerId = auth()->user()->getAuthIdentifier();
      $owner = Owner::findOrFail($ownerId);
  
      $magazins = $owner->magazins; // Get the actual collection of magazins
  
      // Initialize an empty array to hold products
      $allProducts = [];
  
      foreach ($magazins as $magazin) {
          $products = $magazin->products()
              ->with(['category' => function ($query) {
                  $query->select('id', 'name');
              }])
              ->with('subcategory:id,name') // Load the subcategory relationship
              ->get()
              ->map(function ($product) use ($magazin) {
                  // Add the magazin_name to each product
                  $product->magazin_name = $magazin->name;
                  return $product;
              });
  
          $allProducts = array_merge($allProducts, $products->toArray());
      }
  
      return response()->json(['data' => $allProducts]);
  }


  // public function getProductsByOwnerAndMagazin($magazinId = 20)
  // {
  //     $ownerId = auth()->user()->getAuthIdentifier();
  
  //     $owner = Owner::findOrFail($ownerId);
  //     $magazin = $owner->magazins()->findOrFail($magazinId);
  
  //     $products = $magazin->products()->with(['category' => function ($query) {
  //         $query->select('id', 'name');
  //     }])->get();
  
  //     $products->transform(function ($product) {
  //         // Adjust the image URL based on the database structure
  //         $product->image_url = asset("/storage/{$product->image}");
  //         return $product;
  //     });
  
  //     return response()->json(['data' => $products]);
  // }
  
  public function showOwnerMagazins($ownerId)
  {
      // Find the owner by ID
      $owner = Owner::find($ownerId);

      if (!$owner) {
          return response()->json(['message' => 'Owner not found'], 404);
      }

      // Load the related Magazins
      $magazins = $owner->magazins;

      return response()->json(['owner' => $owner, 'magazins' => $magazins]);
  }
  /**
   * Display the specified resource.
   */
  public function show(Owner $owner)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateownerRequest $request, Owner $owner)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Owner $owner)
  {
    //
  }
}
