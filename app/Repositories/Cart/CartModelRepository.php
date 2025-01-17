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

    protected $cookieId;


    public function get(): Collection
    {

          return Cart::where('cookie_id','=',$this->getCookieId())->get();
       
    }

    public function add(Product $product, $quantity = 1)
    {

        return Cart::create([

            'cookie_id' => $this->getCookieId(),
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'quantity' => $quantity,

        ]);
   
    }

    public function update(Product $product, $quantity = 1)
    {

        Cart::where('product_id','=',$product->id)
        ->where('cookie_id','=',$this->getCookieId())->update([
            'quantity' => $quantity,
        ]);
        
    }

    public function delete(Product $product)
    {
       return Cart::where('id','=',$product->id)->delete();
    }


    public function empty()
    {
        return Cart::where('cookie_id','=',$this->getCookieId())->destroy();
        
    }

    public function total(): float
    {
       
       return Cart::where('cookie_id','=',$this->getCookieId())->join('products','products.id','=','carts.product_id')
        ->selectRaw('SUM(Products.price * carts.quantity) as total')->value('total');
    }

    protected function getCookieId(){
        $cookie_id = Cookie::get('cart_id');

        if(!$cookie_id){
            $cookie_id = Str::uuid();
            Cookie::queue('cart_id', $cookie_id, Carbon::now()->addDays(30));
        }

        return $cookie_id;
    }


}