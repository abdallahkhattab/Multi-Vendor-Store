<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    protected $fillable = ['name','parent_id','slug','image','description','status'];
   // protected $guards = [];

    public function products(){

        return $this->hasMany(Product::class,'category_id','id');
    
    }

    public function parent(){
        return $this->belongsTo(Category::class,'parent_id','id')
        ->withDefault(['name'=>'-']);
    }


    public function children(){
        return $this->hasMany(Category::class,'parent_id','id');
    }


}
