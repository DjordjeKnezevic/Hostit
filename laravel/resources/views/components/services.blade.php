<section class="service_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>Our Services</h2>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            @foreach ($services as $service)
                <div class="col-md-6 col-lg-6 my-3">
                    <div class="box service-box position-relative text-center">
                        @unless ($service['link'])
                            <div class="coming-soon-overlay d-flex justify-content-center align-items-center">
                                <span class="coming-soon-text">Coming Soon</span>
                            </div>
                        @endunless
                        <div class="img-box">
                            <img src="{{ asset($service['image']) }}" alt="" class="img-fluid">
                        </div>
                        <div class="detail-box">
                            <h4>{{ $service['title'] }}</h4>
                            <p>{{ $service['description'] }}</p>
                            @if ($service['link'])
                                <a href="{{ $service['link'] }}">
                                    {{ $service['linkText'] }}
                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
