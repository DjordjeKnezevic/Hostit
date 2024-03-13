@extends('layouts.layout')

@section('hero_content')
    <x-slider />
@endsection

@section('content')
    <x-services />

    @include('components.about')

    <section class="server_section">
        <div class="container ">
            <div class="row">
                <div class="col-md-6">
                    <a href="" class="img-box">
                        <img src="{{ Storage::url('img/server-img.jpg') }}" alt="">
                        <div class="play_btn">
                            <button>
                                <i class="fa fa-play" aria-hidden="true"></i>
                            </button>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <div class="heading_container">
                            <h2>
                                Rent a Server
                            </h2>
                            <p>
                                Rent a server from us and get the best value for your money. Our servers are fast, reliable,
                                and secure. We offer 24/7 support and a 99.9% uptime guarantee.
                            </p>
                        </div>
                        @if (Auth::user() && Auth::user()->hasVerifiedEmail())
                            <a href="{{ route('rent-server') }}">
                                Go to renting page
                            </a>
                        @else
                            <a href="{{ route('login') }}">
                                Go to renting page
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-pricing />

    <x-testimonials />

    @include('components.contact')
@endsection
