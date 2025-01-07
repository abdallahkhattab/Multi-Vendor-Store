<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }


    public function store(){
        return $this->belongsTo(Store::class,'store_id','id');
    }
}
