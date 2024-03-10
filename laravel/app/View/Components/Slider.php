<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Slider extends Component
{
    public $slides;

    public function __construct()
    {
        $this->slides = [
            [
                'title' => 'Welcome to Next-Gen Hosting',
                'description' => 'Join us on a journey to seamless web hosting with our innovative technologies designed for developers and businesses alike.',
                'image' => 'img/slider-img.png',
                'readMoreLink' => '/about',
                'contactLink' => '/contact',
            ],
            [
                'title' => 'Fast & Secure Web Hosting',
                'description' => 'Experience cutting-edge performance and speed with our cloud hosting solutions. Tailored to your needs for scalability and reliability.',
                'image' => 'img/server-img.jpg',
                'readMoreLink' => '/about',
                'contactLink' => '/contact',
            ],
            [
                'title' => 'Reliable Cloud Infrastructure',
                'description' => 'Our cloud infrastructure provides a reliable foundation for your business, ensuring high availability and consistent performance.',
                'image' => 'img/server-img.png',
                'readMoreLink' => '/about',
                'contactLink' => '/contact',
            ],
        ];
    }

    public function render()
    {
        return view('components.slider');
    }
}
