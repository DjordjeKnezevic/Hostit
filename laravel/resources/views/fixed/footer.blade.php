<div class="footer-page">
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
                            @foreach ($navLinks as $link)
                                <a class="{{ Route::currentRouteName() == $link['route'] ? 'active' : '' }}"
                                    href="{{ route($link['route']) }}">
                                    @if (!empty($link['icon']))
                                        <img src="{{ Storage::url($link['icon']) }}" alt="">
                                    @endif
                                    {{ $link['name'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-0">
                    <h4>Subscribe to our mailing list</h4>
                    <form action="/subscribe" method="POST">
                        @csrf
                        <input type="text" name="email" placeholder="Enter email" />
                        <button type="submit">Subscribe</button>
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
</div>
