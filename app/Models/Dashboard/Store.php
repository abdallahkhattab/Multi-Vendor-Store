<?php

namespace App\Models\Dashboard;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory , Notifiable;

    public static function newFactory()
    {
        return \Database\Factories\StoreFactory::new();
    }

    public function products(){
        
        return $this->hasMany(Product::class);
    }
}
