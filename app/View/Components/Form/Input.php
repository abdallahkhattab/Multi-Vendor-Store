<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    /**
     * Create a new component instance.
     */

     public $type;
     public $name;
     public $placeholder;
     public $value;
     public $class;
    // public $label;
 
    public function __construct($type,$name,$placeholder = null,$value=null,$class='form-control')
    {
        //

        $this->type = $type;
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->class = $class;
      //  $this->label = $label;


    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.input');
    }
}
