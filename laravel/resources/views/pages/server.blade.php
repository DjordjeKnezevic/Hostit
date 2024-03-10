@extends('layouts.layout')

@section('body_class', 'sub_page')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4">Select your preferred region</h2>

        @foreach ($locations as $location)
            <div class="mb-3">
                <button class="btn btn-primary btn-lg btn-block d-flex align-items-center justify-content-between"
                    type="button" data-toggle="collapse" data-target="#serverDetails{{ $location->id }}" aria-expanded="false"
                    aria-controls="serverDetails{{ $location->id }}"
                    style="text-align: left; padding-left: 50px; background-size: cover; background-position: center;">
                    <div class="d-flex flex-column mr-3 p-2">
                        <p class="location-name d-block">{{ $location->name }}</p>
                        <p class="location-details d-block">{{ $location->network_zone }} - {{ $location->city }}</p>
                    </div>
                    <img src="{{ asset($location->image) }}" alt="">
                </button>
                <div class="collapse" id="serverDetails{{ $location->id }}">
                    @foreach ($location->servers as $server)
                        <div class="card card-body mt-2">
                            <h5 class="text-center">{{ $server->name }}</h5>
                            <ul>
                                <li>CPU: {{ $server->cpu_cores }} cores</li>
                                <li>RAM: {{ $server->ram }} GB</li>
                                <li>Storage: {{ $server->storage }} GB</li>
                                <li>Network Speed: {{ $server->network_speed }}</li>
                                <li>Weekly Backups</li>
                                <li>DDoS Protection</li>
                                <li>Full Root Access</li>
                                <li>24/7/365 Tech Support</li>
                            </ul>
                            <button class="btn btn-secondary mt-2" type="button" data-toggle="collapse"
                                data-target="#pricingPlans{{ $server->id }}" aria-expanded="false"
                                aria-controls="pricingPlans{{ $server->id }}">
                                Pricing Plans
                            </button>
                            <div class="collapse" id="pricingPlans{{ $server->id }}">
                                @foreach ($server->pricing as $price)
                                    <div class="card card-body">
                                        {{ ucfirst($price->period) }}: ${{ $price->price }}
                                    </div>
                                @endforeach
                            </div>
                            @auth
                                <a href="" class="btn btn-success mt-2">Rent now</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-success mt-2">Rent now</a>
                            @endauth
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
