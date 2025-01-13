<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Dashboard\Tag;
use App\Models\Dashboard\Product;
use App\Models\Dashboard\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
      
        $products = Product::with(['category','store'])->paginate(5);
        
        return view('dashboardPages.products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Category $categories)
    {
        //

    // Fetch parent categories (main categories)
   // $categories = Category::whereNull('parent_id')->get();

    // Fetch subcategories
 //   $subCategories = Category::whereNotNull('parent_id')->get();

       $categories = Category::with(['parent', 'children'])->whereNull('parent_id')->get();
        

        return view('dashboardPages.products.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        //

            // Validate the incoming data

        $cleanData = $request->validated();
        //dd($cleanData);

        // Handle image upload if a file is present
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Store the image in the 'products' folder on the public disk
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $tags = explode(',',$request->post('tags'));
        $tag_ids = [];

        foreach($tags as $t_name){

            $slug  = Str::slug($t_name);
            $tag = Tag::where('slug',$slug)->first();
            if(!$tag){
                $tag = Tag::create(['name'=>$t_name,'slug'=>$slug]);
            }

            $tag_ids [] = $tag->id;

        }

             // Merge the slug and image path into the clean data
             $cleanData['slug'] = Str::slug($cleanData['name']) . '-' . uniqid();
             $cleanData['image'] = $imagePath;
             $cleanData['store_id'] = auth()->user()->store_id ?? null;
     
             // Create a new product instance and save to the database
             $product = new Product($cleanData);
             $product->save();
             $product->tags()->sync($tag_ids);


       return redirect()->route('products.index')->with('success','Product Created Successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //

        $product = Product::with('category')->first();
        $categories = Category::all();
        $tags = implode(',',$product->tags()->pluck('name')->toArray());

        return view('dashboardPages.products.edit',compact('product','categories','tags'));
    }

    /**
     * Update the specified resource in storage.
     */

     
     public function update(ProductRequest $request, Product $product)
     {
         // Validate and sanitize input
         $cleanData = $request->validated();
     
         // Handle image upload if a new image is provided
         if ($request->hasFile('image')) {
             if ($product->image) {
                 Storage::disk('public')->delete($product->image);
             }
             $imagePath = $this->handleImageUpload($request->file('image'));
             $cleanData['image'] = $imagePath;
         }
     
         // Generate a unique slug if the product name changes
         $cleanData['slug'] = Str::slug($cleanData['name']);
     
         // Update the product with the new data
         $product->update($cleanData);
     
         // Handle tags
         if ($request->has('tags')) {
             $tags = explode(',', $request->input('tags'));
             $tag_ids = [];
     
             foreach ($tags as $t_name) {
                 $slug = Str::slug($t_name);
                 $tag = Tag::firstOrCreate(['slug' => $slug], ['name' => $t_name]);
                 $tag_ids[] = $tag->id;
             }
     
             // Sync tags with the product
             $product->tags()->sync($tag_ids);
         }
     
         return redirect()->route('products.index')->with('success', 'Product updated successfully!');
     }
     
     
     public function handleImageUpload($image)
     {
         if (!$image) {
             return null;
         }
     
         // Create a unique filename for the image
         $imageName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
     
         // Store the image in the public storage
         $path = $image->storeAs('products', $imageName, 'public');
     
         return $path;
     }
   
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //

        $product->delete();

        // Redirect the user back with a success message
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    
    }
}
