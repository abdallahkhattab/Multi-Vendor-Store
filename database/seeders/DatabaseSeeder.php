<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use App\Models\Dashboard\Store;

use Illuminate\Database\Seeder;
use App\Models\Dashboard\Product;
use App\Models\Dashboard\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        /*
        Store::factory()->count(5)->create();
        Category::factory()->count(10)->create();
        Product::factory()->count(100)->create();*/

        //User::factory(2)->create();
        Admin::factory(3)->create();

    }
}
