<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Dashboard\Product;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        return Product::filter($request->query())->with('category:id,name', 'store:id,name', 'tags:id,name')->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'status' => 'in:active,inactive',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|gt:price',
        ]);

        //    $data['slug'] = Str::slug($data['name']) . '-' . uniqid();

        $product = Product::create($data);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product

        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        /*  we cant use (with) here
         return $product->with('category')->first();
        */
        return $product->load('category:id,name', 'store:id,name', 'tags:id,name');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'status' => 'in:active,inactive',
            'compare_price' => 'nullable|numeric|gt:price',
        ]);

        $product->update($data);
        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the product by ID
        $deleted_product = Product::find($id);
    
        // Check if the product exists
        if (!$deleted_product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        // Delete the product
        $deleted_product->delete();
    
        // Return the response with the deleted product
        return response()->json([
            'message' => 'Product deleted successfully',
            'deleted_product' => $deleted_product
        ], 200); // Return HTTP 200 for successful deletion
    }
    }
