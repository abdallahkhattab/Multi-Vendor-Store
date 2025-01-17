<?php

namespace App\Providers;

use App\Repositories\Cart\CartModelRepository;
use App\Repositories\Cart\CartRepository;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        /*
        Binds the alias 'cart' to a function that returns a new CartModelRepository instance.
        This ensures that every time you request 'cart' from the container,
         a new instance of CartModelRepository is created.
        */ 
        $this->app->bind(CartRepository::class,function(){
            return new CartModelRepository();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
