@php
    $colors = [
        'hourly' => 'bg-blue',
        'monthly' => 'bg-green',
        'yearly' => 'bg-red',
    ];

    $color = $colors[$getState()] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="{{ $color }} px-2 py-1 rounded-lg">
    {{ ucfirst($getState()) }}
</span>

<style>
    .bg-blue {
        background-color: #3490dc;
        color: #fff;
    }

    .bg-green {
        background-color: #38c172;
        color: #fff;
    }

    .bg-red {
        background-color: #e3342f;
        color: #fff;
    }
</style>
