<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\Dashboard\Cart;

class CartObserver
{
    /**
     * Handle the Cart "creating" event.
     */
    public function creating(Cart $cart): void
    {
        //
     // Automatically generate a UUID for the cart before saving it

        $cart->id  = Str::uuid();
        $cart->cookie_id = $cart::getCookieId();
        
    }

    /**
     * Handle the Cart "updated" event.
     */
    public function updated(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "deleted" event.
     */
    public function deleted(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "restored" event.
     */
    public function restored(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "force deleted" event.
     */
    public function forceDeleted(Cart $cart): void
    {
        //
    }
}
