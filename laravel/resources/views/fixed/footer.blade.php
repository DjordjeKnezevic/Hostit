<section class="info_section layout_padding2">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="info_contact">
                    <h4>
                        Address
                    </h4>
                    <div class="contact_link_box">
                        <a href="">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <span>
                                Location
                            </span>
                        </a>
                        <a href="">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <span>
                                Call +01 1234567890
                            </span>
                        </a>
                        <a href="">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <span>
                                demo@gmail.com
                            </span>
                        </a>
                    </div>
                </div>
                <div class="info_social">
                    <a href="">
                        <i class="fa fa-facebook" aria-hidden="true"></i>
                    </a>
                    <a href="">
                        <i class="fa fa-twitter" aria-hidden="true"></i>
                    </a>
                    <a href="">
                        <i class="fa fa-linkedin" aria-hidden="true"></i>
                    </a>
                    <a href="">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info_link_box">
                    <h4>Links</h4>
                    <div class="info_links">
                        <a class="{{ Route::currentRouteName() == 'index' ? 'active' : '' }}"
                            href="{{ route('index') }}">
                            <img src="{{ Storage::url('img/nav-bullet.png') }}" alt=""> Home
                        </a>
                        <a class="{{ Route::currentRouteName() == 'about' ? 'active' : '' }}"
                            href="{{ route('about') }}">
                            <img src="{{ Storage::url('img/nav-bullet.png') }}" alt=""> About
                        </a>
                        <a class="{{ Route::currentRouteName() == 'server' ? 'active' : '' }}"
                            href="{{ route('server') }}">
                            <img src="{{ Storage::url('img/nav-bullet.png') }}" alt=""> Servers
                        </a>
                        <a class="{{ Route::currentRouteName() == 'price' ? 'active' : '' }}"
                            href="{{ route('price') }}">
                            <img src="{{ Storage::url('img/nav-bullet.png') }}" alt=""> Pricing
                        </a>
                        <a class="{{ Route::currentRouteName() == 'contact' ? 'active' : '' }}"
                            href="{{ route('contact') }}">
                            <img src="{{ Storage::url('img/nav-bullet.png') }}" alt=""> Contact Us
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-0">
                <h4>
                    Subscribe
                </h4>
                <form action="#">
                    <input type="text" placeholder="Enter email" />
                    <button type="submit">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<footer class="footer_section">
    <div class="container">
        <p>
            &copy; <span id="displayYear"></span> All Rights Reserved
        </p>
    </div>
</footer>
