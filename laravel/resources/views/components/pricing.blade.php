<section class="price_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>Our Pricing</h2>
        </div>
        <div class="price_container">
            @foreach ($pricingPlans as $period => $price)
                <div class="box">
                    <div class="detail-box">
                        <h2>$ <span>{{ $price }}<sup>*</sup></span></h2>
                        <h6>
                            {{ ucfirst($period) }}
                        </h6>
                        <ul class="price_features">
                            <li>CPU up to {{ $maxSpecs['cpu'] }} cores<sup>*</sup></li>
                            <li>RAM up to {{ $maxSpecs['ram'] }} GB<sup>*</sup></li>
                            <li>Storage up to {{ $maxSpecs['storage'] }} GB<sup>*</sup></li>
                            <li>Network Speed up to {{ $maxSpecs['network_speed'] }}<sup>*</sup></li>
                            <!-- The next items do not depend on the region -->
                            <li>Weekly Backups</li>
                            <li>DDoS Protection</li>
                            <li>Full Root Access</li>
                            <li>24/7/365 Tech Support</li>
                        </ul>
                        <div class="btn-box">
                            <a href="{{ route('server') }}">
                                See Details
                            </a>
                        </div>
                        <p class="small-text">*Pricing and specs depend on the region</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
