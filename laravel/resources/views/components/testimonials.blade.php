<section class="client_section">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>Testimonial</h2>
            <p>
                Discover why our customers trust us to power their websites, applications, and critical infrastructure.
                Read firsthand accounts of our unmatched performance and support.
            </p>
        </div>
    </div>
    <div class="container px-0">
        <div id="customCarousel2" class="carousel  slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach ($testimonials as $index => $testimonial)
                    <x-testimonial :testimonial="$testimonial" :active="$index === 0"></x-testimonial>
                @endforeach
            </div>
            <div class="carousel_btn-box">
                <a class="carousel-control-prev" href="#customCarousel2" role="button" data-slide="prev">
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#customCarousel2" role="button" data-slide="next">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
</section>
