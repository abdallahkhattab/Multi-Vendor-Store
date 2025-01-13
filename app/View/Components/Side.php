<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Side extends Component
{

    protected $items;

    protected $active;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //

        $this->items = config('side') ?? [] ;

        $this->active = Route::currentRouteName();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.side' , ['items' => $this ->items,
                    'active' => $this->active]);    
    }
}
