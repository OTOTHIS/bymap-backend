<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function getSubcategoriesByCategoryId($category_id)
    {
      try {
        $subcategories = Subcategory::where('category_id', $category_id)->get();
        return response()->json([$subcategories], 200);
      } catch (\Throwable $th) {
        return response()->json(["msg" => "no sub categries"], 404);
      }
    }
}
