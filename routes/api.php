<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MagazinController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\UserController;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Magazin;
use App\Models\Order;
use App\Models\Owner;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware(['auth:sanctum', 'ability:user'])->prefix('user')->group(static function () {
   
    Route::get('/', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('carts', CartController::class);
    Route::resource('order', OrderController::class)->only([
        'show', 'store'
    ]);
    Route::get('/stripe_client', [StripeController::class, 'index']);
    Route::put('/', [UserController::class, 'update']);




});


Route::middleware(['auth:sanctum', 'ability:admin'])->prefix('admin')->group(static function () {
    Route::apiResources([
        'owners' => OwnerController::class,
    ]); 

    Route::get('/counts', function () {
        $magazinsCount = Magazin::count();
        $ownersCount = Owner::count();
        $productsCount = Product::count();
        $ordersCount = Order::count();

        return response()->json([
            'magazins' => $magazinsCount,
            'owners' => $ownersCount,
            'products' => $productsCount,
            'orders' => $ordersCount,
        ]);
    });
    
    Route::get('/', function (Request $request) {
        return $request->user();
    });
});


Route::middleware(['auth:sanctum', 'ability:owner'])->prefix('owner')->group(static function () {
    Route::apiResource('magazins', MagazinController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('orders', OrderController::class)->only("destroy");
    Route::get('/getorders', [OrderController::class, 'getOrderMagazin']);

    Route::put('upproducts/{id}', [ProductController::class, 'update']);
    Route::get('/producss', [OwnerController::class, 'getProductsByOwner']);
    Route::get('/magazin/{magazinId}/products', [OwnerController::class, 'getProductsByOwnerAndMagazin']);
   // Route::apiResource('products', ProductController::class);
    Route::get('/', function (Request $request) {
        return $request->user();
    });
});

Route::apiResource('magazins', MagazinController::class)->withoutMiddleware(['auth:sanctum', 'ability:owner']);
// Route::apiResource('categories', CategoryController::class)->withoutMiddleware(['auth:sanctum', 'ability:owner' , 'ability:admin','ability:buyer']);
// Route::apiResource('products', CategoryController::class)->withoutMiddleware(['auth:sanctum', 'ability:owner' , 'ability:admin','ability:buyer']);



Route::prefix('public')->group(function () {
    Route::get('categories', [CategoryController::class, 'index'])->withoutMiddleware(['auth:sanctum', 'ability:owner', 'ability:admin', 'ability:buyer']);
    Route::get('categories/{category}', [CategoryController::class, 'show'])->withoutMiddleware(['auth:sanctum', 'ability:owner', 'ability:admin', 'ability:buyer']);

    Route::get('products', [ProductController::class, 'index'])->withoutMiddleware(['auth:sanctum', 'ability:owner', 'ability:admin', 'ability:buyer']);
    Route::get('products/{product}', [ProductController::class, 'show'])->withoutMiddleware(['auth:sanctum', 'ability:owner', 'ability:admin', 'ability:buyer']);
    Route::get('products/filter', [ProductController::class, 'getAllProductsWithFilters'])->withoutMiddleware(['auth:sanctum', 'ability:owner', 'ability:admin', 'ability:buyer']);

    

    // Route::get('magazins', [MagazinController::class, 'getMagazinDetail'])->withoutMiddleware(['auth:sanctum', 'ability:owner', 'ability:admin', 'ability:buyer']);
    Route::get('magazins/{magazin}', [MagazinController::class, 'getMagazin'])
    ->withoutMiddleware(['auth:sanctum', 'ability:owner', 'ability:admin', 'ability:buyer']);
    
    Route::get('magazins', [MagazinController::class, 'magazinList'])->withoutMiddleware(['auth:sanctum', 'ability:owner', 'ability:admin', 'ability:buyer']);

    Route::get('subcategories/{subcategorie}', [SubcategoryController::class, 'getSubcategoriesByCategoryId'])->withoutMiddleware(['auth:sanctum', 'ability:owner', 'ability:admin', 'ability:buyer']);
    Route::get('subcategories', [SubcategoryController::class, 'index'])->withoutMiddleware(['auth:sanctum', 'ability:owner', 'ability:admin', 'ability:buyer']);

    Route::get('/magazinas', [MagazinController::class, 'paginatedMagazins']);
    Route::get('/Allmagazins', [MagazinController::class, 'getMagazinDetail']);


    
});

Route::get('magazins/populate', [MagazinController::class, 'getRandomMagazinWithProducts']);


require __DIR__ . '/auth.php';
