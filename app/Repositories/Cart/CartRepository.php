<?php

namespace App\Repositories\Cart;

use App\Models\Dashboard\Product;
use Illuminate\Support\Collection;

interface CartRepository{

    public function get():Collection ;

    public function add(Product $product);

    public function update(Product $product,$quantity=1);

    public function delete($id);

    public function empty();

    public function total() : float;
    
    
}