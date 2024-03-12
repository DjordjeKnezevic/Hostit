@if (isset($pricing))
    <ul class="list-group" id="pricingList">
        <h5 class="mt-2">Pricing information:</h5>
        <li class="list-group-item pricing-period">Period: {{ ucfirst($pricing->period) }}</li>
        <li class="list-group-item pricing-amount">Price: ${{ number_format($pricing->price, 2) }}
            @if ($pricing->period === 'monthly' || $pricing->period === 'yearly')
                (Paid upfront)
            @endif
        </li>
    </ul>
@else
    <ul class="list-group" id="pricingList">
    </ul>
@endif
