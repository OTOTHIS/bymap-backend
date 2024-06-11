<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function transforms ($product) {
        // Remove "images/" from the image path stored in the database
        $imagePathWithoutImages = str_replace('images/', '', $product->image);
        
        // Adjust the image URL based on the modified path
        $product->image_url = asset("storage/{$imagePathWithoutImages}");
        
        return $product;
    }
    // public function index(Request $request)
  
 
    public function index(Request $request)
    {
        // Retrieve query parameters from the request
        $fields = $request->query('fields');
        $type = $request->query('type');
        $limit = $request->query('limit', 20); // Default limit is 4 if not specified
        $page = $request->query('page', 1);
        $sorttype = $request->query('sort_type',"asc"); // Default page is 1 if not specified
        $subcategory = $request->query('subcategory');
        $category  = $request->query('category');// Query parameter for category
        $customPrice = $request->query('customPrice'); // Query parameter for custom price range
        $latitude = $request->query('latitude'); // User's latitude
        $longitude = $request->query('longitude');
        $magazin = $request->query('magazin'); // User's magazin
        $search = $request->query('search'); // User's search

    
        // Initialize query builder
        $query = Product::query();
    
        if ($subcategory) {
            // Filter products by subcategory if provided
            $query->whereHas('category.subcategory', function ($query) use ($subcategory) {
                $query->where('name', $subcategory);
            });
        }
        if ($search) {
            $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%']);    
            }

        if ($category) {
            // Filter products by category if provided
            $query->whereHas('category', function ($query) use ($category) {
                $query->where('name', $category);
            });
        }

        if ($magazin) {
            // Filter products by category if provided
            $query->whereHas('magazins', function ($query) use ($magazin) {
                $query->where('name', $magazin);
            });
        }

        if ($customPrice) {
            // Filter products by custom price range if provided
            $priceRange = explode('-', $customPrice);
            $minPrice = $priceRange[0];
            $maxPrice = $priceRange[1];
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }
    
        if ($latitude && $longitude) {
            // Join with the magazins table
            $query->join('magazins', 'products.magazin_id', '=', 'magazins.id');
    
            // Calculate the distance between user's location and magazin's location
            $haversine = "(6371 * acos(cos(radians($latitude)) 
                          * cos(radians(magazins.Latitude)) 
                          * cos(radians(magazins.Longitude) - radians($longitude)) 
                          + sin(radians($latitude)) 
                          * sin(radians(magazins.Latitude))))";
    
            // Select distance as a calculated field
            $query->select('products.*')
                  ->selectRaw("$haversine AS distance")
                  ->orderBy('distance', 'asc');
        }
    
        // Sort by price or creation date if sorting parameters are provided
        $sortBy = $request->query('sort_by');

        if ($sortBy === 'price' && $sorttype ) {
            $query->orderBy('price' , $sorttype );
        } elseif ($sortBy === 'created_at') {
            $query->orderBy('created_at', 'desc');
        }
    
        // If fields and type are provided, handle specific queries
        if ($fields && $type) {
            if ($type == "latest") {
                // Fetch latest products based on fields and limit
                $data = $query->orderBy('created_at', 'desc')
                              ->select(explode(',', $fields))
                              ->limit($limit)
                              ->get();
            } else {
                // Handle other types of queries if needed
                return response()->json(["message" => "Invalid type parameter"], 400);
            }
        } else {
            // If no specific queries are provided, paginate the products
            $data = Product::inRandomOrder()->paginate($limit, ['*'], 'page', $page);
        }
    
        // Transform the data
        $transformedData = $data->map(function ($product) {
            return $this->transform($product);
        });
    
        // Return the JSON response with the count and transformed data
        return response()->json([
            "result" => $data instanceof \Illuminate\Pagination\LengthAwarePaginator ? $data->total() : $data->count(), 
            "data" => $transformedData,
            "current_page" => $data->currentPage(), // Current page
            "last_page" => $data->lastPage(), // Last page
        ]);
    }
    
    // protected function transform($product)
    // {
    //     // Remove "images/" from the image path stored in the database
    //     $imagePathWithoutImages = str_replace('images/', '', $product->image);
    
    //     // Adjust the image URL based on the modified path
    //     $product->image_url = asset("storage/{$imagePathWithoutImages}");
    
    //     // Return transformed product data
    //     return [
    //         'image' => $product->image, // Use the adjusted image URL
    //         'title' => $product->title,
    //         'id' => $product->id,
    //         'price' => $product->price,
    //         'oldprice' => $product->oldprice,
    //         'category_name' => optional($product->category)->name,
    //         'subcategory_name' => optional($product->category->subcategory)->name,
    //         'magazin_name' => optional($product->magazins)->name,
    //     ];
    // }
    private function transform($product)
{
      

    return [
        'id' => $product->id,
        'title' => $product->title,
        'price' => $product->price,
        // 'images' => $product->images,
        'images' => json_decode($product->images),
        // 'images' => is_string($product->images) 
        // ? array_map(function($image) { return str_replace('\/', '/', $image); }, json_decode($product->images, true)) 
        // : $product->images, 
            'category' => [
            'id' => $product->category->id,
            'name' => $product->category->name,
            'subcategory' => $product->category->subcategory ? [
                'id' => $product->category->subcategory->id,
                'name' => $product->category->subcategory->name,
            ] : null,
        ],
      'magazin_name' => optional($product->magazins)->name,
  

    ];
}







public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'magazin_id' => 'required|exists:magazins,id',
        'category_id' => 'required|exists:categories,id',
        'subcategory_id' => 'required|exists:subcategories,id',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:6048',
    ]);

 
    

    // Handle image upload
    if ($request->hasFile('image')) {
        $uploadedImage = $request->file('image');
        $imagePath = $uploadedImage->store('images/products'); // You can customize the storage path

        // Save the image path in the database
        $validatedData['image'] = $imagePath;
    }

    // Create a new product using Eloquent
    $product = Product::create($validatedData);

    // Return a response, e.g., the newly created product
    return response()->json($product, 201);
}

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        
      return  $product->load([
            'category' => function($query) {
                $query->select('id', 'name');
            },
            'subcategory' => function($query) {
                $query->select('id', 'name');
            },
            'magazins' => function($query) {
                $query->select('id', 'name');
            },
            'reviews' => function($query) {
                $query->select('id', 'product_id', 'user_id', 'commmenttitle','content', 'rating', 'created_at');
            },
            'reviews.user' => function($query) {
                $query->select('id', 'firstname' , 'lastname');
            }
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    //     public function destroy(Product $product)
// {
    // Delete the product
    // $product->delete();

    // return response()->json(['message' => 'Product deleted successfully']);

    public function destroy(Product $product)
    {


        $file_path = $product->image;
        //   $img = "images/products/ypSqWRtx7EbHfNynnmgcpBu1jCtGz09altoCsQor.png" ;

        // If the image path exists, attempt to delete the corresponding image file
        if ($file_path) {
            try {

                // Delete the image
                if (Storage::disk('local')->exists($file_path)) {
                    Storage::disk('local')->delete($file_path);
                    $product->delete();
                    // Image deleted successfully
                } else {
                    return response()->json(['message' => 'file not exist'], 404);
                }

            } catch (\Exception $e) {
                // Log or handle the exception as needed
                response()->json("Error deleting image: {$e->getMessage()}");
            }
        }

        return response()->json(['message' => 'Product and associated image deleted successfully']);
    }



}

