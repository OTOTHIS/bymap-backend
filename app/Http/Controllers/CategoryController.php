<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

//         $data = Category::withCount('products')
//         ->with(['subcategories' => function ($query) {
//             $query->withCount('products');
//         }])
//         ->get();
// return response()->json($data, 200);


// $categories = Category::withCount('products')
// ->with(['subcategories' => function ($query) {
//     $query->withCount('products');
// }])
// ->get();

// // Iterate through categories to verify counts
// $categories->transform(function ($category) {
// $categoryProductsCount = $category->products_count;
// $subcategoriesProductsCount = $category->subcategories->sum('products_count');

// // If counts don't match, set category's products_count to subcategories sum
// if ($categoryProductsCount != $subcategoriesProductsCount) {
// $category->products_count = $subcategoriesProductsCount;
// }

// // Extracting only necessary attributes for categories and subcategories
// return [
// 'id' => $category->id,
// 'name' => $category->name,
// 'products_count' => $category->products_count,
// 'subcategories' => $category->subcategories->map(function ($subcategory) {
// return [
// 'id' => $subcategory->id,
// 'name' => $subcategory->name,
// 'products_count' => $subcategory->products_count
// ];
// })
// ];
// });

// return response()->json($categories, 200);







// $categories = Category::withCount('products')
// ->with(['subcategories' => function ($query) {
//     $query->withCount('products');
// }])
// ->get();

// // Iterate through categories to verify counts
// $categories->transform(function ($category) {
// $categoryProductsCount = $category->products_count;
// $subcategoriesProductsCount = $category->subcategories->sum('products_count');

// // If counts don't match, set category's products_count to subcategories sum
// if ($categoryProductsCount != $subcategoriesProductsCount) {
// $category->products_count = $subcategoriesProductsCount;
// }

// return $category;
// });


$categories= Category::all();
return $categories;


    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(category $category)
    {
        //
    }
}
