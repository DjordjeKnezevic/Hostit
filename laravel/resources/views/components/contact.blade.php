<section class="contact_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>Get In Touch</h2>
        </div>
        <div class="row">
            <div class="col-md-8 col-lg-6 mx-auto">
                <div class="form_container">
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Your Name" name="name" required />
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Your Email" name="email"
                                required />
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="message" placeholder="Message" required></textarea>
                        </div>
                        <div class="btn_box">
                            <button type="submit" class="btn btn-primary">SEND</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
