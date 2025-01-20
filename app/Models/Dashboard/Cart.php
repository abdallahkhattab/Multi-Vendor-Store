<?php

namespace App\Models\Dashboard;

use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Observers\CartObserver;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['cookie_id','user_id','product_id','quantity','options'];


    //Events (observers)
    //deleted ,deleting ,restoring ,restored , retrieved
    //saved ,saving ,creating ,created ,updating ,updated

    protected static  function booted(){

         // for single event
        /*
        static::creating(function(Cart $cart){
            $cart->id = Str::uuid();
        });*/

        static::observe(CartObserver::class);

        static::addGlobalScope('cookie_id',function(Builder $builder){
            $builder->where('cookie_id','=',Cart::getcookieId());
        });


      
    }

    public static function getCookieId(){
        $cookie_id = Cookie::get('cart_id');

        if(!$cookie_id){
            $cookie_id = Str::uuid();
            Cookie::queue('cart_id', $cookie_id, Carbon::now()->addDays(30)->diffInMinutes(Carbon::now()));
        }

        return $cookie_id;
    }

    public function user(){
        return $this->belongsTo(User::class)->withDefault([
            'name'=> 'Anonymous',
        ]);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }


}
