<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Testimonial as TestimonialModel;

class Testimonials extends Component
{
    public $testimonials;

    public function __construct()
    {
        $this->testimonials = TestimonialModel::all();
    }

    public function render()
    {
        return view('components.testimonials');
    }
}
