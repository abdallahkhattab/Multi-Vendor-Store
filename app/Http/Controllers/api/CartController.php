<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Dashboard\Cart;
use App\Models\Dashboard\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Facades\Validator;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $cart;

     public function __construct(CartRepository $cart )
     {
         $this->cart = $cart;
     }

     public function index(Request $request,CartRepository $cart)
     {
        return response()->json([
            'message' => 'Cart items retrieved successfully',
            'cart' =>$cart,
        ], 200);

    }

     

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CartRepository $cart)
    {
        // Validate the request data
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|gt:0',
        ]);

        // Find the product in the database
        $product = Product::find($data['product_id']);

        // If the product is not found, return a 404 response
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }


        // If the product doesn't exist, add it to the cart
        $added_item = $cart->add($product, $data['quantity']);

        // Return success response with the added item
        return response()->json([
            'message' => 'Product added to cart successfully',
            'data' => $added_item
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $cart_item = $this->cart->get()->firstWhere('id',$id);

        if(!$cart_item){
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        return response()->json([
            'message' => 'Cart item retrieved successfully',
            'data' => $cart_item
            ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1', // Ensure quantity is a positive integer
        ]);

        // If validation fails, return a 422 response with errors
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the cart item using the repository
        $updated = $this->cart->update($id, $request->quantity);

        // If the cart item was not found, return a 404 response
        if (!$updated) {
            return response()->json([
                'message' => 'Cart item not found',
            ], 404);
        }

        // Return a success response
        return response()->json([
            'message' => 'Cart item updated successfully',
        ], 200);
    }
 
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $deleted = $this->cart->delete($id);
        if (!$deleted) {
            return response()->json([
                'message' => 'Cart item not found',
                ], 404);
                }

                return response()->json([
                    'message' => 'Cart item deleted successfully',
                ], 200);
    }
}
