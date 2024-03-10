<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqsTableSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            [
                'question' => 'How can I reset my password?',
                'answer' => 'You can reset your password by clicking on "Forgot password?" at the login page and following the instructions.'
            ],
            [
                'question' => 'What types of support do you offer?',
                'answer' => 'We offer 24/7 support through live chat, email, and our ticketing system.'
            ],
            [
                'question' => 'How do you handle data backups?',
                'answer' => 'We perform daily backups of all hosted websites, ensuring your data is safe. You can restore a backup directly from your dashboard.',
            ],
            [
                'question' => 'What are the CPU and RAM limits for regular and premium accounts?',
                'answer' => 'Regular accounts are limited to 16 vCPUs and 32GB of RAM, while premium accounts enjoy unlimited CPU and RAM resources.'
            ],
            [
                'question' => 'How can I increase the number of CPU and RAM for my hosting plan?',
                'answer' => 'Upgrade to a premium hosting plan to enjoy increased CPU and RAM allocations suitable for your needs.'
            ],
            [
                'question' => 'How can I upgrade my hosting plan?',
                'answer' => 'You can upgrade your hosting plan directly from your dashboard. Navigate to the billing section and select the upgrade option.',
            ]
        ];

        foreach ($faqs as $faq) {
            DB::table('faqs')->insert($faq + ['created_at' => now(), 'updated_at' => now()]);
        }
    }
}
