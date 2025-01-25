<?php

namespace App\Models;

use PDO;
use Illuminate\Support\Carbon;
use App\Models\Dashboard\Store;
use App\Models\Dashboard\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id' , 'user_id', 'payment_method' , 'status' , 'payment_status',
    ];

    public function store(){
        return $this->belongsTo(Store::class);
    }

    public function user(){
        return $this->belongsTo(User::class)->withDefault(['name' =>'Guest Customer']);
    }

    public function products(){
        return $this->belongsToMany(Product::class,'order_items','order_id','product_id','id','id')->as('order_item')
        ->using(OrderItem::class)->withPivot(['product_name','price','quantity','options']);
    }

    public function addresses(){
         return $this->hasMany(OrderAddress::class);

    }

    public function billingAddress(){
        return $this->hasOne(OrderAddress::class,'order_id','id')
        ->where('type','=','billing');
    }

    public function shippingAddress(){
        return $this->hasOne(OrderAddress::class,'order_id','id')
        ->where('type','=','billing');
    }

    protected static function booted(){

        static::creating(function(Order $order){
            $order->number =  Order::getNextOrderNumber();

        });
    }

    public static function getNextOrderNumber() {
        $year = Carbon::now()->year; // Get the current year (e.g., 2025)
        
        $number = Order::whereYear('created_at', $year)->max('number'); 
        // Get the highest `number` of orders created this year
    
        if ($number) {
            return $number + 1; // Increment the highest order number by 1
        }
    
        return $year . '0001'; // If no orders exist this year, start with "YYYY0001"
    }
    
}
