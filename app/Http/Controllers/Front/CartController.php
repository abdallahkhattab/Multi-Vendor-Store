<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Models\Dashboard\Product;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Repositories\Cart\CartModelRepository;
use App\Repositories\Cart\CartRepository;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CartRepository $cart)
    {   /*
        By binding the CartModelRepository to the 
        service container(Providers/CartServiceProvider) using the service provider
        , you no longer need to manually instantiate it
        */ 
        //$repository = new CartModelRepository();    
        //instead of it we use the down below code

       // $repository = App::make('cart');
        // $items = $repository->get();

        $items = $cart->get();

        return view ('front.cart',compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CartRepository $cart)
    {
        //
        $request->validate([
            'product_id'=> 'required|int|exists:products,id',
            'quantity'=> 'nullabl|int|min:1',
        ]);

       // $repository = new CartModelRepository();

        $product = Product::findOrFail($request->post('product_id'));
       // $repository->add($product,$request->post('quantity'));
         $cart->add($product,$request->post('quantity'));


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartRepository $cart)
    {
        //
        $request->validate([
            'product_id'=> 'required|int|exists:products,id',
            'quantity'=> 'nullabl|int|min:1',
        ]);
       // $repository = new CartModelRepository();

        $product = Product::findOrFail($request->post('product_id'));
        //$repository->update($product,$request->post('quantity'));
        $cart->update($product,$request->post('quantity'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartRepository $cart,$id)
    {
    
       // $repositary = new CartModelRepository();
        $$cart->delete($id);
    }
}
