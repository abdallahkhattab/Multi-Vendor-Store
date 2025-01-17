<?php

namespace App\Models\Dashboard;

use App\Models\User;
use Illuminate\Support\Str;
use App\Observers\CartObserver;
use Illuminate\Database\Eloquent\Model;
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

        static::observe(CartObserver::class);

        // for single event
        /*
        static::creating(function(Cart $cart){
            $cart->id = Str::uuid();
        });*/
    }

    public function user(){
        return $this->belongsTo(User::class);
    }


}
