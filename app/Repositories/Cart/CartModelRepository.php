<?php

namespace App\Repositories\Cart;

use Illuminate\Support\Str;
use App\Models\Dashboard\Cart;
use Illuminate\Support\Carbon;
use App\Models\Dashboard\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartModelRepository implements CartRepository
{

   // protected $cookieId;

   protected $items;

   public function __construct()
   {
     $this->items = collect([]);
   }

    public function get(): Collection
    {

         // return Cart::with('product')->where('cookie_id','=',$this->getCookieId())->get();
                 /* we defined global scope in cart so the code now is*/ 
                 //return Cart::with('product')->get();

        if(!$this->items->count()){
            $this->items = Cart::with('product')->get();
        }
        return $this->items;
       
    }

    public function add(Product $product, $quantity = 1)
    {
        // Find the cart item for the product and cookie_id
        $item = Cart::where('product_id', $product->id)
            ->first();
    
        if (!$item) {
               // Create a new cart item if it doesn't exist
        $cart = Cart::create([
            //  'cookie_id' => $this->getCookieId(),   we instead made observer 
              'product_id' => $product->id,
              'user_id' => Auth::id() ?? null,
              'quantity' => $quantity,
          ]);
          $this->get()->push($cart);
          return $cart;
        }
        // Increment the quantity if the item exists
        $item->increment('quantity', $quantity);

        
    
    }
    
    public function update($id, $quantity = 1):bool
    {

        $cartItem = Cart::find($id);

        if ($cartItem) {
            $cartItem->update(['quantity' => $quantity]);
            return true;
        }

        return false;

    
        
    }

    public function delete($id):bool
    {
       return Cart::where('id','=',$id)->delete() > 0;
    }


    public function empty()
    {
       // return Cart::delete(); //to fix error do
      // return Cart::truncate();  // Deletes all rows and resets the auto-increment counter (not relevant for UUIDs, but still an option)


       return Cart::query()->delete();  // Deletes all rows without resetting any auto-increment counter

        
    }

    public function total(): float
    {
       /*
       $total =  Cart::join('products','products.id','=','carts.product_id')
        ->selectRaw('SUM(Products.price * carts.quantity) as total')->value('total');
        return (float) ($total ?? 0);  // Return 0 if $total is null
*/
        return $this->get()->sum(function($item){
            return $item->product->price * $item->quantity;
         });
    }

    public function getByUserId($user_id){
        return Cart::where('user_id',$user_id);
    }



}