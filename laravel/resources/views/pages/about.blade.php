@extends('layouts.layout')

@section('body_class', 'sub_page')

@section('content')
    <section class="about_section layout_padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-box">
                        <div class="heading_container">
                            <h2>About Hostit</h2>
                        </div>
                        <p>
                            At Hostit, we believe that web hosting is more than just providing web space and FTP access.
                            Our mission is to provide individuals and businesses with everything they need to express
                            themselves on the internet. Whatever you want to create a website for, be it a small family
                            photo album or a full-powered database-driven site, your choice of host is essential. At
                            Hostit, we provide dependable hosting services that offer you all the scalability, security,
                            and support you need to build your presence online and share it with the world.
                        </p>
                        <p>
                            Our commitment to your success is evident in our round-the-clock customer support, an extensive
                            knowledge base, and a vibrant community forum where you can share, learn, and grow. From
                            enterprise-level corporations to individual developers, we provide hosting solutions tailored to
                            meet your requirements. With Hostit, you can always expect reliable service, expert advice,
                            and a hosting experience that helps you reach your objectives.
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="img-box">
                        <img src="{{ asset('img/about-img.png') }}" alt="About Hostit">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="location_section">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>Global Presence</h2>
                <p>Our server locations span across the globe, ensuring high availability and performance.</p>
            </div>
            <div class="row">
                @foreach ($locations as $location)
                    <div class="col-md-4">
                        <div class="location_box">
                            <img src="{{ asset($location->image) }}" alt="{{ $location->name }}">
                            <div class="location_info">
                                <h4>{{ $location->city }}</h4>
                                <p>{{ $location->name }} - {{ $location->network_zone }}</p>
                            </div>
                        </div>
                    </div>
                    @if ($loop->iteration % 3 == 0 && !$loop->last)
            </div>
            <div class="row mt-4">
                @endif
                @endforeach
            </div>
        </div>
    </section>
@endsection
