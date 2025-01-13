<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = ['name','parent_id','slug','image','description','status'];
   // protected $guards = [];

   public static function newFactory()
    {
        return \Database\Factories\CategoryFactory::new();
    }

    public function products(){

        return $this->hasMany(Product::class,'category_id','id');
    
    }

    public function parent()
{
    return $this->belongsTo(Category::class, 'parent_id', 'id')->withDefault(['name' => '-']);
}


    public function children(){
        return $this->hasMany(Category::class,'parent_id','id');
    }


}
