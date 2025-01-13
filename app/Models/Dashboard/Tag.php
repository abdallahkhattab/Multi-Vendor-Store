<?php

namespace App\Models\Dashboard;

use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug'];

    public $timestamps = false;

    public function products(){

        return $this->belongsToMany(Product::class);
    }
}
