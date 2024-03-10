<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialsTableSeeder extends Seeder
{
    public function run()
    {
        $testimonials = [
            [
                'name' => 'John Doe',
                'type' => 'Customer',
                'description' => 'The reliability and speed of their cloud servers have been outstanding. Our website has never been faster!',
                'image' => 'img/testimonials/testimonial1.jpg',
            ],
            [
                'name' => 'Jane Smith',
                'type' => 'Partner',
                'description' => 'Partnering with them has allowed us to scale our operations with ease. Highly recommend their services.',
                'image' => 'img/testimonials/testimonial2.jpg',
            ],
            [
                'name' => 'Alex Johnson',
                'type' => 'Customer',
                'description' => 'Their customer support is top-notch. Any issues are resolved quickly, ensuring our business runs smoothly.',
                'image' => 'img/testimonials/testimonial3.jpg',
            ],
            [
                'name' => 'Samantha Lee',
                'type' => 'Customer',
                'description' => 'We switched to their cloud hosting a year ago, and the improvement in our application\'s performance has been remarkable.',
                'image' => 'img/testimonials/testimonial4.jpg',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            DB::table('testimonials')->insert(array_merge($testimonial, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
