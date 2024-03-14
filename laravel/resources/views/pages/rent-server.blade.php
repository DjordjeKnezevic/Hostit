@extends('layouts.layout')

@section('body_class', 'sub_page')

@section('content')
    <div class="container mb-4">
        <h2 class="text-center mb-4 my-4">Server Renting</h2>
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('process-renting') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="location">Location</label>
                        <select id="location" name="location" class="form-control" hx-get="{{ route('get-servers') }}"
                            hx-trigger="change" hx-target="#serverDropdown">
                            <option value="" selected="selected">Select a Location</option>
                            @foreach ($locations as $location)
                                <option
                                    value="{{ $location->id }}"{{ $selectedLocationId == $location->id ? ' selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="server">Server</label>
                        <select id="serverDropdown" name="server" class="form-control"
                            hx-get="{{ route('get-server-pricing') }}" hx-trigger="change" hx-target="#pricingDropdown"
                            hx-include="#location">
                            @if ($servers)
                                @foreach ($servers as $server)
                                    <option
                                        value="{{ $server->id }}"{{ $selectedServer && $selectedServer->id == $server->id ? ' selected' : '' }}>
                                        {{ $server->serverType->name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="">Please select a location first</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pricing">Pricing Plan</label>
                        <select id="pricingDropdown" name="pricing" class="form-control">
                            @if ($pricingPlans)
                                @foreach ($pricingPlans as $pricing)
                                    <option
                                        value="{{ $pricing->id }}"{{ $selectedPricing && $selectedPricing->id == $pricing->id ? ' selected' : '' }}>
                                        {{ ucfirst($pricing->period) }}: ${{ $pricing->price }}
                                        @if ($pricing->period === 'monthly' || $pricing->period === 'yearly')
                                            (Paid upfront)
                                        @endif
                                    </option>
                                @endforeach
                            @else
                                <option value="">Please select a location first</option>
                            @endif
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#confirmationModal"
                        id="openModalButton">Complete Renting</button>

                    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
                        aria-labelledby="confirmationModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Renting</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @if ($selectedServer && $selectedServer->location && $selectedPricing)
                                        Are you sure you want to rent the server <b>{{ $selectedServer->name }}</b> in
                                        region
                                        <b>{{ $selectedServer->location->name }}</b>?
                                        @if ($selectedPricing)
                                            @if ($selectedPricing->period === 'monthly' || $selectedPricing->period === 'yearly')
                                                <p>This will charge you <b>${{ $selectedPricing->price }}</b>.</p>
                                            @else
                                                <p>This will cost you <b>${{ $selectedPricing->price }}</b> per hour, you
                                                    can cancel the
                                                    subscription
                                                    at any
                                                    time.</p>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <input type="submit" class="btn btn-primary" id="confirmRenting" value="Confirm">
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div id="serverDetails" class="col-md-6">

                @include('partials.location_details', [
                    'location' => $selectedServer ? $selectedServer->location : null,
                ])

                @include('partials.server_details', ['server' => $selectedServer])

                @include('partials.pricing_details', ['pricing' => $selectedPricing])
            </div>
        </div>
    </div>


@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openModalButton = document.getElementById('openModalButton');
            const locationSelect = document.getElementById('location');
            const serverDropdown = document.getElementById('serverDropdown');
            const pricingDropdown = document.getElementById('pricingDropdown');
            const serverList = document.getElementById('serverList');
            const pricingList = document.getElementById('pricingList');
            const locationList = document.getElementById('locationList');
            const rentingForm = document.querySelector('form');

            let selectedServerName = '{{ $selectedServer->name ?? '' }}';
            let selectedLocationName = '{{ $selectedServer->location->name ?? '' }}';
            let selectedPricingPeriod = '{{ $selectedPricing->period ?? '' }}';
            let selectedPricingAmount = '{{ $selectedPricing->price ?? '' }}';

            function checkFormCompletion() {
                const allSelected = selectedServerName && selectedLocationName && selectedPricingPeriod &&
                    selectedPricingAmount;
                openModalButton.disabled = !allSelected;
                openModalButton.style.opacity = allSelected ? '1' : '0.5';
            }

            function updateModalText() {
                const modalBody = document.querySelector('#confirmationModal .modal-body');
                modalBody.innerHTML = '';

                let confirmationText =
                    `Are you sure you want to rent the server <b>${selectedServerName}</b> in region <b>${selectedLocationName}</b>?`;
                modalBody.innerHTML += confirmationText;

                let pricingText = '';
                if (selectedPricingPeriod.toLowerCase() === 'monthly' || selectedPricingPeriod.toLowerCase() ===
                    'yearly') {
                    pricingText = `<p>This will charge you <b>$${selectedPricingAmount}</b>.</p>`;
                } else if (selectedPricingPeriod.toLowerCase() === 'hourly') {
                    pricingText =
                        `<p>This will cost you <b>$${selectedPricingAmount}</b> per hour, you can cancel the subscription at any time.</p>`;
                }

                modalBody.innerHTML += pricingText;
                checkFormCompletion();
            }

            locationSelect.addEventListener('change', function() {
                serverDropdown.innerHTML = '<option value="">Please select a server first</option>';
                pricingDropdown.innerHTML =
                    '<option value="">Please select a pricing plan first </option>';
                serverList.innerHTML = '';
                pricingList.innerHTML = '';

                selectedServerName = '';
                selectedPricingPeriod = '';
                selectedPricingAmount = '';

                if (locationSelect.value === "") {
                    selectedLocationName = '';
                    updateModalText();
                } else {
                    selectedLocationName = locationSelect.options[locationSelect.selectedIndex].text;
                    fetch(`{{ url('/location-details') }}/${locationSelect.value}`)
                        .then(response => response.text())
                        .then(html => {
                            locationList.innerHTML = html;
                            updateModalText();
                        });
                }

            });

            serverDropdown.addEventListener('change', function() {
                pricingDropdown.innerHTML = '<option value="">Please select a pricing plan first</option>';
                selectedPricingPeriod = '';
                selectedPricingAmount = '';

                if (serverDropdown.value) {
                    fetch(`{{ url('/server-details') }}/${serverDropdown.value}`)
                        .then(response => response.text())
                        .then(html => {
                            serverList.innerHTML = html;
                            const serverNameElement = serverList.querySelector('.server-name');
                            selectedServerName = serverNameElement ? serverNameElement.textContent
                                .split(': ')[1] : '';
                            updateModalText();
                        });
                } else {
                    serverList.innerHTML = '';
                    updateModalText();
                }
                pricingList.innerHTML = '';

            });

            pricingDropdown.addEventListener('change', function() {
                if (pricingDropdown.value) {
                    fetch(`{{ url('/pricing-details') }}/${pricingDropdown.value}`)
                        .then(response => response.text())
                        .then(html => {
                            pricingList.innerHTML = html;
                            const pricingPeriodElement = pricingList.querySelector('.pricing-period')
                                .textContent;
                            const pricingAmountElement = pricingList.querySelector('.pricing-amount')
                                .textContent;

                            const periodMatch = pricingPeriodElement.match(/Period:\s*(\w+)/);
                            const priceMatch = pricingAmountElement.match(
                                /Price:\s*\$([\d,]+(\.\d{1,2})?)/);
                            selectedPricingPeriod = periodMatch ? periodMatch[1] : '';
                            selectedPricingAmount = priceMatch ? priceMatch[1].replace(',', '') : '';

                            updateModalText();
                        });
                } else {
                    pricingList.innerHTML = '';
                    updateModalText();
                }
            });

        });
    </script>
@endpush
