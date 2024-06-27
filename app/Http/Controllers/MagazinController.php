<?php

namespace App\Http\Controllers;

use App\Models\Magazin;
use Illuminate\Http\Request;

class MagazinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     // Retrieve magazins related to the authenticated owner
    //     $magazins = auth()->user()->magazins;

    //     return response()->json($magazins);
    // }


    public function getRandomMagazinWithProducts()
    {
        try {

            
            // Fetch a random magazin
            $magazin = Magazin::paginate(2);

            if (!$magazin) {
                return response()->json(['error' => 'No magazins found.'], 200);
            }

            // Get up to 3 products associated with the magazin
            $products = $magazin->products()->take(3)->get();

            return response()->json([
                'magazin' => $magazin,
                'products' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching magazin.'], 500);
        }
    }
    // public function paginatedMagazins(Request $request)
    // {
    //     // Paginate magazins
    //     $magazins = Magazin::paginate(3);

    //     // Transform the paginated collection to include up to 3 products for each magazin
    //     $magazins->getCollection()->transform(function ($magazin) {
    //         // Fetch only the title and image of the products
    //         $magazin->products = $magazin->products()->take(3)->get(['title', 'images']);
    //         return $magazin;
    //     });

    //     return response()->json($magazins);
    // }

    public function paginatedMagazins(Request $request)
    {  
        $paginant = $request->query('paginate',3);
        // Paginate magazins
        $magazins = Magazin::paginate($paginant);
    
        // Transform the paginated collection to include up to 3 products for each magazin
        $magazins->getCollection()->transform(function ($magazin) {
            // Fetch only the title and decode the images of the products
            $magazin->products = $magazin->products()-> take(4)->get(['title', 'images'])->map(function ($product) {
                $product->images = json_decode($product->images);
                return $product;
            });
    
            return $magazin;
        });
    
        return response()->json($magazins);
    }
    







    public function index()
    {
        // Retrieve magazins related to the authenticated owner
        $magazins = auth()->user()->magazins;
     
        // // Append asset URL to each image path
        // $magazins->transform(function ($magazin) {
        //     // Remove "images/" from the image path stored in the database
        //     $imagePathWithoutImages = str_replace('images/', '', $magazin->image);
            
        //     // Adjust the image URL based on the modified path
        //     $magazin->image_url = asset("storage/{$imagePathWithoutImages}");
            
        //     return $magazin;
        // });
    
        return response()->json($magazins);
    }

    public function magazinList(Request $request)
{
    $search = $request->query('search');
    
    if ($search) {
        $searched = Magazin::where('name', 'like', '%' . $search . '%')->get();
        return response()->json($searched);
    }

    $allMagazines = Magazin::paginate(10);
    return response()->json($allMagazines);
}


    public function getMagazinDetail()
    {
       
      try {
     return Magazin::all();
  
   
      } catch (\Throwable $th) {
        return response()->json(['error' => 'Magazins not founds.'], 404);
      }
    }
    
    public function getMagazin($id)
    {
        try {
            // Find the magazin by ID
            $magazin = Magazin::with(['products.category', 'products'])->findOrFail($id);            // Load the associated products
            $products = $magazin->products;
            
            // Return a combined response with the magazin and its products
            return response()->json([
                'magazin' => $magazin,
                // 'products' => $products,
            ]);
        } catch (\Exception $e) {
            // Handle the case where magazin with the given ID is not found
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    
    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'adresse' => 'required',
    //         'Latitude' => 'required',
    //         'Longitude' => 'required',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust file type and size as needed
    //     ]);
    
    //     if ($request->hasFile('image')) {
    //         $uploadedImage = $request->file('image');
    //         $imagePath = $uploadedImage->store('images/magazins'); // You can customize the storage path
    
    //         // You may also want to save the image path in the database
    //         $imageName = basename($imagePath);
    //     }
    
    //     $magazin = new Magazin([
    //         'name' => $request->input('name'),
    //         'adresse' => $request->input('adresse'),
    //         'Latitude' => $request->input('Latitude'),
    //         'Longitude' => $request->input('Longitude'),
    //         'owner_id' => auth()->user()->id,
    //         'image' => $imageName ?? null, // Save the image name in the database
    //     ]);
    
    //     $magazin->save();
    
    //     return response()->json($magazin, 201);
    // }
    
    public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'name' => 'required|string',
        'Latitude' => 'required|string',
        'Longitude' => 'required|string',
        // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
    ]);



    // Handle image upload
    if ($request->hasFile('image')) {
        $uploadedImage = $request->file('image');
        $imagePath = $uploadedImage->store('images/magazins'); // You can customize the storage path

        // Save the image path in the database
        $validatedData['image'] = $imagePath;
        $magazin = new Magazin([
            'name' => $request->input('name'),
            'adresse' => $request->input('adresse'),
            'Latitude' => $request->input('Latitude'),
            'Longitude' => $request->input('Longitude'),
            'owner_id' => auth()->user()->id,
            'image' => $imagePath, // Save the image name in the database
        ]);
        $magazin->save();
        return response()->json($magazin, 201);
    }

    // Create a new product using Eloquent
  

    // Return a response, e.g., the newly created product
   
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // $magazin = magazin::findOrFail($id);
        // return response()->json($magazin);

     
   
        try {
            $magazin = auth()->user()->magazins()->findOrFail($id);
            return response()->json($magazin);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Magazin not found'], 404);
        }

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'adresse' => 'required',
            'Latitude' => 'required',
            'Longitude' => 'required',
            'owner_id' => 'required|exists:owners,id',
        ]);

        $magazin = Magazin::findOrFail($id);
        $magazin->update($request->all());

        return response()->json($magazin);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $magazin = magazin::findOrFail($id);
        // $magazin->delete();

        // return response()->json(null, 204);
    }
}
