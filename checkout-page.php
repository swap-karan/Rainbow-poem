@include('layout.header')
<div id="get-in-touch">
    <section class="section-1">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 p-0">
                    <img class="img-fluid" src="{{url('/')}}/public/images/contact_us.jpg">
                </div>
                <div class="col-md-6 d-flex align-items-center pt-0 form_right">
                    <div class="position-relative">
                        <h2 class="heading">Let's Chat</h2>
                        <h5>You can also shoot us an email at <a href="mailto:help@anselandivy.com">help@anselandivy.com</a>  or check out our <a href="{{url('/')}}/help">Help Center.</a> </h5>
                        <form method="post" name="contact" class="contact">
                            <div class="form-group">
                                <input type="text" class="form-control" id="contact-name" aria-describedby="emailHelp" placeholder="Name*">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" id="contact-email" aria-describedby="emailHelp" placeholder="Email*">
                            </div>
                            <div class="w-100 form-group">
                                <select class="form-control" name="contact-subject" id="contact-subject">
                                    <option value="" selected="true" disabled="disabled">Select</option>
                                    <option value="billing">Billing</option>
                                    <option value="plantcare">Plant Care</option>
                                    <option value="press">Press</option>
                                    <option value="general-inquiry">General Inquiry</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="contact-message" placeholder="Message*" rows="4"></textarea>
                            </div>
                            <button type="submit" class="btn btn-ansel mt-3 contact-submit">SUBMIT</button>
                        </form>
                        <ul class="social mt-5 d-none">
                            <li>
                                <span>GET SOCIAL</span>
                            </li>
                            <li>
                                <i class="fab fa-twitter"></i>
                            </li>
                            <li>
                                <i class="fab fa-facebook-f"></i>
                            </li>
                            <li>
                                <i class="fab fa-pinterest-p"></i>
                            </li>
                            <li>
                                <i class="fab fa-instagram"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('layout.footer')
<script>
    jQuery(document).ready(function($){
        $('form.contact .select .select-styled').text('Subject*');
        $('form.contact ul.select-options li:nth-child(1)').css('display','none');
        $('form.contact input').on('change',function(){
            var regName = /^[a-z ,.'-]+$/i;
            var regEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
            if($('#contact-name').val() && !regName.test($('#contact-name').val())){
                valid = false;
                $('<p class="error" style="color:red;">Please enter valid name</p>').
                insertAfter('form.contact #contact-name');
            } else if($('#contact-email').val() && !regEmail.test($('#contact-email').val())){
                valid = false;
                $('<p class="error" style="color:red;">Please enter valid email address</p>').
                insertAfter('form.contact #contact-email');
            } else {
                valid = true;
                $('form.contact .error').remove();
            }
        });
        $('form.contact input , form.contact textarea').keypress(function(e) {
            var key = e.which;
            if (key == 13) // the enter key code
            {
                $( "form.contact" ).trigger( "submit" );
                return false;
            }
        });
        $(document).on('submit','form.contact',function(e){
            $('form.contact .error').remove();
            $('form.contact .success.mt-3').remove();
            $('form.contact .error.mt-3').remove();
            var valid = true;
            var regName = /^[a-z ,.'-]+$/i;
            var regEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
            var regPhone = /^[+]?[0-9]+$/;
            e.preventDefault();
            if(!$('#contact-name').val()){
                valid = false;
                $('<p class="error" style="color:red;">This is a required field.</p>').
                insertAfter('form.contact #contact-name');
            } else if(!$('#contact-email').val()){
                valid = false;
                $('<p class="error" style="color:red;">This is a required field.</p>').
                insertAfter('form.contact #contact-email');
            } else if($('select-hidden option:selected').val() == ''){
                valid = false;
                $('<p class="error" style="color:red;">This is required field.</p>').
                insertAfter('form.contact .w-100 .select');
            } else if ($('#contact-name').val() && !regName.test($('#contact-name').val())) {
                valid = false;
                $('<p class="error" style="color:red;">Please enter valid name</p>').
                insertAfter('form.contact #contact-name');
            } else if ($('#contact-email').val() && !regEmail.test($('#contact-email').val())) {
                valid = false;
                $('<p class="error" style="color:red;">Please enter valid email address</p>').
                insertAfter('form.contact #contact-email');
            } else if($('select[name=contact-subject] option:selected').val() == '' || $('select[name=contact-subject]').val() == ''){
                valid = false;
                $('<p class="error" style="color:red;">This is a required field.</p>').
                insertAfter('form.contact .w-100 .select');
            } else if($('#contact-message').val() == ''){
                valid = false;
                $('<p class="error" style="color:red;">This is a required field.</p>').
                insertAfter('form.contact #contact-message');
            } else{
                $('form.contact button.contact-submit').addClass('disabled').attr('disabled','disabled');
                valid = true;
                var name = $('#contact-name').val();
                var email = $('#contact-email').val();
                var subject = $('select[name=contact-subject] option:selected').text();
                var message = $('#contact-message').val();
                var data = {'name':name,'email':email,'subject':subject,'message':message};
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '@php echo url('/').'/contactformsubmit'; @endphp',
                    method:'POST',
                    data:data,
                    dataType:'json',
                    success:function(data){
                        $('form.contact button.contact-submit').removeClass('disabled').removeAttr('disabled');
                        if(data.result == 'success'){
                            $("<p class='success mt-3' style='color:#313232'>Thanks for reaching out! We’ll get back to you as soon as possible.</p>").insertAfter('form.contact .contact-submit');
                        } else{
                            $("<p class='error mt-3' style='color:red'>Please try again later.</p>").insertAfter('form.contact .contact-submit');
                        }
                    }
                });
            }
            return valid;
        });
    })
</script>
@include('layout.endfooter')
