<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Services extends Component
{
    public $services;

    public function __construct()
    {
        $this->services = [
            [
                'title' => 'Server Renting',
                'description' => 'Rent a server with the best price and performance.',
                'image' => 'img/s1.png',
                'link' => '/servers',
                'linkText' => 'Start Renting Now'
            ],
            [
                'title' => 'Domain Registration',
                'description' => 'Secure your brand with the perfect domain name.',
                'image' => 'img/s6.png',
                'link' => null,
                'linkText' => null
            ],
        ];
    }

    public function render()
    {
        return view('components.services');
    }
}
