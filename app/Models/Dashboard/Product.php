<?php

namespace App\Models\Dashboard;

use Illuminate\Support\Str;
use App\Models\Scopes\StoreScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;



    protected $hidden = ['created_at','updated_at','deleted_at','image'];
    protected $appends = [
        'image_url',
    ];
    protected static function booted(){

     static::addGlobalScope(StoreScope::class);

      /* static::addGlobalScope('store',function(Builder $builder){
            $user = Auth()->user();
            if($user->store_id){
            $builder->where('store_id', '=',$user->store_id);

            }
        });*/

        static::creating(function(Product $product){
            $product->slug = Str::Slug($product->name).'-'.uniqid();
        });
    }

    public function scopeFilter(Builder $builder,$filters){

        $options = array_merge([
            'store_id' => null,
            'category_id' => null,
            'tags' => null,
            'status'=>'active',

        ],$filters);

        $builder->when($options['status'],function($builder,$status){
            $builder->where('status',$status);
        });


        $builder->when($options['store_id'],function($builder,$value){
            $builder->where('store_id',$value);
        });

        $builder->when($options['category_id'],function($builder,$value){
            $builder->where('category_id',$value);
        });


        $builder->when(array_key_exists('tag_id', $options), function ($builder) use ($options) {
            $builder->whereExists(function ($query) use ($options) {
                $query->select(DB::raw(1))
                      ->from('product_tag')
                      ->whereColumn('product_tag.product_id', 'products.id')
                      ->where('product_tag.tag_id', $options['tag_id']);
            });
      

           // $builder->whereRaw('id IN (SELECT product_id FROM product_tag WHERE tag_id = ?)',[$value]);
         //   $builder->whereRaw('EXISTS (SELECT 1 FROM product_tag WHERE tag_id = ? AND product_id = products.id)',[$value]);
            

            /*$builder->whereHas('tags',function($query)use($value){
                $query->whereIn('id',$value);
            });*/
        });
    }

    public static function scopeActive($query){
        $query->where('status', '=','active');
    }

    // Accessors

    public function getImageUrlAttribute(){


         return 'https://www.incathlab.com/images/products/default_product.png';


        if(Str::startsWith($this->image, ['http://','https://'])){
            return $this->image;
        }

        return asset('storage/' . $this->image);

    }

    public function getSalePercentAttribute(){

        if(!$this->compare_price){
            return 0;
        }
        return round(100 - (100 * $this->price / $this->compare_price),1);
    }



    public static function newFactory()
    {
        return \Database\Factories\ProductFactory::new();
    }
    
    protected $fillable = [
    'name',
    'store_id',
    'category_id',
    'slug',
    'description',
    'image',
    'price',
    'compare_price',
    'featured',
    'status'
];    


    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }


    public function store(){
        return $this->belongsTo(Store::class,'store_id','id');
    }


    public function tags(){
        return $this->belongsToMany(Tag::class,  // Related Model
        'product_tag', //pivot table name
        'product_id', // FK in pivot table for the current model
        'tag_id', // FK in pivot table for the related model
        'id',     //PK current model
        'id',    //PK related model
        );
    }
}
