<!-- START SECTION SUBSCRIBE NEWSLETTER -->
<div class="section bg_default {{ Request::is('/') ? 'mt-5' : '' }} small_pt small_pb">
	<div class="custom-container">	
    	<div class="row align-items-center">	
            <div class="col-md-6">
            	<div class="newsletter_text text_white">
                    <h3>Join Our Newsletter Now</h3>
                    <p> Register now to get updates on promotions. </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="newsletter_form2 rounded_input">
                    <form id="newsletter-form" method="POST" action="{{ route('post.newsletter') }}">
                        <input type="email" required="" name="email" class="form-control" placeholder="Enter Email Address">
                        <button type="submit" id="newsletter_submit" style="display: none;" class="btn btn-dark btn-radius">Subscribe</button>
                        <button type="button" class="btn btn-dark btn-radius" disabled id="newsletter_submitting">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...    
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- START SECTION SUBSCRIBE NEWSLETTER -->