<option value="">Select the Pricing Plan</option>
@foreach ($pricingPlans as $pricing)
    <option value="{{ $pricing->id }}">
        {{ ucfirst($pricing->period) }} - ${{ $pricing->price }}
        @if ($pricing->period === 'monthly' || $pricing->period === 'yearly')
            (Paid upfront)
        @endif
    </option>
@endforeach
