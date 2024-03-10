<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Testimonial extends Component
{
    public $testimonial;
public $active;

    public function __construct($testimonial, $active = false)
    {
        $this->testimonial = $testimonial;
        $this->active = $active;
    }

    public function render()
    {
        return view('components.testimonial');
    }
}
