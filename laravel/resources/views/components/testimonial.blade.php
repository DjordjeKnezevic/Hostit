<div class="carousel-item {{ $active ? 'active' : '' }}">
    <div class="container">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="box">
                    <div class="img-box">
                        <img src="{{ Storage::url($testimonial->image) }}" alt="">
                    </div>
                    <div class="detail-box">
                        <div class="client_info">
                            <div class="client_name">
                                <h5>{{ $testimonial->name }}</h5>
                                <h6>{{ $testimonial->type }}</h6>
                            </div>
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                        <p>{{ $testimonial->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
