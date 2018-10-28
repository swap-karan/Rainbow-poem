@include('layout.include_header')
@php $session = session()->all(); @endphp
@php $url = m2apiendpoint().'customers/me'; @endphp
@php $customerData = loggedinApiCall($url,'get','');@endphp

@php if(isMobile()): @endphp
<!-- Checkout Mobile -->
<?php $previous = "javascript:history.go(-1)";
if(isset($_SERVER['HTTP_REFERER'])) {
    $previous = $_SERVER['HTTP_REFERER'];
} else{
    $previous = url('/');
} ?>
<div class="col-md-12 checkout-mobile">
    <div class="row">
        <div class="checkout-nav fixed-top">
            <div class="row">
                <div class="col-3 text-left">
                    <a href="@php echo $previous; @endphp"><img width="16px" src="{{url('/')}}/public/images/left-arrow.png">
                </div>
                <div class="col-6 text-center">
                    <a href="@php echo url('/'); @endphp"><img width="125px" src="{{url('/')}}/public/images/Ansel & Ivy Logo.png"></a>
                </div>
                <div class="col-3 text-right">
                    {{--<a href="@php //echo url('/'); @endphp"><img width="16px" src="{{url('/')}}/public/images/multiply.png"></a>--}}
                </div>
            </div>
        </div>
        <div id="mobile-step-1" class="col-12" style="display: block;">
            <div class="row">
                <div class="col-12 checkout-mob-items">
                    <h5>Items</h5>
                    @php if(array_key_exists("quote_id",$session)): @endphp
                    @php if($session['quote_id'] != '' ): @endphp
                    @php $quote_id = $session['quote_id']; @endphp
                    @php
                        if(isset($session['customer_token']))
                        {
                            $url = createquote().''.$quote_id;
                        }
                        else
                        {
                            $url = createquoteguest().''.$quote_id;
                        }
                    @endphp
                    @php $cartdata = m2ApiCall($url,'get',''); @endphp
                    <span data-toggle="modal" data-target="#cart_modal" class="view view_cart_modal">View</span>
                    <ul class="list-inline cart-mob-items">
                        @foreach($cartdata['items'] as $data)
                            @php if($data['sku'] != 'dummy-product'): @endphp
                            <li class="list-inline-item">
                                @php $media_images = get_product_image($data['sku']) @endphp
                                @php $url = catalog_url() @endphp
                                @if(count($media_images) > 0)
                                    @php $i = 0; @endphp
                                    @foreach($media_images as $img)
                                        @php if($i == 0): @endphp
                                        <img width="75px" height="75px" src="{{$url}}{{$img['file']}}">
                                        @php endif; @endphp
                                        @php $i++; @endphp
                                    @endforeach
                                @endif
                                <p>@php echo $data['name'] @endphp</p>
                            </li>
                            @php endif; @endphp
                        @endforeach
                    </ul>
                    @php endif @endphp
                    @php endif @endphp

                    <div class="promo_code">
                        <from method="post" id="apply_coupon" name="apply_coupon">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group m-0">
                                        <input type="text" class="form-control" id="coupon_code" placeholder="Code / Gift Card">
                                    </div>
                                </div>
                                <div class="col-6 pl-0">
                                    <button type="button" class="btn btn-ansel btn-block apply">APPLY</button>
                                    <button type="button" class="btn btn-ansel btn-block cancel_coupon" style="display:none;">
                                        CANCEL
                                    </button>
                                </div>
                            </div>
                        </from>
                    </div>
                </div>

                @php if(array_key_exists("quote_id",$session)): @endphp
                @php if($session['quote_id'] != '' ): @endphp
                @php $quote_id = $session['quote_id']; @endphp
                @php
                    if(isset($session['customer_token']))
                    {
                        $url = createquote().''.$quote_id.'/totals';
                    }
                    else
                    {
                        $url = createquoteguest().''.$quote_id.'/totals';
                    }
                @endphp
                @php
                    $totals = m2ApiCall($url,'get','');
                @endphp
                @php if(!array_key_exists("message",$totals)): @endphp
                <div class="col-md-12 cart-total-mob">
                    <div class="row">
                        <div class="col-6 text-left">
                            <h6>Subtotal</h6>
                        </div>
                        <div class="col-6 text-right">
                            <h6>${{number_format($totals['total_segments'][0]['value'],2)}}</h6>
                        </div>
                        <div class="col-6 text-left" @php if($totals['shipping_amount'] == 0): @endphp style="display: none;" @php endif; @endphp>
                            <h6>Shipping</h6>
                        </div>
                        <div class="col-6 text-right" @php if($totals['shipping_amount'] == 0): @endphp style="display: none;" @php endif; @endphp>
                            <h6>${{number_format($totals['shipping_amount'][0]['value'],2)}}</h6>
                        </div>
                        <div class="col-6 text-left discount-text-mob" @php if($totals['discount_amount'] == 0): @endphp style="display: none;" @php endif; @endphp>
                            <h6>Discount</h6>
                        </div>
                        <div class="col-6 text-right discount-amount-mob" @php if($totals['discount_amount'] == 0): @endphp style="display: none;" @php endif; @endphp>
                            <h6>${{number_format($totals['discount_amount'][0]['value'],2)}}</h6>
                        </div>
                        <div class="div-divider"></div>
                        <div class="col-6 text-left">
                            <h2>Total:</h2>
                        </div>
                        <div class="col-6 text-right">
                            <h2>${{number_format($totals['total_segments'][0]['value'],2)}}</h2>
                        </div>
                    </div>
                    <button type="button" class="btn btn-ansel btn-block">CHECKOUT</button>
                </div>
                @php endif; @endphp
                @php endif; @endphp
                @php endif; @endphp
            </div>
        </div>
        @php if(!isset($_COOKIE["customer_token"])): @endphp
        <div class="col-md-12 add-recipient" id="email-forms-mob" style="display:none;">
            <!-- > Checkout Guest Email Form <-->
            <form class="guestEmailform" name="guestEmailform" method="post">
                <h4>All set? Please sign in.</h4>
                <a href="@php echo url('/'); @endphp/facebook-login"><button type="button" class="btn btn-fb btn-block">CONTINUE WITH FACEBOOK</button></a>
                <h4 class="or"><span>OR</span></h4>
                <div class="form-group">
                    <input class="form-control" id="guestEmail" aria-describedby="emailHelp" placeholder="Email Address" type="email">
                </div>
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-ansel btn-block mt-3 continue-guest">CONTINUE</button>
                    </div>
                </div>
            </form>

            <!-- > Checkout Guest Registration Form <-->
            <form action="@php echo url('/'); @endphp/create-customer" name="create-acc" method="post" class="create-acc" style="display: none;">
                <h4>It looks like you're new here. Please create an account.</h4>
                <a href="@php echo url('/'); @endphp/facebook-login"><button type="button" class="btn btn-fb btn-block">Sign up With Facebook</button></a>
                <h4 class="or"><span>OR</span></h4>
                <div class="form-group">
                    <input class="form-control" required name="name" id="fullname" aria-describedby="emailHelp" placeholder="Full Name" type="text">
                </div>
                <div class="form-group">
                    <input class="form-control" required name="email" id="reg_email" aria-describedby="emailHelp" placeholder="Email" type="email">
                </div>
                <div class="form-group">
                    <input class="form-control" required name="password" id="password" placeholder="Password" type="password">
                </div>
                <div class="row">
                    <div class="col">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" reg-btn class="btn btn-ansel btn-block mt-3 create-account">CREATE AN ACCOUNT</button>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-ansel-secondary btn-block mt-3 guest-checkout">CHECKOUT AS GUEST</button>
                    </div>
                </div>
                <h6 class="text-left mt-4 mb-5">
                    <a class="ul" id="login-with-existing-account">Log In with an existing account</a>
                </h6>
            </form>

            <form name="existing_customer" method="post" class="existing_customer" style="display: none;">
                <h4 class="heading-secondary">Good to see you again. Please log in.</h4>
                <div class="form-group">
                    <input type="email" class="form-control" id="existingEmail" aria-describedby="emailHelp" placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="existingPass" aria-describedby="emailHelp" placeholder="Enter your password">
                </div>
                <h6 class="text-left mt-4">
                    <a href="#" data-toggle="modal" data-target="#forgot-password">Forgot your
                        password?</a>
                </h6>
                {{--<h6 class="text-left mt-4 create_account">
                    <a>Create new account</a>
                </h6>--}}
                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-ansel btn-block mt-3">CONTINUE</button>
                    </div>
                </div>
            </form>
        </div>
        @php else: @endphp

        @php endif; @endphp
        @php if(isset($_COOKIE["customer_token"])): @endphp
        @php if(!key_exists('message',$customerData)): @endphp
        @php $display = "display: block;" @endphp
        @php else: @endphp
        @php $display = "display: none;" @endphp
        @php endif; @endphp
        @php else: @endphp
        @php $display = "display: none;" @endphp
        @php endif; @endphp
        <div id="mobile-step-2" class="col-12" style="display:none;">
            <div class="col-12 checkout-mob-items">
                <h5>Items</h5>
                @php if(array_key_exists("quote_id",$session)): @endphp
                @php if($session['quote_id'] != '' ): @endphp
                @php $quote_id = $session['quote_id']; @endphp
                @php
                    if(isset($session['customer_token']))
                    {
                        $url = createquote().''.$quote_id;
                    }
                    else
                    {
                        $url = createquoteguest().''.$quote_id;
                    }
                @endphp
                @php $cartdata = m2ApiCall($url,'get',''); @endphp
                <span data-toggle="modal" data-target="#cart_modal" class="view view_cart_modal">View</span>
                <ul class="list-inline cart-mob-items">
                    @foreach($cartdata['items'] as $data)
                        @php if($data['sku'] != 'dummy-product'): @endphp
                        <li class="list-inline-item">
                            @php $media_images = get_product_image($data['sku']) @endphp
                            @php $url = catalog_url() @endphp
                            @if(count($media_images) > 0)
                                @php $i = 0; @endphp
                                @foreach($media_images as $img)
                                    @php if($i == 0): @endphp
                                    <img width="75px" height="75px" src="{{$url}}{{$img['file']}}">
                                    @php endif; @endphp
                                    @php $i++; @endphp
                                @endforeach
                            @endif
                            <p>@php echo $data['name'] @endphp</p>
                        </li>
                        @php endif; @endphp
                    @endforeach
                </ul>
                @php endif @endphp
                @php endif @endphp

                <div class="promo_code">
                    <from method="post" id="apply_coupon" name="apply_coupon">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group m-0">
                                    <input type="text" class="form-control cc_text_step2" id="coupon_code" placeholder="Code / Gift Card">
                                </div>
                            </div>
                            <div class="col-6 pl-0">
                                <button type="button" class="btn btn-ansel btn-block apply">APPLY</button>
                                <button type="button" class="btn btn-ansel btn-block cancel_coupon" style="display:none;">
                                    CANCEL
                                </button>
                            </div>
                        </div>
                    </from>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-12 checkout-cards">
                    <h6>Email Address</h6>
                    @php if(isset($_COOKIE["customer_token"])): @endphp
                    @php if(!key_exists('message',$customerData)): @endphp
                    <p class="m-0" id="shippingEmail-text">@php echo $customerData['email']; @endphp</p>
                    @php else: @endphp
                    <form class="mobile_guestEmailform" name="mobile_guestEmailform" method="post">
                        <div class="form-group">
                            <input type="email" class="form-control" id="guestEmail" placeholder="your@example.com">
                        </div>
                        {{--<div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-ansel btn-block mt-3 continue-guest">CONTINUE
                                </button>
                            </div>
                        </div>--}}
                    </form>
                    <p class="m-0" id="shippingEmail-text"></p>
                    <span class="edit-email"><a href="javascript:void(0);">Edit</a></span>
                    @php endif; @endphp
                    @php else: @endphp
                    <form class="mobile_guestEmailform" name="mobile_guestEmailform" method="post">
                        <div class="form-group">
                            <input type="email" class="form-control" id="guestEmail" placeholder="your@example.com">
                        </div>
                        {{--<div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-ansel btn-block mt-3 continue-guest">CONTINUE
                                </button>
                            </div>
                        </div>--}}
                    </form>
                    <p class="m-0" id="shippingEmail-text"></p>
                    <span class="edit-email"><a href="javascript:void(0);">Edit</a></span>
                    @php endif; @endphp
                </div>

                <div class="col-12 checkout-cards" id="address-details">
                    <h6>Customer Info</h6>
                    <div id="add-address-modal">
                        <div class="form-group">
                            <input type="text" class="form-control" id="firstname" aria-describedby="emailHelp" placeholder="First Name*">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="lastname" aria-describedby="emailHelp" placeholder="Last Name*">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="addressline1" aria-describedby="emailHelp" placeholder="Address*">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="addressline2" aria-describedby="emailHelp" placeholder="Unit #">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="city" aria-describedby="emailHelp" placeholder="City*">
                        </div>
                        <div class="form-group">
                            @include('layout.statedropdown')
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="country" aria-describedby="emailHelp" placeholder="Country*" value="United States" disabled readonly>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="postcode" aria-describedby="emailHelp" placeholder="Postal Code*">
                        </div>
                        <div class="form-group">
                            <input type="tel" class="form-control" id="telephone" aria-describedby="emailHelp" placeholder="Telephone*">
                        </div>
                        @php if(isset($_COOKIE["customer_token"])): @endphp
                        @php if(!key_exists('message',$customerData)): @endphp
                        <label class="custom-checkbox">Save this information for next time
                            <input type="checkbox" checked="checked" name="saveaddress" id="saveaddress">
                            <span class="checkmark"></span>
                        </label>
                        @php else: @endphp
                        @php endif; @endphp
                        @php endif; @endphp
                    </div>
                    {{--<button type="button" class="btn btn-ansel btn-block add_address_reqest">Add Shipping Address</button>--}}
                    <label class="custom-checkbox mb-3">This is a Gift
                        <input type="checkbox" name="have_recipient">
                        <span class="checkmark"></span>
                    </label>
                    <div id="add-recipient-modal" style="display:none;">
                        <div class="form-group">
                            <input type="text" class="form-control" id="recipient-name" aria-describedby="emailHelp" placeholder="Name Of Recipient*">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="recipient-email" aria-describedby="emailHelp" placeholder="Email Address Of Recipient*">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="recipient-message" placeholder="Personal Message*" rows="3"></textarea>
                        </div>
                        {{--<button type="button" class="btn btn-ansel btn-block mt-4">Continue</button>--}}
                    </div>
                    <button type="button" class="btn btn-ansel btn-block mt-4">Continue</button>
                    {{--<button type="button" class="btn btn-ansel btn-block add_recipient_info">Add Recipient info</button>--}}
                </div>

                <div class="col-12 checkout-cards">
                    <div class="disabled_div" style="width:  100%;height:  100%;position: absolute;z-index: 2;background-color: #fff;opacity: 0.6;"></div>
                    <h6>Shipping Method</h6>
                    <div id="shipping_append">
                        <div class="shipping-mob">
                            <label class="custom-radio">Standard (2-5 day) - Fixed
                                <input type="radio" checked="checked" name="radio">
                                <span class="checkmark"></span>
                            </label>
                            <p>$7.99</p>
                        </div>
                        <div class="shipping-mob border-0">
                            <label class="custom-radio">Expedited Shipping (1-3 day) - Fixed
                                <input type="radio" name="radio">
                                <span class="checkmark"></span>
                            </label>
                            <p>$24.99</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 checkout-cards payment_method_mobile">
                    {{--<h6>Payment Method</h6>
                    <button type="submit" onclick="location.href = 'add-card.html'" class="btn btn-ansel btn-block">CREDIT /
                        DEBIT CARD
                    </button>
                    <img class="mt-3" width="150px" src="{{url('/')}}/public/images/cards.jpg">
                    <div class="div-divider"></div>--}}
                    <div class="disabled_div" style="width:  100%;height:  100%;position: absolute;z-index: 2;background-color: #fff;opacity: 0.6;"></div>
                    <h6>Payment Method</h6>
                    <div id="payment_method_mob">
                        <div class="div-divider"></div>
                    </div>
                    <form method="post" name="cc-method" class="cc-method-mobile">
                        <div class="credit_card_info w-100">
                            <div class="row">
                                <div class="col text-center">
                                    <img width="150px" src="{{url('/')}}/public/images/cards.jpg">
                                </div>
                            </div>
                        </div>
                        <div class="credit_card_form">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="tel" class="form-control" id="cc-number"
                                           placeholder="Card Number">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="cc-nameOncard"
                                                   placeholder="Name on Card">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <input type="tel" id="cc-exp-month" name="cc-exp-month" value="" class="form-control" placeholder="MM" maxlength="2"/>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <input type="tel" id="cc-exp-year" name="cc-exp-year" value="" class="form-control" placeholder="YY" maxlength="2"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="cc-cvv" maxlength="4"
                                                   aria-describedby="emailHelp" placeholder="CVV">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div id="billing_method_mob">
                        <h6>Billing Address</h6>
                        <div class="billing-mob">
                            <label class="custom-radio">Same as shipping address
                                <input type="radio" checked="checked" name="radio">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="billing-mob border-0">
                            <label class="custom-radio">Use a different billing address
                                <input type="radio" name="radio">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div id="add-billing-address-modal" style="display: none;">
                            <h4>Add Billing Address</h4>
                            <div class="form-group">
                                <input type="text" class="form-control" id="bill-firstname" aria-describedby="emailHelp" placeholder="First Name*">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="bill-lastname" aria-describedby="emailHelp" placeholder="Last Name*">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="bill-addressline1" aria-describedby="emailHelp" placeholder="Address*">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="bill-addressline2" aria-describedby="emailHelp" placeholder="Unit #">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="bill-city" aria-describedby="emailHelp" placeholder="City*">
                            </div>
                            <div class="form-group">
                                @include('layout.statedropdown')
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="bill-country" aria-describedby="emailHelp" placeholder="Country*" value="United States" disabled readonly>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="bill-postcode" aria-describedby="emailHelp" placeholder="Postal Code*">
                            </div>
                            <div class="form-group">
                                <input type="tel" class="form-control" id="bill-telephone" aria-describedby="emailHelp" placeholder="Telephone*">
                            </div>
                            <button type="button" class="btn btn-ansel btn-block mt-4">Continue</button>
                        </div>
                    </div>
                </div>
            </div>
            @php if(array_key_exists("quote_id",$session)): @endphp
            @php if($session['quote_id'] != '' ): @endphp
            @php $quote_id = $session['quote_id']; @endphp
            @php
                if(isset($session['customer_token']))
                {
                    $url = createquote().''.$quote_id.'/totals';
                }
                else
                {
                    $url = createquoteguest().''.$quote_id.'/totals';
                }
            @endphp
            @php
                $totals = m2ApiCall($url,'get','');
            @endphp
            @php if(!array_key_exists("message",$totals)): @endphp
            <div class="col-md-12 cart-total-mob">
                <div class="row">
                    <div class="col-6 text-left">
                        <h6>Subtotal</h6>
                    </div>
                    <div class="col-6 text-right">
                        <h6>${{number_format($totals['total_segments'][0]['value'],2)}}</h6>
                    </div>
                    <div class="col-6 text-left" @php if($totals['shipping_amount'] == 0): @endphp style="display: none;" @php endif; @endphp>
                        <h6>Shipping</h6>
                    </div>
                    <div class="col-6 text-right" @php if($totals['shipping_amount'] == 0): @endphp style="display: none;" @php endif; @endphp>
                        <h6>${{number_format($totals['shipping_amount'][0]['value'],2)}}</h6>
                    </div>
                    <div class="col-6 text-left discount-text-mob" @php if($totals['discount_amount'] == 0): @endphp style="display: none;" @php endif; @endphp>
                        <h6>Discount</h6>
                    </div>
                    <div class="col-6 text-right discount-amount-mob" @php if($totals['discount_amount'] == 0): @endphp style="display: none;" @php endif; @endphp>
                        <h6>${{number_format($totals['discount_amount'][0]['value'],2)}}</h6>
                    </div>
                    <div class="div-divider"></div>
                    <div class="col-6 text-left">
                        <h2>Total:</h2>
                    </div>
                    <div class="col-6 text-right">
                        <h2>${{number_format($totals['total_segments'][0]['value'],2)}}</h2>
                    </div>
                </div>
                <button type="submit" class="btn btn-ansel btn-block disabled btn-placeorder-mob" disabled="disabled">COMPLETE ORDER</button>
            </div>
            @php endif; @endphp
            @php endif; @endphp
            @php endif; @endphp
        </div>
    </div>
</div>

@php else: @endphp

<div class="col-md-12 checkout checkout-web">
    <div class="row">
        <div class="col-md-7 l-s">
            <div class="left-sec" id="steps-of-checkout">
                <div class="main_top">
                    <a href="{{url('/')}}"><img width="200px" src="{{url('/')}}/public/images/Ansel & Ivy Logo.png"></a>
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link @php if(isset($_COOKIE["customer_token"])): if(!key_exists('message',$customerData)): @endphp disabled @php else: @endphp active @php endif; @endphp @php else: @endphp active @php endif; @endphp"
                               id="pills-home-tab" data-toggle="pill" href="#pills-sign-in" role="tab"
                               aria-controls="pills-home"
                               aria-selected="true">01 SIGN IN</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @php if(isset($_COOKIE["customer_token"])): if(!key_exists('message',$customerData)): @endphp active @php else: @endphp disabled @php endif; @endphp @php else: @endphp disabled @php endif; @endphp"
                               id="pills-profile-tab" data-toggle="pill" href="#pills-customer-info" role="tab"
                               aria-controls="pills-profile"
                               aria-selected="false">02 CUSTOMER INFO</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" id="pills-contact-tab" data-toggle="pill"
                               href="#pills-shipping-method" role="tab" aria-controls="pills-contact"
                               aria-selected="false">03 SHIPPING METHOD</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" id="pills-payment-tab" data-toggle="pill"
                               href="#pills-payment-method" role="tab" aria-controls="pills-contact"
                               aria-selected="false">04 PAYMENT METHOD</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    @php if(!isset($_COOKIE["customer_token"])): @endphp
                    <div class="tab-pane fade show active" id="pills-sign-in" role="tabpanel"
                         aria-labelledby="pills-home-tab">
                        <form class="guestEmailform" name="guestEmailform" method="post">
                            <h5 class="heading-secondary">All set? Please sign in.</h5>
                            <a href="{{url('/')}}/facebook-login">
                                <button class="btn btn-fb btn-block" type="button">Continue With Facebook</button>
                            </a>
                            <h4 class="or">
                                <span>
                                    OR
                                </span>
                            </h4>
                            <div class="form-group">
                                <input type="email" class="form-control" id="guestEmail" placeholder="your@example.com">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-ansel btn-block mt-3 continue-guest">CONTINUE
                                    </button>
                                </div>
                            </div>
                        </form>
                        <form action="{{url('/')}}/create-customer" name="create-acc" method="post" class="create-acc"
                              style="display:none;">
                            <h5 class="heading-secondary">Looks like you're new. Please create an account.</h5>
                            <a href="{{url('/')}}/facebook-login">
                                <button class="btn btn-fb btn-block">Sign up With Facebook</button>
                            </a>
                            <h4 class="or">
                                        <span>
                                            OR
                                        </span>
                            </h4>
                            <div class="form-group">
                                <input type="text" required name="name" class="form-control" id="fullname"
                                       aria-describedby="emailHelp" placeholder="Full Name">
                            </div>
                            <div class="form-group">
                                <input type="email" required name="email" class="form-control" id="reg_email"
                                       aria-describedby="emailHelp" placeholder="Email">
                                <p id="email-error" style="display:none"></p>
                            </div>
                            <div class="form-group">
                                <input type="password" required name="password" class="form-control" id="password"
                                       placeholder="Password">
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" id="reg-btn"
                                            class="btn btn-ansel btn-block mt-3 create-account">CREATE AN ACCOUNT
                                    </button>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-ansel-secondary btn-block mt-3 guest-checkout">
                                        CHECKOUT AS GUEST
                                    </button>
                                </div>
                            </div>
                            <h6 class="text-left mt-4 exist_customer">
                                <a>Log In with an existing account</a>
                            </h6>
                        </form>
                        <form name="existing_customer" method="post" class="existing_customer" style="display: none;">
                            <h5 class="heading-secondary">Good to see you again. Please log in.</h5>
                            <div class="form-group">
                                <input type="email" class="form-control" id="existingEmail" aria-describedby="emailHelp"
                                       placeholder="Enter your email">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="existingPass"
                                       aria-describedby="emailHelp" placeholder="Enter your password">
                            </div>
                            <h6 class="text-left mt-4">
                                <a href="#" data-toggle="modal" data-target="#forgot-password">Forgot your
                                    password?</a>
                            </h6>
                            {{--<h6 class="text-left mt-4 create_account">
                                <a>Create new account</a>
                            </h6>--}}
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-ansel btn-block mt-3">CONTINUE</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @php else: @endphp

                    @php endif; @endphp
                    <div class="tab-pane fade" id="pills-customer-info" role="tabpanel"
                         aria-labelledby="pills-profile-tab">
                        <form name="shippingmethodForm" class="shippingmethodForm" method="post">
                            <h5 class="heading-secondary">
                                <b>Email Address</b>
                            </h5>
                            @php if(isset($_COOKIE["customer_token"])): @endphp
                            @php if(!key_exists('message',$customerData)): @endphp
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" style="position: absolute;top:-500px;">
                                        <input class="form-control" id="shippingEmail" aria-describedby="emailHelp"
                                               placeholder="Email" type="email">
                                    </div>
                                </div>
                            </div>
                            <p class="mb-5" id="shippingEmail-text">@php echo($customerData['email']); @endphp</p>
                            @php else: @endphp
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" style="position: absolute;top:-500px;">
                                        <input class="form-control" id="shippingEmail" aria-describedby="emailHelp"
                                               placeholder="Email" type="email">
                                    </div>
                                </div>
                            </div>
                            <p class="mb-5" id="shippingEmail-text"></p>
                            @php endif; @endphp
                            @php else: @endphp
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" style="position: absolute;top:-500px;">
                                        <input class="form-control" id="shippingEmail" aria-describedby="emailHelp"
                                               placeholder="Email" type="email">
                                    </div>
                                </div>
                            </div>
                            <p class="mb-5" id="shippingEmail-text"></p>
                            @php endif; @endphp
                            @php if(isset($_COOKIE["customer_token"])): @endphp
                            @php if(!key_exists('message',$customerData)): @endphp
                            {{--<p class="mb-5" id="shippingEmail-text"></p>--}}
                            @php endif; @endphp
                            @php endif; @endphp
                            <h5 class="heading-secondary">
                                <b>Shipping Address</b>
                            </h5>
                            <div class="col-md-12 p-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="shippingFirstname"
                                                   placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pl-0">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="shippingLastname"
                                                   placeholder="Last Name">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="shippingAddress"
                                                   placeholder="Address">
                                        </div>
                                    </div>
                                    <div class="col-md-4 pl-0">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="shippinngAddressline2"
                                                   placeholder="Unit #">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="shippingCity"
                                                   placeholder="City">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pl-0">
                                        <div class="form-group state-select">
                                            @include('layout.statedropdown')
                                            {{--<input type="text" class="form-control" id="shippingState"
                                                   placeholder="State">--}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{--@include('layout.countrydropdown')--}}
                                            <input type="text" class="form-control" id="country_id" value="United States" readonly placeholder="country_id">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pl-0">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="shippingPostCode"
                                                   placeholder="Postal Code">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="shippingTelephone"
                                                   placeholder="Telephone">
                                        </div>
                                    </div>
                                    <div class="col-md-12 save_info">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" checked="checked" id="samebilling">
                                            <label class="custom-control-label" for="samebilling">Shipping and Billing
                                                address are same.</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 gift mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="giftcheck">
                                            <label class="custom-control-label" for="giftcheck">This is a Gift</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="gift_form" style="display: none;">

                                        <div class="form-group">
                                            <input type="text" class="form-control" id="shippingReceiverName"
                                                   placeholder="Name of Recipient">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="shippingReceiverEmail"
                                                   placeholder="Email Address of Recipient">
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" id="shippingReceiverMessage"
                                                      placeholder="Personal Message" rows="3"></textarea>
                                        </div>

                                    </div>
                                    <div class="col-md-12 return">
                                        <div class="row">
                                            <div class="col">
                                                <button type="button"
                                                        class="btn btn-ansel float-right continue-to-shipping">CONTINUE
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="pills-shipping-method" role="tabpanel"
                         aria-labelledby="pills-contact-tab">
                        <div class="col-md-12 shipping_address">
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <h6>
                                        <b>Shipping Address</b>
                                    </h6>
                                </div>
                                <div class="col-md-6 ">
                                    <h6 class="text-muted shipping-address-h6">House # 308, Aurangzeb Road, E-7,
                                        Islamabad, Islamabad 44000, Pakistan</h6>
                                </div>
                                <div class="col-md-3 text-right">
                                    <h6>
                                        <span class="ul edit-address" title="Edit Shipping Address">Edit</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 shipping_method">
                            <div class="row">
                                <h5 class="heading-secondary">Shipping Method</h5>
                                <table class="table">
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12 return">
                            <div class="row">
                                <div class="col edit-address">
                                    <h6>
                                        <i class="fas fa-chevron-left mr-2"></i> Return to customer information
                                    </h6>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-ansel float-right continue-to-payment">
                                        CONTINUE
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-payment-method" role="tabpanel"
                         aria-labelledby="pills-contact-tab">
                        <div class="col-md-12 shipping_address">
                            <div class="row">
                                <table class="table m-0">
                                    <tbody>
                                    <tr>
                                        <td class="border-top-0 w-25">
                                            <b>Shipping Address</b>
                                        </td>
                                        <td class="text-mutued border-top-0 shipping-address-h6">House # 308, Aurangzeb
                                            Road, E-7, Islamabad, Islamabad 44000, Pakistan
                                        </td>
                                        <td class="border-top-0 w-25 text-right">
                                            <span class="ul edit-address" title="Edit Shipping Address">Edit</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-25">
                                            <b>Shipping Method</b>
                                        </td>
                                        <td class="text-mutued">International Free Shipping</td>
                                        <td class="w-25 text-right">
                                            <span class="ul edit-shipping" title="Edit Shipping Method">Edit</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12 payment_method">
                            <div class="row">
                                <div class="col-md-12 p-0">
                                    <h5 class="heading-secondary">Payment Method</h5>
                                </div>
                                <div class="col-md-12 p-0">
                                    <p class="text-mutued">All transactions are secure and encrypted.</p>
                                </div>
                                <table class="table payment-table-hidden">
                                    <tbody></tbody>
                                </table>
                                <form method="post" name="cc-method" class="cc-method" {{--style="display:none;"--}}>
                                    <div class="credit_card_info w-100">
                                        <div class="row">
                                            <div class="col">
                                                <h6 class="text-mutued m-0">Credit Card Information</h6>
                                            </div>
                                            <div class="col text-right">
                                                <img width="150px" src="{{url('/')}}/public/images/cards.jpg">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="credit_card_form">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="cc-number"
                                                       placeholder="Card Number">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="cc-nameOncard"
                                                               placeholder="Name on Card">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 pl-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text" placeholder="MM" id="cc-exp-month" name="cc-exp-month" value="" class="form-control" maxlength="2"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text" placeholder="YY" id="cc-exp-year" name="cc-exp-year" value="" class="form-control" maxlength="2"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 pl-0">
                                                    <div class="form-group">
                                                        <input type="tel" class="form-control" id="cc-cvv" maxlength="4"
                                                               aria-describedby="emailHelp" placeholder="CVV">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-12 billing_address">
                            <div class="row">
                                <h5 class="heading-secondary">Billing Address</h5>
                                <div class="col-md-12 billing_kind">
                                    <div class="row">
                                        <div class="col-md-12 border-bottom kind">
                                            <label class="custom-radio">Same as shipping address
                                                <input type="radio" checked="checked" name="billingstatus" value="same">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-12 kind">
                                            <label class="custom-radio">Use a different billing address
                                                <input type="radio" name="billingstatus" value="different">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <form name="differnt_billing_address" class="differnt_billing_address" method="post">
                                    <div class="col-md-12 differnt_billing_address">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="billingFirstname"
                                                           aria-describedby="emailHelp" placeholder="First Name">
                                                </div>
                                            </div>
                                            <div class="col-md-6 pl-0">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="billingLastname"
                                                           aria-describedby="emailHelp" placeholder="Last Name">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="billingAddress"
                                                           aria-describedby="emailHelp" placeholder="Address">
                                                </div>
                                            </div>
                                            <div class="col-md-4 pl-0">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="billingAddressline2"
                                                           aria-describedby="emailHelp"
                                                           placeholder="Unit #">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="billingCity"
                                                           aria-describedby="emailHelp" placeholder="City">
                                                </div>
                                            </div>
                                            <div class="col-md-6 p1-0">
                                                <div class="form-group">
                                                    @include('layout.statedropdown')
                                                    {{--<input type="text" class="form-control" id="billingState"
                                                           aria-describedby="emailHelp" placeholder="State">--}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="country_id"
                                                           aria-describedby="emailHelp" readonly value="United States">
                                                    {{--@include('layout.countrydropdown')--}}
                                                </div>
                                            </div>
                                            <div class="col-md-6 pl-0">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="billingPostCode"
                                                           aria-describedby="emailHelp" placeholder="Postal Code">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="tel" class="form-control" id="billingTelephone"
                                                           aria-describedby="emailHelp" placeholder="Telephone">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                @php if(isset($_COOKIE["customer_token"])): @endphp
                                @php if(!key_exists('message',$customerData)): @endphp
                                <div class="col-md-12 remember_me">
                                    <div class="row">
                                        {{--<h5 class="heading-secondary">Remember Me</h5>--}}
                                        <div class="col-md-12 save-info-box">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" checked="checked" class="custom-control-input" id="saveaddress">
                                                <label class="custom-control-label text-mutued" for="saveaddress">Save
                                                    my information for faster checkout</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php endif; @endphp
                                @php endif; @endphp
                                <div class="col-md-12 return">
                                    <div class="row">
                                        <div class="col edit-shipping">
                                            <h6>
                                                <i class="fas fa-chevron-left mr-2"></i> Return to shipping method
                                            </h6>
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn btn-ansel float-right placeorder">COMPLETE
                                                ORDER
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="col-md-12 last-sec" style="display: none;">
                    <h6>All rights reserved ANSEL & IVY</h6>
                </div>--}}
            </div>
            <div class="left-sec" id="order-success-step" style="display: none;">
                <div class="order-top-sec text-center">
                    <img width="68px" src="{{ url('/') }}/public/images/success.png">
                    <h1>We're on it!</h1>
                    <h2 id="customer-firstname">Thank you Hiraa for your purchase!</h2>
                    <h5 id="customer-email">A confirmation email has been sent to you@example.com</h5>
                    <h6 id="customer-orderId">Order ID: 1876 </h6>
                </div>
                <div class="order-detail">
                    <p>Please allow 2-4 days for your order to ship. Each plant is handpicked by our team and we like to
                        ensure that we're getting you the fullest and freshest plant possible. We'll be sure to send
                        you an email when your plant ships.
                    </p>
                </div>
                <div class="col-md-12 ci">
                    <div class="row">
                        <h5>Customer Information</h5>
                    </div>
                </div>
                <div class="col-md-12 order-summary">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled" id="order-shipping-address">
                                <li>Shipping Address</li>
                                <li>Hiraa Khan</li>
                                <li>77 Van Ness Ave</li>
                                <li>Apt 703</li>
                                <li>San Francisco CA 9412-604</li>
                                <li> United States</li>
                                <li> 456382665</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled" id="order-billing-address">
                                <li>Billing Address</li>
                                <li>Hiraa Khan</li>
                                <li>77 Van Ness Ave</li>
                                <li>Apt 703</li>
                                <li>San Francisco CA 9412-604</li>
                                <li> United States</li>
                                <li> 456382665</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled" id="order-shipping-method">
                                <li>Shipping Method</li>
                                <li>USPS - First Class Package Service</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled" id="order-payment-method">
                                <li>Payment Method</li>
                                <li>
                                        <span>
                                            <img src="{{url('/')}}/public/images/Visa_Logo.png" width="30px"> </span>Ending
                                    with 456
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 return">
                    <div class="row">
                        <div class="col">
                            <h6>
                                <a href="{{url('/')}}/help">Need Help?</a>
                                <a href="{{url('/')}}/contact" target="_blank"><span class="ul"> Contact Us</span></a>
                            </h6>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-ansel float-right continue-shopping">CONTINUE
                                SHOPPING
                            </button>
                        </div>
                    </div>
                </div>
                {{--<div class="col-md-12 last-sec">
                    <h6>All rights reserved ANSEL & IVY</h6>
                </div>--}}
            </div>
        </div>
        <div class="col-md-5 r-s">
            <div class="right-sec">
                <h4 class="text-left heading-md">Items</h4>
                @php if(array_key_exists("quote_id",$session)): @endphp
                @php if($session['quote_id'] != '' ): @endphp
                @php $quote_id = $session['quote_id']; @endphp
                @php
                    if(isset($session['customer_token']))
                    {
                        $url = createquote().''.$quote_id;
                    }
                    else
                    {
                        $url = createquoteguest().''.$quote_id;
                    }
                @endphp
                @php $cartdata = m2ApiCall($url,'get',''); @endphp
                @foreach($cartdata['items'] as $data)
                    @php if($data['sku'] != 'dummy-product'): @endphp
                    @php $product_info = productinfo($data['sku']); @endphp
                    <div class="cart-items">
                        <div class="col-md-12 p-0">
                            <div class="row">
                                @php $url = catalog_url() @endphp
                                <div class="col-md-3 col-3">
                                    @if($product_info['items']['image'] != '')
                                        <a href="{{ url('/') }}/product/{{ str_replace(' ', '-',strtolower($data['name'])) }}/{{ $product_info['items']['productId'] }}"><img class="img-fluid" src="{{$product_info['items']['image']}}"></a>
                                    @endif
                                    <span id="counter_badge"><span class="badge badge-light">@php echo $data['qty'] @endphp</span></span>
                                </div>
                                <div class="col-md-9 col-9">
                                    <a href="{{ url('/') }}/product/{{ str_replace(' ', '-',strtolower($data['name'])) }}/{{ $product_info['items']['productId'] }}"><h4>@php echo $data['name'] @endphp</h4></a>
                                    <h6 class="ww">@php echo $data['sku'] @endphp</h6>
                                    <h6 class="">$@php echo $data['price'] @endphp</h6>
                                    <span data-id="@php echo $data['quote_id']; @endphp"
                                          data-cart-item="@php echo $data['item_id']; @endphp"
                                          class="remove remove-item">Remove</span>
                                    <div class="form-group">
                                        <input value="@php echo $data['qty'] @endphp" class="form-control quantity-select cartitemqty" type="tel">
                                        <span class="up-counter">
                                            <img src="@php echo url('/'); @endphp/public/images/counter-up-black.png" width="8px">
                                        </span>
                                        <span class="down-counter">
                                            <img src="@php echo url('/'); @endphp/public/images/counter-down-black.png" width="8px">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php endif; @endphp
                @endforeach
                @php endif @endphp
                @php endif @endphp
                <div class="promo_code">
                    <form name="apply_coupon" id="apply_coupon" method="post">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group m-0">
                                    <input type="text" class="form-control" id="coupon_code"
                                           placeholder="Code / Gift Card">
                                </div>
                            </div>
                            <div class="col-md-4 pl-0">
                                <button type="button" class="btn btn-ansel btn-block apply">APPLY</button>
                                <button type="button" class="btn btn-ansel btn-block cancel_coupon"
                                        style="display:none;">CANCEL
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @php if(array_key_exists("quote_id",$session)): @endphp
                @php if($session['quote_id'] != '' ): @endphp
                @php $quote_id = $session['quote_id']; @endphp
                @php
                    if(isset($session['customer_token']))
                    {
                        $url = createquote().''.$quote_id.'/totals';
                    }
                    else
                    {
                        $url = createquoteguest().''.$quote_id.'/totals';
                    }
                @endphp
                @php
                    $totals = m2ApiCall($url,'get','');
                @endphp
                @php if(array_key_exists("message",$totals)): @endphp

                @php else: @endphp
                <div class="subtotal">
                    <table class="table m-0">
                        <tbody>
                        <tr>
                            <td>Subtotal</td>
                            <td class="text-right">${{ number_format($totals['total_segments'][0]['value'],2) }}</td>
                        </tr>
                        <tr class="tax" style="display:none">
                                <td>Tax</td>
                                <td class="text-right">${{number_format($totals['tax_amount'],2)}}</td>
                            </tr>
                        @if($totals['discount_amount'] != 0)
                            <tr class="discount">
                                <td>Discount</td>
                                <td class="text-right">$@php echo number_format(str_replace("-","",$totals['discount_amount']),2); @endphp</td>
                            </tr>
                        @else
                            <tr class="discount" @php if($totals['discount_amount'] == 0): @endphp style="display: none;" @php endif; @endphp>
                                <td>Discount</td>
                                <td class="text-right">${{number_format($totals['discount_amount'],2)}}</td>
                            </tr>
                        @endif
                        <tr class="shipping-total" @php if($totals['shipping_amount'] == 0): @endphp style="display: none;" @php endif; @endphp>
                            <td>Shipping</td>
                            <td class="text-right">${{number_format($totals['shipping_amount'],2)}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="total">
                    <table class="table m-0">
                        <tbody>
                        <tr>
                            <td>Total:</td>
                            <td class="text-right">
                                <h3>
                                    <b>${{number_format($totals['grand_total'],2)}}</b>
                                </h3>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                @php endif @endphp
                @php endif @endphp
                @php endif @endphp
            </div>
        </div>
    </div>
</div>

@php endif; @endphp
<span id="recalculateprice" style="display:none">recalculate</span>
<!-- Modal cart -->
<div id="cart_modal" class="modal  fade right" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" id="cart_modal_dialog" role="document">
        <div class="modal-content">
            <div class="for-sz">
                <div class="modal-header-custom">
                    <h5 class="heading text-left" id="exampleModalLongTitle">Cart</h5>
                    @php if(array_key_exists("quote_id",$session)): @endphp
                    @php if($session['quote_id'] != '' ): @endphp
                    @php $quote_id = $session['quote_id']; @endphp
                    @php if(!is_object($quote_id)): @endphp
                    @php if(isset($session['customer_token'])): @endphp
                    @php $url = createquote().''.$quote_id.'/totals'; @endphp
                    @php $carturl = createquote().''.$quote_id; @endphp
                    @php else: @endphp
                    @php $url = createquoteguest().''.$quote_id.'/totals'; @endphp
                    @php $carturl = createquoteguest().''.$quote_id; @endphp
                    @php endif; @endphp

                    @php $cartdata = m2ApiCall($carturl,'get',''); @endphp
                    @php $totals = m2ApiCall($url,'get',''); @endphp
                    @php if(array_key_exists("message",$totals)): @endphp

                    @php else: @endphp
                    @php if(count($cartdata['items']) > 0): @endphp
                    @php if($totals['total_segments'][0]['value'] < 75): @endphp
                    @php $less_amount = 75 - $totals['total_segments'][0]['value']; @endphp
                    <p class='cart-incentive'>You're so close. Add another $@php echo $less_amount; @endphp to your cart for FREE Shipping.</p>
                    @php else: @endphp
                    <p class='cart-incentive'>Yes! You've qualified for FREE Shipping.</p>
                    @php endif; @endphp
                    @php endif; @endphp
                    @php endif; @endphp
                    @php else: @endphp

                    @php endif; @endphp
                    @php endif; @endphp
                    @php endif; @endphp
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img width="20px" src="{{ url('/') }}/public/images//close.png">
                    </button>
                </div>
                <div class="modal-body p-0">
                    @php if(array_key_exists("quote_id",$session)): @endphp
                    @php if($session['quote_id'] != '' ): @endphp
                    @php $quote_id = $session['quote_id']; @endphp
                    @php if(!is_object($quote_id)): @endphp
                    <?php if(isset($session['customer_token'])){
                        $url = createquote().''.$quote_id;
                    } else{
                        $url = createquoteguest().''.$quote_id;
                    }
                    ?>
                    @php $cartdata = m2ApiCall($url,'get',''); @endphp
                    @php if(array_key_exists('items',$cartdata)): @endphp
                    @foreach($cartdata['items'] as $data)
                        @php if($data['sku'] != 'dummy_product'): @endphp
                        @php $product_info = productinfo($data['sku']); @endphp
                        <div class="cart-items">
                            <div class="col-md-12 p-0">
                                <div class="row">
                                    @php $url = catalog_url() @endphp
                                    <div class="col-md-3 col-3 pr-0">
                                        @if($product_info['items']['image'] != '')
                                            <a href="{{ url('/') }}/product/{{ str_replace(' ', '-',strtolower($data['name'])) }}/{{ $product_info['items']['productId'] }}"><img class="img-fluid" src="{{$product_info['items']['image']}}"></a>
                                        @endif
                                    </div>
                                    <div class="col-md-9 col-9">
                                        <a href="{{ url('/') }}/product/{{ str_replace(' ', '-',strtolower($data['name'])) }}/{{ $product_info['items']['productId'] }}"><h4>@php echo $data['name'] @endphp</h4></a>
                                        <h6 class="ww">@php echo $data['sku'] @endphp</h6>
                                        <h6>$@php echo $data['price'] @endphp</h6>
                                        <span data-id="@php echo $cartdata['id'] @endphp" data-cart-item="@php echo $data['item_id'] @endphp" class="remove remove-item">Remove</span>
                                        <div class="form-group">
                                            <input value="@php echo $data['qty'] @endphp" class="form-control quantity-select cartitemqty" type="tel">
                                            <span class="up-counter">
                                                <img src="@php echo url('/'); @endphp/public/images/counter-up-black.png" width="8px">
                                            </span>
                                            <span class="down-counter">
                                                <img src="@php echo url('/'); @endphp/public/images/counter-down-black.png" width="8px">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php endif; @endphp
                    @endforeach
                    @php else: @endphp
                    <div class="empty-cart"><p class="m-0">0 products in your cart</p></div>
                    @php endif; @endphp
                    @php else: @endphp
                    <div class="empty-cart"><p class="m-0">0 products in your cart</p></div>
                    @php endif; @endphp
                    @php endif; @endphp
                    @php else: @endphp
                    <div class="empty-cart"><p class="m-0">0 products in your cart</p></div>
                    @php endif; @endphp
                    @php if(array_key_exists("quote_id",$session)): @endphp
                    @php if($session['quote_id'] != '' ): @endphp
                    @php $quote_id = $session['quote_id']; @endphp
                    @php if(!is_object($quote_id)): @endphp
                    @php if(isset($session['customer_token'])): @endphp
                    @php //$url = createquote().''.$quote_id.'/totals'; @endphp
                    @php else: @endphp
                    @php //$url = createquoteguest().''.$quote_id.'/totals'; @endphp
                    @php endif; @endphp
                    @php //$totals = m2ApiCall($url,'get',''); @endphp
                    @php if(array_key_exists("message",$totals)): @endphp

                    @php else: @endphp
                    @php if(count($cartdata['items']) > 0): @endphp
                    <div class="total">
                        <div class="row">
                            <div class="col-md-6 col-6 text-left">
                                <h2>Subtotal:</h2>
                            </div>
                            <div class="col-md-6 col-6 text-right">
                                <h2>${{number_format($totals['total_segments'][0]['value'],2)}}</h2>
                            </div>
                        </div>
                    </div>
                    @php else: @endphp

                    @php endif; @endphp
                    @php endif; @endphp
                    @php else: @endphp

                    @php endif; @endphp
                    @php endif; @endphp
                    @php endif; @endphp
                </div>
                <div class="modal-footer-custom text-center">
                    @php if(array_key_exists("quote_id",$session)): @endphp
                    @php if($session['quote_id'] != '' ): @endphp
                    @php if(!is_object($quote_id)): @endphp
                    @php if(array_key_exists('items',$cartdata)): @endphp
                    @php if(count($cartdata['items']) > 0): @endphp
                    <div class="col-md-12">
                        <button type="button" class="btn btn-ansel btn-checkout">CHECKOUT</button>
                    </div>
                    <div class="col-md-12">
                        <h6>Shipping and taxes calculated during checkout.</h6>
                    </div>
                    @php else: @endphp
                    <div class="col-md-12">
                        <button type="button" class="btn btn-ansel btn-continue-shopping" data-dismiss="modal">CONTINUE SHOPPING</button>
                    </div>
                    <div class="col-md-12">
                        <h6>Shipping and taxes calculated during checkout.</h6>
                    </div>
                    @php endif; @endphp
                    @php else: @endphp
                    <div class="col-md-12">
                        <button type="button" class="btn btn-ansel btn-continue-shopping" data-dismiss="modal">CONTINUE SHOPPING</button>
                    </div>
                    <div class="col-md-12">
                        <h6>Shipping and taxes calculated during checkout.</h6>
                    </div>
                    @php endif; @endphp
                    @php endif; @endphp
                    @php else: @endphp
                    <div class="col-md-12">
                        <button type="button" class="btn btn-ansel btn-continue-shopping" data-dismiss="modal">CONTINUE SHOPPING</button>
                    </div>
                    <div class="col-md-12">
                        <h6>Shipping and taxes calculated during checkout.</h6>
                    </div>
                    @php endif; @endphp
                    @php else: @endphp
                    <div class="col-md-12">
                        <button type="button" class="btn btn-ansel btn-continue-shopping" data-dismiss="modal">CONTINUE SHOPPING</button>
                    </div>
                    <div class="col-md-12">
                        <h6>Shipping and taxes calculated during checkout.</h6>
                    </div>
                    @php endif; @endphp
                </div>
            </div>
        </div>
    </div>
</div>

<!--  forgot-password Modal -->
<div class="modal fade" id="forgot-password" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Don’t worry - this happens to us all the time
                    too.</h5>
                <p class="m-0">To have your password reset, enter your email address below.</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forgot-password" method="post" name="forgot-password">
                    <div class="form-group">
                        <input type="email" class="form-control" id="forgot-email" aria-describedby="emailHelp"
                               placeholder="your@example.com*">
                    </div>
                    <button class="btn-block btn-ansel"
                            type="submit" {{--data-toggle="modal" data-target="#forgot-password-message"--}}>SEND EMAIL
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!--  forgot-password message Modal -->
<div class="modal fade" id="forgot-password-message" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Don’t worry - this happens to us all the time too.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="m-0 text-center">
                    Please check your email to reset your password.
                </p>
            </div>
        </div>
    </div>
</div>

<!--  add address Modal -->
<div class="modal fade" id="add-address-modal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 add-recipient">
                        <h4>Add Address</h4>
                        <div class="form-group">
                            <input type="text" class="form-control" id="firstname" aria-describedby="emailHelp" placeholder="First Name*">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="lastname" aria-describedby="emailHelp" placeholder="Last Name*">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="addressline1" aria-describedby="emailHelp" placeholder="Address*">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="addressline2" aria-describedby="emailHelp" placeholder="Unit #">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="city" aria-describedby="emailHelp" placeholder="City*">
                        </div>
                        <div class="form-group">
                            @include('layout.statedropdown')
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="country" aria-describedby="emailHelp" placeholder="Country*" value="United States" disabled readonly>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="postcode" aria-describedby="emailHelp" placeholder="Postal Code*">
                        </div>
                        <div class="form-group">
                            <input type="tel" class="form-control" id="telephone" aria-describedby="emailHelp" placeholder="Telephone*">
                        </div>
                        @php if(isset($_COOKIE["customer_token"])): @endphp
                        @php if(!key_exists('message',$customerData)): @endphp
                        <label class="custom-checkbox">Save this information for next time
                            <input type="checkbox" checked="checked" name="saveaddress" id="saveaddress">
                            <span class="checkmark"></span>
                        </label>
                        @php else: @endphp
                        @php endif; @endphp
                        @php endif; @endphp
                        <button type="button" class="btn btn-ansel btn-block mt-4">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  add address Modal -->
<div class="modal fade" id="add-billing-address-modal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 add-recipient">
                        <h4>Add Billing Address</h4>
                        <div class="form-group">
                            <input type="text" class="form-control" id="bill-firstname" aria-describedby="emailHelp" placeholder="First Name*">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="bill-lastname" aria-describedby="emailHelp" placeholder="Last Name*">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="bill-addressline1" aria-describedby="emailHelp" placeholder="Address*">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="bill-addressline2" aria-describedby="emailHelp" placeholder="Unit #">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="bill-city" aria-describedby="emailHelp" placeholder="City*">
                        </div>
                        <div class="form-group">
                            @include('layout.statedropdown')
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="bill-country" aria-describedby="emailHelp" placeholder="Country*" value="United States" disabled readonly>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="bill-postcode" aria-describedby="emailHelp" placeholder="Postal Code*">
                        </div>
                        <div class="form-group">
                            <input type="tel" class="form-control" id="bill-telephone" aria-describedby="emailHelp" placeholder="Telephone*">
                        </div>
                        <button type="button" class="btn btn-ansel btn-block mt-4">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  add recipient Modal -->
<div class="modal fade" id="add-recipient-modal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 add-recipient">
                        <h4>Add Recipient Info</h4>
                        <div class="form-group">
                            <input type="text" class="form-control" id="recipient-name" aria-describedby="emailHelp" placeholder="Name Of Recipient*">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="recipient-email" aria-describedby="emailHelp" placeholder="Email Address Of Recipient*">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="recipient-message" placeholder="Personal Message*" rows="3"></textarea>
                        </div>
                        <button type="button" class="btn btn-ansel btn-block mt-4">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--  refer friend Modal -->
<div class="modal fade" id="refer-friend" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <div class="row">
                    <div class="col-lg-6 col-md-12 refer-friend-left pl-0">
                        <img class="img-fluid" src="@php echo url('/'); @endphp/public/images/Referafriend.jpg">
                    </div>
                    <div class="col-lg-6 col-md-12 refer-friend-right d-flex align-items-center">
                        <div class="position-relative">
                            <h3>Get 15% off for every friend you refer</h3>
                            <p>Love Ansel & Ivy? Tell your friends and you both get 15% off when they make their first purchase.</p>
                            <form class="referral-form">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="referral-email" placeholder="To: Enter friend’s emails (separated by commas)">
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" id="referral-message" placeholder="Message" rows="3"></textarea>
                                </div>
                                <div class="col-md-12 p-0">
                                    <button type="button" class="btn btn-ansel btn-block referral-send">SEND EMAIL</button>
                                </div>
                            </form>
                            {{--<div class="col-md-12 p-0">
                                <h4 class="or">
                                        <span>
                                            OR
                                        </span>
                                </h4>
                                <h6>Share the Link on Your Favorite Networks</h6>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="" value="https://projects.invisionapp.com/d/main#/console/12757611/275954673/inspect"
                                           aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-ansel" type="button">
                                            <i class="fas fa-link"></i>
                                        </button>
                                    </div>
                                </div>
                                <ul class="social mt-3 list-inline">
                                    <li class="list-inline-item">
                                        <a href="#!">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#!">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#!">
                                            <i class="fab fa-google-plus-g"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#!">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.include_footer')

<script>
    jQuery(document).ready(function ($) {
        $(document).on('submit','form.forgot-password',function(){
            var valid = false;
            var email = $("form.forgot-password #forgot-email").val();
            var regEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
            if(!email){
                valid = false;
                $('<p class="error" style="color:red;">This is a required field.</p>').
                insertAfter('form.forgot-password #forgot-email');
            } if (email && !regEmail.test(email)) {
                valid = false;
                $('<p class="error" style="color:red;">Please enter valid email address</p>').
                insertAfter('form.forgot-password #forgot-email');
            } else{
                //$('div.main-loader').css('display','block');
                $('form.forgot-password button.btn-block.btn-ansel').addClass('disabled').attr('disabled','disabled');
                data = {'email':email};
                url = '@php echo url('/')."/forgotpassword"; @endphp',
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: 'POST',
                        url: url,
                        data: data,
                        success: function(data){
                            $('form.forgot-password button.btn-block.btn-ansel').removeClass('disabled').removeAttr('disabled');
                            $('#forgot-password').modal('hide');
                            $('#forgot-password-message .m-0.text-center').text("To finish resetting your password, check your email at "+ email +" and follow the instructions. ");
                            $('#forgot-password-message').modal('show');
                            $('div.main-loader').css('display','none');
                        },
                        dataType:'json'
                    });
            }
            return valid;
        });
        $('form.create-acc button.create-account').on('click', function () {
            $('form.create-acc .error').remove();
            var valid = true;
            var regName = /^[a-z ,.'-]+$/i;
            var regEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
            if (!$('form.create-acc #fullname').val()) {
                valid = false;
                $('<p class="error" style="color:red;">This is required field</p>').insertAfter('form.create-acc #fullname');
            }
            if (!$('form.create-acc #reg_email').val()) {
                valid = false;
                $('<p class="error" style="color:red;">This is required field</p>').insertAfter('form.create-acc #reg_email');
            }
            if (!$('form.create-acc #password').val()) {
                valid = false;
                $('<p class="error" style="color:red;">This is required field</p>').insertAfter('form.create-acc #password');
            }
            if ($('form.create-acc #fullname').val() != '' && !regName.test($('form.create-acc #fullname').val())) {
                valid = false;
                $('<p class="error" style="color:red;">Please enter valid email address</p>').insertAfter('form.create-acc #fullname');
            }
            if ($('form.create-acc #reg_email').val() != '' && !regEmail.test($('form.create-acc #reg_email').val())) {
                valid = false;
                $('<p class="error" style="color:red;">Please enter valid email address</p>').insertAfter('form.create-acc #reg_email');
            } else {
                valid = true;
            }
            return valid;
        });
        $('input#giftcheck').on('change', function () {
            giftcheck = $(this).is(':checked');
            if (giftcheck == true) {
                $('div#gift_form').css({'display': 'block'});
            } else {
                $('div#gift_form').css({'display': 'none'});
                $('div#gift_form #shippingReceiverName').val('');
                $('div#gift_form #shippingReceiverEmail').val('');
                $('div#gift_form #shippingReceiverMessage').val('');
            }
        });
        @php if(isset($_COOKIE["customer_token"])): @endphp
        @php if(!key_exists('message',$customerData)): @endphp
        $('div.main-loader').css('display','block');
        $('.checkout #pills-tabContent #pills-customer-info').addClass('active').addClass('show');
        var url = '@php echo url('/').'/customershippingaddress'; @endphp';
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                var jsonData = JSON.parse(JSON.stringify(data));
                console.log(jsonData);
                if(jsonData != ''){
                    if (jsonData.length == '0') {
                        @php $custId = m2apiendpoint(). 'customers/me'; @endphp
                        @php $data['customerData'] = loggedinApiCall($custId,'get',''); @endphp
                        @php if(!key_exists('message',$data['customerData'])): @endphp
                        @php $email = $data['customerData']['email']; @endphp
                        $('form.shippingmethodForm #shippingEmail').val('@php echo $email; @endphp');
                        $('form.shippingmethodForm #shippingEmail-text').html('');
                        $('form.shippingmethodForm #shippingEmail-text').text('@php echo $email; @endphp');
                        @php endif; @endphp
                    } else {
                        $('#saveaddress').prop('checked', false);
                        @php if(isMobile()): @endphp
                        if(jsonData.street[1] != '' && jsonData.street[1] != undefined){
                            var address_string = '<h6>Customer Info</h6><span class="change"><a href="javascript:void(0);" data-toggle="modal" data-target="#add-address-modal">Change</a></span><p>'+ jsonData.firstname + ' ' + jsonData.lastname +'</p><p>'+ jsonData.street[0] + ' ' + jsonData.street[1] +'</p><p>'+ jsonData.city +', ' + jsonData.region.region + ', ' + jsonData.postcode +'</p><p>United States</p><p>'+ jsonData.telephone +'</p><label class="custom-checkbox mb-3">This is a Gift<input type="checkbox" name="have_recipient"><span class="checkmark"></span></label><button type="button" class="btn btn-ansel btn-block add_recipient_info">Add Recipient info</button>';
                        } else{
                            var address_string = '<h6>Customer Info</h6><span class="change"><a href="javascript:void(0);" data-toggle="modal" data-target="#add-address-modal">Change</a></span><p>'+ jsonData.firstname + ' ' + jsonData.lastname +'</p><p>'+ jsonData.street[0] + ' </p><p>'+ jsonData.city +', ' + jsonData.region.region + ', ' + jsonData.postcode +'</p><p>United States</p><p>'+ jsonData.telephone +'</p><label class="custom-checkbox mb-3">This is a Gift<input type="checkbox" name="have_recipient"><span class="checkmark"></span></label><button type="button" class="btn btn-ansel btn-block add_recipient_info">Add Recipient info</button>';
                        }
                        $('#add-address-modal #firstname').val(jsonData.firstname);
                        $('#add-address-modal #lastname').val(jsonData.lastname);
                        $('#add-address-modal #addressline1').val(jsonData.street[0]);
                        if(jsonData.street[1] != '' && jsonData.street[1] != undefined){
                            var addressline2 = jsonData.street[1];
                            $('#add-address-modal #addressline2').val(jsonData.street[1]);
                        } else{
                            var addressline2 = '';
                        }
                        $('#add-address-modal #city').val(jsonData.city);
                        $('#add-address-modal #country').val("United States");
                        $('#add-address-modal #postcode').val(jsonData.postcode);
                        $('#add-address-modal #telephone').val(jsonData.telephone);
                        $('#add-address-modal .select option[value="' + jsonData.region.region + '"]').prop('selected', true);
                        $('#add-address-modal .select-styled').text('');
                        $('#add-address-modal .select-styled').text(jsonData.region.region);
                        $('.checkout-mobile .row div#mobile-step-2 div:nth-child(2) div.col-12.checkout-cards:nth-child(2)').html(address_string).addClass('customer-info-mob');

                                @php if(array_key_exists("quote_id",$session)): @endphp
                                @php if($session['quote_id'] != '' ): @endphp
                                @php $quote_id = $session['quote_id']; @endphp
                        var quote_id = '@php echo $quote_id; @endphp';
                                @php endif; @endphp
                                @php endif; @endphp
                        var data = {"quote_id": quote_id,
                                "email": $('#mobile-step-2 div:nth-child(2) div.col-12.checkout-cards p.m-0').text(),
                                "firstname": $('#add-address-modal #firstname').val(),
                                "lastname": $('#add-address-modal #lastname').val(),
                                "address": $('#add-address-modal #addressline1').val(),
                                "addressline2": $('#add-address-modal #addressline2').val(),
                                "city": $('#add-address-modal #city').val(),
                                "state": $('#add-address-modal .select-styled').text(),
                                "region_id": $('#add-address-modal .select option:selected').val(),
                                "country": "US",
                                "postcode": $('#add-address-modal #postcode').val(),
                                "receivername": "",
                                "receiveremail": "",
                                "message": "",
                                "same_as_billing": 1,
                                "telephone": $('#add-address-modal #telephone').val()};
                        var url = '@php echo url('/').'/estimateshipping'; @endphp';
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            type: "POST",
                            url: url,
                            data: data,
                            success: function (data) {
                                var jsonData = JSON.parse(JSON.stringify(data));
                                var shipping_method = '';
                                for (var i = 0; i < jsonData.length; i++) {
                                    var counter = jsonData[i];
                                    if(i == 0){
                                        shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" checked="checked" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>$' + counter['amount'] + '</p></div>';
                                    } else{
                                        shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>$' + counter['amount'] + '</p></div>';
                                    }
                                }
                                $('#shipping_append').html(shipping_method);
                                var method_code = $('#shipping_append input[name=shipping-method]:checked').val();
                                var career_code = $('#shipping_append input[name=shipping-method]:checked').val();
                                var data = {
                                    "quote_id": quote_id,
                                    "email": $('#mobile-step-2 div:nth-child(2) div.col-12.checkout-cards p.m-0').text(),
                                    "firstname": $('#add-address-modal #firstname').val(),
                                    "lastname": $('#add-address-modal #lastname').val(),
                                    "address": $('#add-address-modal #addressline1').val(),
                                    "addressline2": $('#add-address-modal #addressline2').val(),
                                    "city": $('#add-address-modal #city').val(),
                                    "state": $('#add-address-modal .select-styled').text(),
                                    "region_id": $('#add-address-modal .select option:selected').val(),
                                    "country": "US",
                                    "postcode": $('#add-address-modal #postcode').val(),
                                    "receivername": '',
                                    "receiveremail": '',
                                    "message": '',
                                    "shippingCarrierCode": career_code,
                                    "shippingMethodCode": method_code,
                                    "telephone": $('#add-address-modal #telephone').val()
                                };

                                var url = '@php echo url('/').'/shippinginformation'; @endphp';
                                $.ajax({
                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                    type: "POST",
                                    url: url,
                                    data: data,
                                    success: function (data) {
                                        $('#pills-shipping-method .continue-to-payment').removeClass('disabled').removeAttr('disabled');
                                        var jsonData = JSON.parse(JSON.stringify(data));
                                        var string = '';
                                        for (var i = 0; i < jsonData['payment_methods'].length; i++) {
                                            var counter = jsonData['payment_methods'][i];
                                            string += '<div class="billing-mob"><label class="custom-radio">' + counter['title'] + '<input checked="checked" name="payment-method" value="' + counter['code'] + '" type="radio" methodlabel="' + counter['title'] + '" required><span class="checkmark"></span></label></div>';
                                        }
                                        $('#payment_method_mob').html(string);
                                        var billing_method = '<div class="div-divider"></div><h6>Billing Address</h6><div class="billing-mob"><label class="custom-radio">Same as shipping address<input type="radio" checked="checked" name="radio"><span class="checkmark"></span></label></div><div class="billing-mob border-0"><label class="custom-radio">Use a different billing address<input type="radio" name="radio"><span class="checkmark"></span></label></div>';
                                        $('#billing_method_mob').html(billing_method);
                                        $('.col-md-12.cart-total-mob .row div:nth-child(2) h6').text('$'+ (jsonData['totals'].base_subtotal).toFixed(2));
                                        $('.col-md-12.cart-total-mob .row div:nth-child(4) h6').text('$'+jsonData['totals'].shipping_amount);
                                        $('.col-md-12.cart-total-mob .row div.tax-amount-mob h6').text(('$'+jsonData['totals'].tax_amount).replace('-',''));
                                        $('.col-md-12.cart-total-mob .row div.discount-amount-mob h6').text(('$'+jsonData['totals'].discount_amount).replace('-',''));
                                        $('.col-md-12.cart-total-mob .row div:nth-child(4)').css('display','block');
                                        $('.col-md-12.cart-total-mob .row div:nth-child(3)').css('display','block');
                                        $('.col-md-12.cart-total-mob .row div:last-child h2').text('$'+(jsonData['totals'].grand_total).toFixed(2));
                                        $('.cart-total-mob button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled');
                                        $('.disabled_div').remove();
                                    },
                                    dataType:'json'
                                });
                            },
                            dataType:'json'
                        });
                        @php else: @endphp
                        $('form.shippingmethodForm #shippingFirstname').val(jsonData.firstname);
                        $('form.shippingmethodForm #shippingLastname').val(jsonData.lastname);
                        $('form.shippingmethodForm #shippingAddress').val(jsonData.street[0]);
                        $('form.shippingmethodForm #shippinngAddressline2').val(jsonData.street[1]);
                        $('form.shippingmethodForm #shippingCity').val(jsonData.city);
                        /*$('form.shippingmethodForm #shippingState').val(jsonData.region.region);*/
                        $('form.shippingmethodForm #country_id').val('United States');
                        $('form.shippingmethodForm #shippingPostCode').val(jsonData.postcode);
                        $('form.shippingmethodForm #shippingTelephone').val(jsonData.telephone)
                        $('form.shippingmethodForm .select option[value="' + jsonData.region.region + '"]').prop('selected', true);
                        $('form.shippingmethodForm .select-styled').text($('form.shippingmethodForm .select option[data-title="' + jsonData.region.region + '"]').text());
                        /*$('form.shippingmethodForm .select option[value="' + jsonData.country_id + '"]').prop('selected', true);
                        $('form.shippingmethodForm .select-styled').text($('form.shippingmethodForm .select option[value="' + jsonData.country_id + '"]').text());*/
                        $('<input type="hidden" value="' + jsonData.customer_id + '" id="customer_id"/>').insertAfter('form.shippingmethodForm #shippingEmail');
                        $('<input type="hidden" value="' + jsonData.id + '" id="address_id"/>').insertAfter('form.shippingmethodForm #shippingEmail');
                        var url1 = "@php echo url('/').'/customersalldata/'; @endphp" + jsonData.customer_id + "";
                        $.ajax({
                            type: 'GET',
                            url: url1,
                            success: function (alldata) {
                                var jsonResult = JSON.parse(JSON.stringify(alldata));
                                console.log(jsonResult.email);
                                $('form.shippingmethodForm #shippingEmail').val(jsonResult.email);
                                $('form.shippingmethodForm #shippingEmail-text').html('');
                                $('form.shippingmethodForm #shippingEmail-text').text(jsonResult.email);
                                $('form.shippingmethodForm .row .col-md-12 span').remove();
                            }
                        });
                        @php endif; @endphp
                    }
                } else{
                    @php $custId = m2apiendpoint(). 'customers/me'; @endphp
                    @php $data['customerData'] = loggedinApiCall($custId,'get',''); @endphp
                    @php if(!key_exists('message',$data['customerData'])): @endphp
                    @php $email = $data['customerData']['email']; @endphp
                    $('form.shippingmethodForm #shippingEmail').val('@php echo $email; @endphp');
                    $('form.shippingmethodForm #shippingEmail-text').html('');
                    $('form.shippingmethodForm #shippingEmail-text').text('@php echo $email; @endphp');
                    @php endif; @endphp
                }
                $('div.main-loader').css('display','none');
            },
            dataType: 'json'
        });
        @php endif; @endphp
        @php endif; @endphp
        $('form.shippingmethodForm .select .select-styled').text($('#country_id option:selected').html());
        $('form.differnt_billing_address .select .select-styled').text($('#country_id option:selected').html());
        $(document).on('click','#apply_coupon .apply' ,function () {
            var valid = true;
            $('#apply_coupon .error').remove();
            $('#apply_coupon .form-group p').remove();
            if (!$('#coupon_code').val()) {
                valid = false;
                $('<p style="color:red;" class="error">Please enter coupon code</p>').insertAfter('#coupon_code');
            } else {
                //$('div.main-loader').css('display','block');
                $('#apply_coupon .apply').addClass('disabled').attr('disabled','disabled');
                $('.right-sec').append('<div class="dev-loader"></div>');
                var coupon_code = $('#coupon_code').val();
                valid = true;
                var url = '@php echo url('/').'/coupons/'.$session['quote_id'].'/'; @endphp' + coupon_code;
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (data) {
                        $.ajax({
                            type: "GET",
                            url : '@php echo url("/"); @endphp/referralcode/'+coupon_code,
                            success : function (data){
                                if(data.result == 'coupon_found'){
                                    var referral_sender_email = data.coupondata[0].sender_email;
                                    localStorage.setItem('referral_sender_email', referral_sender_email);
                                } else{
                                    localStorage.setItem('referral_sender_email', '');
                                }
                            },
                            dataType: 'json'
                        });
                        $('#apply_coupon .apply').removeClass('disabled').removeAttr('disabled');
                        if (data.coupon == true) {
                            $('.coupon_applied').remove();
                            localStorage.setItem('applied_coupon', $('#coupon_code').val());
                            $('<p class="coupon_applied" style="color:green">Code applied.</p>').insertAfter('#coupon_code');
                            $('#apply_coupon button.cancel_coupon').css({'display': 'block', 'margin-top': '0px'});
                            $('#apply_coupon button.apply').css({'display': 'none'});

                            var discount = Math.abs(data.total.discount_amount);
                            @php if(isMobile()): @endphp
                            $('.cart-total-mob .row .discount-amount-mob h6').html('$' + (discount).toFixed(2));
                            $('.cart-total-mob .row .discount-amount-mob').css('display','block');
                            $('.cart-total-mob .row .discount-text-mob').css('display','block');
                            var grandtotal = data.total.grand_total;
                            var shipping = $('.cart-total-mob .row div.col-6:nth-child(4) h6').html();
                            if (shipping != '-' && shipping != '') {
                                if(shipping == 'Free'){
                                    shipping = '$0';
                                }
                                shipping = shipping.replace('$', '');
                                grandtotal = eval(shipping) + eval(data.total.subtotal) + eval(data.total.discount_amount);
                            } else {
                                grandtotal = eval(data.total.grand_total) + eval(data.total.discount_amount) ;
                            }
                            $('.cart-total-mob .row div.col-6:last-child h2').html('$' + (grandtotal).toFixed(2));
                            @php else: @endphp
                            $('.subtotal .discount td.text-right').html('$' + (discount).toFixed(2));
                            // }
                            $('.right-sec .dev-loader').remove();
                            var grandtotal = data.total.grand_total;
                            var shipping = $('.subtotal table tr.shipping-total td.text-right').text();
                            if (shipping != '-' && shipping != '') {
                                if(shipping == 'Free'){
                                    shipping = '$0';
                                }
                                shipping = shipping.replace('$', '');
                                grandtotal = eval(shipping) + eval(data.total.subtotal) + eval(data.total.discount_amount);
                            } else {
                                grandtotal = eval(data.total.grand_total) + eval(data.total.discount_amount);
                            }
                            $('.subtotal .discount').css({'display': 'table-row'});
                             // Add tax for adding of coupan 
                            if($("#pills-payment-tab").hasClass("active")){
                                 $('.right-sec .subtotal tr.tax td.text-right').text('$'+(data.total.base_tax_amount).toFixed(2));
                                grandtotal =eval(grandtotal)+eval(data.total.base_tax_amount);
                            }
                            $('.total td.text-right h3 b').html('$' + (grandtotal).toFixed(2));
                            @php endif; @endphp
                        } else {
                            $("#coupon_code").siblings("p").remove();
                            $("#coupon_code").addClass("remove_coupan_code");
                            $('<p class="error" style="color:red">Oops! Invalid code.</p>').insertAfter('#coupon_code');
                        }
                        $('div.main-loader').css('display','none');
                    },
                    dataType: 'json'
                });
            }
            return valid;
        });
        // Check email exist

        $('#apply_coupon .cancel_coupon').on('click', function () {
            //$('div.main-loader').css('display','block');
            $('#apply_coupon .cancel_coupon').addClass('disabled').attr('disabled','disabled');
            $('.right-sec').append('<div class="dev-loader"></div>');
            var coupon_code = $('#coupon_code').val();
            $('#apply_coupon .form-group p').remove();
            var url = '@php echo url('/').'/removecoupon/'.$session['quote_id'].'/'; @endphp' + coupon_code;
            $.ajax({
                type: "GET",
                url: url,
                success: function (data) {
                    $('#apply_coupon .cancel_coupon').removeClass('disabled').removeAttr('disabled','disabled');
                    if (data.coupon == true) {
                        localStorage.setItem('applied_coupon', '');
                        $('<p style="color:green">Code removed.</p>').insertAfter('#coupon_code');
                        $('#apply_coupon button.cancel_coupon').css({'display': 'none'});
                        $('#apply_coupon button.apply').css({'display': 'block', 'margin-top': '0px'});
                        $('#apply_coupon #coupon_code').val('');

                        var discount = Math.abs(data.total.discount_amount);
                        @php if(isMobile()): @endphp
                        $('.cart-total-mob .row .discount-amount-mob h6').html('$' + (discount).toFixed(2));
                        $('.cart-total-mob .row .discount-amount-mob').css('display','none');
                        $('.cart-total-mob .row .discount-text-mob').css('display','none');
                        var grandtotal = data.total.grand_total;
                        var shipping = $('.cart-total-mob .row div.col-6:nth-child(4) h6').html();
                        if (shipping != '-' && shipping != '') {
                            if(shipping == 'Free'){
                                shipping = '$0';
                            }
                            shipping = shipping.replace('$', '');
                            grandtotal = eval(shipping) + eval(data.total.subtotal) + eval(data.total.discount_amount);
                        } else {
                            grandtotal = eval(data.total.grand_total) + eval(data.total.discount_amount);
                        }
                        $('.cart-total-mob .row div.col-6:last-child h2').html('$' + (grandtotal).toFixed(2));
                        @php else: @endphp
                        $('.subtotal .discount td.text-right').html('$' + (discount).toFixed(2));
                        // }
                        $('.right-sec .dev-loader').remove();
                        var grandtotal = data.total.grand_total;
                        var shipping = $('.subtotal table tr.shipping-total td.text-right').text();
                        if (shipping != '-' && shipping != '') {
                            if(shipping == 'Free'){
                                shipping = '$0';
                            }
                            shipping = shipping.replace('$', '');
                            grandtotal = eval(shipping) + eval(data.total.subtotal) + eval(data.total.discount_amount);
                        } else {
                            grandtotal = eval(data.total.grand_total) + eval(data.total.discount_amount);
                        }
                        $('.subtotal .discount').css({'display': 'none'});
                        // Add tax for adding of coupan 
                        if($("#pills-payment-tab").hasClass("active")){
                            $('.right-sec .subtotal tr.tax td.text-right').text('$'+(data.total.base_tax_amount).toFixed(2));
                                grandtotal =eval(grandtotal)+eval(data.total.base_tax_amount);
                        }
                        $('.total td.text-right h3 b').html('$' + (grandtotal).toFixed(2));
                        // $('.subtotal tr.discount').hide();
                        $('.right-sec .dev-loader').remove();
                        @php endif; @endphp

                    } else {
                        $('<p class="error" style="color:red">Coupon code is not valid!</p>').insertAfter('#coupon_code');
                    }
                    $('div.main-loader').css('display','none');
                },
                dataType: 'json'
            });
        });
        $('.form-group.state-select .select ul.select-options li').on('click', function () {
            var selected_state = $(this).text();
         //   var regionId = $(this).rel();
            console.log(selected_state);
        })
    });
    $('form.existing_customer button.btn.btn-ansel.btn-block.mt-3').on('click', function () {
        valid = true;
        $('form.create-acc .error').remove();
        $('form.existing_customer .error').remove();
        $('form.guestEmailform .error').remove();
        var regEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
        if (!$('form.existing_customer #existingEmail').val()) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter your email</p>').insertAfter('form.existing_customer #existingEmail');
        } else if (!$('form.existing_customer #existingPass').val()) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter your password</p>').insertAfter('form.existing_customer #existingPass');
        } else if ($('form.existing_customer #existingEmail').val() && !regEmail.test($('form.existing_customer #existingEmail').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid email address</p>').insertAfter('form.existing_customer #existingEmail');
        } else {
            valid = true;
            //$('div.main-loader').css('display','block');
            $('form.existing_customer button.btn.btn-ansel.btn-block.mt-3').addClass('disabled').attr('disabled','disabled');
            $('form.existing_customer .error').remove();
            var email = $('form.existing_customer #existingEmail').val();
            var pass = $('form.existing_customer #existingPass').val();
            var url = '@php echo url('/').'/customerauth'; @endphp';
            data = {'email': email, 'password': pass};
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: url,
                data: data,
                success: function (data) {
                    $('form.existing_customer button.btn.btn-ansel.btn-block.mt-3').removeClass('disabled').removeAttr('disabled');
                    if (data.message) {
                        $('<p class="error" style="color:red;">Sorry, we didn’t recognize your email or password. Please try again.</p>').insertAfter('form.existing_customer #existingPass');
                    } else {
                        var customer_token = data;
                        var now = new Date();
                        var time = now.getTime();
                        time += 3600 * 1000;
                        now.setTime(time);
                        document.cookie = "customer_token = " + customer_token + "; expires=" + now.toUTCString() +
                            "; path=/";
                        location.reload(true);
                    }
                    $('div.main-loader').css('display','none');
                },
                dataType: 'json'
            });
        }
        return valid;
    });
    $('form.create-acc button.guest-checkout').on('click', function () {
        if ($('form.guestEmailform #guestEmail').val() != '') {
            var email = $('form.guestEmailform #guestEmail').val();
            @php if(isMobile()): @endphp
            $('form.mobile_guestEmailform #guestEmail').val(email).css({'position':'absolute','top':'-1000px','opacity':'0'});
            $('.checkout-mobile #shippingEmail-text').text(email);
            $('form.create-acc ,#email-forms-mob').css('display','none');
            $('#mobile-step-2').css('display','block');
            @php else: @endphp
            $('form.shippingmethodForm #shippingEmail').val(email);
            $('form.shippingmethodForm #shippingEmail-text').html('');
            $('form.shippingmethodForm #shippingEmail-text').text(email);
            @php endif; @endphp
        }
        $('.main_top #pills-tab li.nav-item a').removeClass('active');
        $('.main_top #pills-tab li:nth-child(2) a').removeClass('disabled').addClass('active');
        $('#pills-tabContent #pills-sign-in').removeClass('show').removeClass('active');
        $('#pills-tabContent #pills-customer-info').addClass('show').addClass('active');
        $('form.create-acc').css('display','none');
        $('form.guestEmailform').css('display','block');
    });
    $('form.guestEmailform button.continue-guest').on('click', function () {
        $('form.shippingmethodForm .error').remove();
        $('form.create-acc .error').remove();
        $('form.guestEmailform .error').remove();
        var valid = true;
        var regEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
        if ($('form.guestEmailform #guestEmail').val() == '') {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.guestEmailform #guestEmail');

        } else if ($('form.guestEmailform #guestEmail').val() != '' && !regEmail.test($('form.guestEmailform #guestEmail').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid email address</p>').insertAfter('form.guestEmailform #guestEmail');

        } else {
            //$('div.main-loader').css('display','block');
            $('form.guestEmailform button.continue-guest').addClass('disabled').attr('disabled','disabled');
            valid = true;
            var email = $('form.guestEmailform #guestEmail').val();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: '{{ url('/') }}/email-validation',
                data: {'email': email},
                dataType: 'json',
                success: function (data) {
                    $('form.guestEmailform button.continue-guest').removeClass('disabled').removeAttr('disabled');
                    if (data === 0) {
                        $('form.existing_customer').css({'display': 'block'});
                        var email = $('form.guestEmailform #guestEmail').val();
                        $('form.existing_customer #existingEmail').val(email);
                        $('form.guestEmailform').css({'display': 'none'});
                        $('form.forgot-password #forgot-email').val(email);
                    }
                    else {
                        $('form.create-acc').css({'display': 'block'});
                        var email = $('form.guestEmailform #guestEmail').val();
                        $('form.create-acc #reg_email').val(email);
                        $('form.guestEmailform').css({'display': 'none'});
                    }
                    $('div.main-loader').css('display','none');
                },
            });
        }
        return valid;
    });
    $(document).on('click','button.add_address_reqest',function(){
        $(".error").remove();
        valid = false;
        var regEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
        if($('#guestEmail').val() == ''){
            valid = false;
            $("<p class='error' style='color:red;'>This is required field.</p>").insertAfter('#guestEmail');
        } else if($('#guestEmail').val() && !regEmail.test($('#guestEmail').val())){
            valid = false;
            $("<p class='error' style='color:red;'>Please enter valid email address.</p>").insertAfter('#guestEmail');
        } else{
            valid = true;
            /*var data = {"email":$('#guestEmail').val()};
            var url = '@php //echo url("/"); @endphp/address';
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: url,
                data: data,
                success: function (data) {
                    console.log(data);
                },
                dataType: 'json'
            });*/
            //window.location.href = '@php //echo url("/"); @endphp/address?'+$('#guestEmail').val();
            $('#add-address-modal').modal('show');
        }
        return valid;
    });

    $('form.shippingmethodForm button.continue-to-shipping').on('click', function () {
        $('form.shippingmethodForm .error').remove();
        $('form.create-acc .error').remove();
        var isgift = $('#pills-customer-info .shippingmethodForm input#giftcheck').is(':checked');
        var valid = true;
        var regName = /^[a-z ,.'-]+$/i;
        var regEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
        var regPhone = /^[+]?[0-9]+$/;
        //var postcode = /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i;
        if (!$('form.shippingmethodForm #shippingEmail').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.shippingmethodForm #shippingEmail');
        } else if (!$('form.shippingmethodForm #shippingFirstname').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.shippingmethodForm #shippingFirstname');
        } else if (!$('form.shippingmethodForm #shippingLastname').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.shippingmethodForm #shippingLastname');
        } else if (!$('form.shippingmethodForm #shippingAddress').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.shippingmethodForm #shippingAddress');
        } else if (!$('form.shippingmethodForm #shippingCity').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.shippingmethodForm #shippingCity');
        } /*else if (!$('form.shippingmethodForm #shippingState').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.shippingmethodForm #shippingState');
        } */else if (!$('form.shippingmethodForm #shippingPostCode').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.shippingmethodForm #shippingPostCode');
        } else if (!$('form.shippingmethodForm #shippingTelephone').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.shippingmethodForm #shippingTelephone');
        } else if ($('form.shippingmethodForm #shippingFirstname').val() && !regName.test($('form.shippingmethodForm #shippingFirstname').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter('form.shippingmethodForm #shippingFirstname');
        } else if ($('form.shippingmethodForm #shippingLastname').val() && !regName.test($('form.shippingmethodForm #shippingLastname').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter('form.shippingmethodForm #shippingLastname');
        } else if ($('form.shippingmethodForm #shippingCity').val() && !regName.test($('form.shippingmethodForm #shippingCity').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid city</p>').insertAfter('form.shippingmethodForm #shippingCity');
        } /*else if ($('form.shippingmethodForm #shippingState').val() && !regName.test($('form.shippingmethodForm #shippingState').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid state</p>').insertAfter('form.shippingmethodForm #shippingState');
        }*/ else if ($('form.shippingmethodForm #shippingEmail').val() && !regEmail.test($('form.shippingmethodForm #shippingEmail').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid email address</p>').insertAfter('form.shippingmethodForm #shippingEmail');
        } else if ($('form.shippingmethodForm #shippingTelephone').val() && !regPhone.test($('form.shippingmethodForm #shippingTelephone').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid Phone number</p>').insertAfter('form.shippingmethodForm #shippingTelephone');
        } else if (isgift == true && !$('form.shippingmethodForm #shippingReceiverName').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter($('form.shippingmethodForm #shippingReceiverName'));
        } else if (isgift == true && !$('form.shippingmethodForm #shippingReceiverEmail').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter($('form.shippingmethodForm #shippingReceiverEmail'));
        } else if (isgift == true && !$('form.shippingmethodForm #shippingReceiverMessage').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter($('form.shippingmethodForm #shippingReceiverMessage'));
        } else if (isgift == true && $('form.shippingmethodForm #shippingReceiverName').val() && !regName.test($('form.shippingmethodForm #shippingReceiverName').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter($('form.shippingmethodForm #shippingReceiverName'));
        } else if (isgift == true && $('form.shippingmethodForm #shippingReceiverEmail').val() && !regEmail.test($('form.shippingmethodForm #shippingReceiverEmail').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid email address</p>').insertAfter($('form.shippingmethodForm #shippingReceiverEmail'));
        } else {
            //$('div.main-loader').css('display','block');
            $('form.shippingmethodForm button.continue-to-shipping').addClass('disabled').attr('disabled','disabled');
            valid = true;
            var shippingEmail = $('form.shippingmethodForm #shippingEmail').val();
            var shippingFirstname = $('form.shippingmethodForm #shippingFirstname').val();
            var shippingLastname = $('form.shippingmethodForm #shippingLastname').val();
            var shippingAddress = $('form.shippingmethodForm #shippingAddress').val();
            var shippinngAddressline2 = $('form.shippingmethodForm #shippinngAddressline2').val();
            var shippingCity = $('form.shippingmethodForm #shippingCity').val();
            var shippingState = $('form.shippingmethodForm div.select div.select-styled').text();
            var shippingRegionId = $('form.shippingmethodForm #shippingState').find('option[data-title="' + shippingState + '"]').val();
            /*var shippingState = $('form.shippingmethodForm #shippingState').val();*/
            var shippingCountry = 'US'/*$('form.shippingmethodForm #country_id').val();*/
            /*var shippingCountry = $('form.shippingmethodForm div.select div.select-styled').text();
            var shippingCountry = $('form.shippingmethodForm #country_id').find('option[data-title="' + shippingCountry + '"]').val();*/
            var shippingPostCode = $('form.shippingmethodForm #shippingPostCode').val();
            var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
            var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
            var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
            var shippingTelephone = $('form.shippingmethodForm #shippingTelephone').val();
            if ($('input#samebilling').is(':checked')) {
                var samebilling = 1;
            } else {
                var samebilling = 0;
            }
                    @php if(array_key_exists("quote_id",$session)): @endphp
                    @php if($session['quote_id'] != '' ): @endphp
                    @php $quote_id = $session['quote_id']; @endphp
            var quote_id = '@php echo $quote_id @endphp';
            var data = {
                "quote_id": quote_id,
                "email": shippingEmail,
                "firstname": shippingFirstname,
                "lastname": shippingLastname,
                "address": shippingAddress,
                "addressline2": shippinngAddressline2,
                "city": shippingCity,
                "state": shippingState,
                "region_id": shippingRegionId,
                "country": shippingCountry,
                "postcode": shippingPostCode,
                "receivername": shippingReceiverName,
                "receiveremail": shippingReceiverEmail,
                "message": shippingReceiverMessage,
                "same_as_billing": samebilling,
                "telephone": shippingTelephone
            };
            console.log(data);
                    @php if(isset($_COOKIE["customer_token"])): @endphp
                    @php if(!key_exists('message',$customerData)): @endphp
            var url = '@php echo url('/').'/estimateshipping'; @endphp';
                    @php else: @endphp
            var url = '@php echo url('/').'/estimateguestshipping'; @endphp';
                    @php endif; @endphp
                    @php else: @endphp
            var url = '@php echo url('/').'/estimateguestshipping'; @endphp';
            @php endif; @endphp

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: url,
                data: data,
                success: function (data) {
                    console.log("newdata");
                    console.log(data);
                    $('form.shippingmethodForm button.continue-to-shipping').removeClass('disabled').removeAttr('disabled');
                    $('#pills-shipping-method .shipping_method tbody tr').remove();
                    var jsonData = JSON.parse(JSON.stringify(data));
                    for (var i = 0; i < jsonData.length; i++) {
                        var counter = jsonData[i];
                        if( i == 0){
                            if(counter['amount'] == '0'){
                                $('#pills-shipping-method .shipping_method tbody').append('<tr><td><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input checked="checked" name="shipping-method" type="radio" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label></td><td class="text-right text-muted">Free</td></tr>');
                            } else{
                                $('#pills-shipping-method .shipping_method tbody').append('<tr><td><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input checked="checked" name="shipping-method" type="radio" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label></td><td class="text-right text-muted">$' + counter['amount'] + '</td></tr>');
                            }
                        }else{
                            if(counter['amount'] == '0'){
                                $('#pills-shipping-method .shipping_method tbody').append('<tr><td><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input name="shipping-method" type="radio" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label></td><td class="text-right text-muted">Free</td></tr>');
                            } else{
                                $('#pills-shipping-method .shipping_method tbody').append('<tr><td><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input name="shipping-method" type="radio" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label></td><td class="text-right text-muted">$' + counter['amount'] + '</td></tr>');
                            }
                        }
                    }
                    var ship_cost = $('.shipping_method input[name=shipping-method]:checked').parent().parent().parent().find('td.text-right.text-muted').text();
                    if(ship_cost == 'Free'){
                        ship_cost = '$0';
                        ship_amount = 'Free';
                    }else{
                        ship_cost = $('.shipping_method input[name=shipping-method]:checked').parent().parent().parent().find('td.text-right.text-muted').text();
                        ship_amount = ship_cost;
                    }
                    var total = $('.right-sec .subtotal tr:first-child td.text-right').text();
                    var subtotal = total.replace('$', '');
                    var shippingCost = ship_cost.replace('$', '');
                    if (shippingCost === '') {
                        shippingCost = 0;
                    }
                    if ($('.subtotal table tr.discount td.text-right').text() != '' && $('.subtotal table tr.discount td.text-right').text() != '$0') {
                        var discount = $('.subtotal table tr.discount td.text-right').text();
                        var discount = discount.replace('$', '');
                        if (discount === '-') {
                            discount = 0;
                        }

                        var grandtotal = eval(subtotal) + eval(shippingCost) - eval(discount);
                        var grandtotal = grandtotal;
                    } else {
                        var grandtotal = eval(subtotal) + eval(shippingCost);
                    }
                    $('.right-sec .subtotal tr.shipping-total td.text-right').text(ship_amount);
                    $('.right-sec .total tr td.text-right h3 b').text('$'+(grandtotal).toFixed(2));
                    $('.right-sec .subtotal .shipping-total').css('display','table-row');
                    var address = '';
                    if ($(shippingAddress) != '') {
                        var address = shippingAddress;
                    }
                    if ($(shippinngAddressline2) != '') {
                        var address = address + ', ' + shippinngAddressline2;
                    }
                    if ($(shippingCity) != '') {
                        var address = address + ', ' + shippingCity;
                    }
                    if ($(shippingState) != '') {
                        var address = address + ', ' + shippingState;
                    }
                    if ($(shippingCountry) != '') {
                        var address = address + ', ' + shippingCountry;
                    }
                    if ($(shippingPostCode) != '') {
                        var address = address + ', ' + shippingPostCode;
                    }
                    $('.shipping-address-h6').text(address);
                    $('.main_top #pills-tab li.nav-item a').removeClass('active');
                    $('.main_top #pills-tab li:nth-child(3) a').removeClass('disabled').addClass('active');
                    $('#pills-tabContent #pills-customer-info').removeClass('show').removeClass('active');
                    $('#pills-tabContent #pills-shipping-method').addClass('show').addClass('active');
                    $("#pills-payment-method").removeClass("show").removeClass("active");
                    $('.tax').hide();


                    $('.shipping_method table tr td .custom-radio input').change(function () {
                        var shipping_cost = $(this).parent().parent().parent().find("td.text-right.text-muted").html();
                        if(shipping_cost == 'Free'){
                            shipping_cost = 'Free';
                            shipping_amount = '$0';
                        } else{
                            shipping_amount = $(this).parent().parent().parent().find("td.text-right.text-muted").html();
                            shipping_cost = shipping_amount;
                        }
                        $('.col-md-5.r-s .right-sec .subtotal tr.shipping-total td.text-right').text(shipping_cost);
                        var total = $('.right-sec div.subtotal table tr:first-child td.text-right').text();
                        var subtotal = total.replace('$', '');
                        var shippingCost = shipping_amount.replace('$', '');
                        if (shippingCost === '') {
                            shippingCost = 0;
                        }
                        if ($('.subtotal table tr.discount td.text-right').text() != '' && $('.subtotal table tr.discount td.text-right').text() != '$0') {
                            var discount = $('.subtotal table tr.discount td.text-right').text();
                            var discount = discount.replace('$', '');
                            if (discount === '-') {
                                discount = 0;
                            }
                            var grandtotal = eval(subtotal) + eval(shippingCost) - eval(discount);
                            var grandtotal = grandtotal;
                        } else {
                            var grandtotal = eval(subtotal) + eval(shippingCost);
                        }
                        $('.right-sec .total tr td.text-right h3 b').html('$' + (grandtotal).toFixed(2));
                    });
                    $('div.main-loader').css('display','none');
                },
                dataType: 'json'
            });
            @php endif; @endphp
            @php endif; @endphp
        }
        return valid;
    });
    // Click on the payment tab
    // $("#pills-payment-tab").click(function(){

    // });
    // Remove tax on the other tabs
    $("#pills-payment-tab, .edit-address, .edit-shipping, #pills-profile-tab, #pills-contact-tab, #recalculateprice").on('click',function(){
        var url = '@php echo url('/').'/cartinfo'; @endphp';
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            url: url,
            success: function (jsonData) {
                console.log(jsonData);
               
              subtotal=   jsonData['grand_total'];
              $('.right-sec .total tr td.text-right h3 b').text('$'+(subtotal).toFixed(2));
               discount_amount=   jsonData['base_discount_amount'];
                 tax_amount=   jsonData['base_tax_amount'];
                 ship_amount=  jsonData['base_shipping_amount'];
                 subtotal=   jsonData['base_subtotal'];
                grandtotal=   jsonData['base_grand_total'];
                if($("#pills-payment-tab").hasClass("active")){ 
                                $(".tax").show();
                            }else{
                                grandtotal =eval(grandtotal)-eval( jsonData['base_tax_amount']);
                                $(".tax").hide();
                            }
                 $('.right-sec .subtotal tr.discount td.text-right').text('$'+(Math.abs(discount_amount)).toFixed(2));
                 $('.right-sec .subtotal tr.tax td.text-right').text('$'+(tax_amount).toFixed(2));
                 $('.right-sec .subtotal tr:first td.text-right').text('$'+(subtotal).toFixed(2));
                $('.right-sec .subtotal tr.shipping-total td.text-right').text('$'+(ship_amount).toFixed(2));
               $('.right-sec .total tr td.text-right h3 b').text('$'+(grandtotal).toFixed(2));
                }
            }); 
            
    });
// Edit address    
    $('.edit-address').on('click', function () {
        $('.main_top #pills-tab li a').removeClass('active');
        $('.main_top #pills-tab li a#pills-profile-tab').removeClass('disabled').addClass('active');
        $('#pills-tabContent .tab-pane').removeClass('show').removeClass('active');
        $('#pills-tabContent #pills-customer-info').addClass('show').addClass('active');
    });
// Edit shipping address
    $('.edit-shipping').on('click', function () {
        $('.main_top #pills-tab li a').removeClass('active');
        $('.main_top #pills-tab li a#pills-contact-tab').removeClass('disabled').addClass('active');
        $('#pills-tabContent .tab-pane').removeClass('show').removeClass('active');
        $('#pills-tabContent #pills-shipping-method').addClass('show').addClass('active');
    });
    $('#pills-shipping-method .continue-to-payment').on('click', function () {
        $('#pills-shipping-method .error').remove();

        if($('form.shippingmethodForm #samebilling').is(':checked')){
            $('#pills-payment-method .billing_address .billing_kind .row .kind input').prop('checked', false);
            $('#pills-payment-method .billing_address .billing_kind .row .kind:nth-child(1) input').prop('checked', true);
            $('form.differnt_billing_address').css('display','none');
        } else{
            $('#pills-payment-method .billing_address .billing_kind .row .kind input').prop('checked', false);
            $('#pills-payment-method .billing_address .billing_kind .row .kind:nth-child(2) input').prop('checked', true);
            $('form.differnt_billing_address').css('display','block');
        }

        var valid = false;
        var shippingEmail = $('form.shippingmethodForm #shippingEmail').val();
        var shippingFirstname = $('form.shippingmethodForm #shippingFirstname').val();
        var shippingLastname = $('form.shippingmethodForm #shippingLastname').val();
        var shippingAddress = $('form.shippingmethodForm #shippingAddress').val();
        var shippinngAddressline2 = $('form.shippingmethodForm #shippinngAddressline2').val();
        var shippingCity = $('form.shippingmethodForm #shippingCity').val();

        var shippingState = $('form.shippingmethodForm div.select div.select-styled').text();
        var shippingRegionId = $('form.shippingmethodForm #shippingState').find('option[data-title="' + shippingState + '"]').val();

        var shippingCountry = 'US';

        var shippingPostCode = $('form.shippingmethodForm #shippingPostCode').val();
        var shippingTelephone = $('form.shippingmethodForm #shippingTelephone').val();
        var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
        var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
        var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();

        valid = true;
                @php if(array_key_exists("quote_id",$session)): @endphp
                @php if($session['quote_id'] != '' ): @endphp
                @php $quote_id = $session['quote_id']; @endphp
        var quote_id = '@php echo $quote_id @endphp';
        valid = true;
        var method_code = $('.shipping_method input[name=shipping-method]:checked').val();
        var career_code = $('.shipping_method input[name=shipping-method]:checked').val();
        var data = {
            "quote_id": quote_id,
            "email": shippingEmail,
            "firstname": shippingFirstname,
            "lastname": shippingLastname,
            "address": shippingAddress,
            "addressline2": shippinngAddressline2,
            "city": shippingCity,
            "state": shippingState,
            "region_id": shippingRegionId,
            "country": shippingCountry,
            "postcode": shippingPostCode,
            "receivername": shippingReceiverName,
            "receiveremail": shippingReceiverEmail,
            "message": shippingReceiverMessage,
            "shippingCarrierCode": career_code,
            "shippingMethodCode": method_code,
            "telephone": shippingTelephone
        };
        //$('div.main-loader').css('display','block');
        $('#pills-shipping-method .continue-to-payment').addClass('disabled').attr('disabled','disabled');
        var url = '@php echo url('/').'/shippinginformation'; @endphp';
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            url: url,
            data: data,
            success: function (data) {
                console.log(data);
                $('#pills-shipping-method .continue-to-payment').removeClass('disabled').removeAttr('disabled');
                var jsonData = JSON.parse(JSON.stringify(data));
                console.log(jsonData);
                /*$('#pills-payment-method .payment_method .credit_card_info.w-100').css({'display':'none'});
                $('#pills-payment-method .payment_method .credit_card_form').css({'display':'none'});*/
                var ship_cost = $('.shipping_method input[name=shipping-method]:checked').parent().parent().parent().find('td.text-right.text-muted').text();
                if(ship_cost == 'Free'){
                    ship_cost = '$0';
                    ship_amount = 'Free';
                } else{
                    ship_cost = $('.shipping_method input[name=shipping-method]:checked').parent().parent().parent().find('td.text-right.text-muted').text();
                    ship_amount = ship_cost;
                }
                var total = $('.right-sec .subtotal tr:first-child td.text-right').text();
                var subtotal = total.replace('$', '');
                var shippingCost = ship_cost.replace('$', '');
                if (shippingCost === '') {
                    shippingCost = 0;
                }
                if ($('.subtotal table tr.discount td.text-right').text() != '' && $('.subtotal table tr.discount td.text-right').text() != '$0') {
                    var discount = $('.subtotal table tr.discount td.text-right').text();
                    var discount = discount.replace('$', '');
                    if (discount === '-') {
                        discount = 0;
                    }

                    var grandtotal = eval(subtotal) + eval(shippingCost) - eval(discount);

                } else {

                    var grandtotal = eval(subtotal) + eval(shippingCost);
                }
                discount_amount=   jsonData['totals']['base_discount_amount'];
                tax_amount=   jsonData['totals']['base_tax_amount'];
                ship_amount=  jsonData['totals']['base_shipping_amount'];
                if(ship_amount==0.00){
                    ship_amount="Free"
                }else{
                    ship_amount=  '$'+(ship_amount).toFixed(2)
                }
                subtotal=   jsonData['totals']['base_subtotal'];
                grandtotal=   jsonData['totals']['base_grand_total'];
               
                $('.right-sec .subtotal tr.discount td.text-right').text('$'+(Math.abs(discount_amount)).toFixed(2));
                $('.right-sec .subtotal tr.tax td.text-right').text('$'+(tax_amount).toFixed(2));
                $('.right-sec .subtotal tr:first td.text-right').text('$'+(subtotal).toFixed(2));
                $('.right-sec .subtotal tr.shipping-total td.text-right').text(ship_amount);
                $('.right-sec .total tr td.text-right h3 b').text('$'+(grandtotal).toFixed(2));
                $('#pills-payment-method .payment_method table tbody tr').remove();
                if(tax_amount > 0){
                    console.log("show tax");
                    $(".tax").show();
                }
                for (var i = 0; i < jsonData['payment_methods'].length; i++) {
                    var counter = jsonData['payment_methods'][i];
                    $('#pills-payment-method .payment_method table tbody').append('<tr><td><label class="custom-radio">' + counter['title'] + '<input name="payment-method" checked="checked" value="' + counter['code'] + '" type="radio" methodlabel="' + counter['title'] + '" required><span class="checkmark"></span></label></td></tr>');
                }
                var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                var address = '';
                if ($(shippingAddress) != '') {
                    var address = shippingAddress;
                }
                if ($(shippinngAddressline2) != '') {
                    var address = address + ', ' + shippinngAddressline2;
                }
                if ($(shippingCity) != '') {
                    var address = address + ', ' + shippingCity;
                }
                if ($(shippingState) != '') {
                    var address = address + ', ' + shippingState;
                }
                if ($(shippingCountry) != '') {
                    var address = address + ', ' + shippingCountry;
                }
                if ($(shippingPostCode) != '') {
                    var address = address + ', ' + shippingPostCode;
                }
                $('.shipping-address-h6').text(address);
                $('#pills-payment-method .shipping_address table tr:nth-child(2) td:nth-child(2)').html(shipingMethodlabel);
                $('.main_top #pills-tab li.nav-item a').removeClass('active');
                @php if(isset($_COOKIE["customer_token"])): @endphp
                @php if(!key_exists('message',$customerData)): @endphp
                $('.main_top #pills-tab li:nth-child(4) a').removeClass('disabled').addClass('active');
                $('#pills-tabContent #pills-shipping-method').removeClass('show').removeClass('active');
                $('#pills-tabContent #pills-payment-method').addClass('show').addClass('active');
                @php else: @endphp
                $('.main_top #pills-tab li:nth-child(4) a').removeClass('disabled').addClass('active');
                $('#pills-tabContent #pills-shipping-method').removeClass('show').removeClass('active');
                $('#pills-tabContent #pills-payment-method').addClass('show').addClass('active');
                @php endif; @endphp
                @php else: @endphp
                $('.main_top #pills-tab li:nth-child(4) a').removeClass('disabled').addClass('active');
                $('#pills-tabContent #pills-shipping-method').removeClass('show').removeClass('active');
                $('#pills-tabContent #pills-payment-method').addClass('show').addClass('active');
                @php endif; @endphp
                $('div.main-loader').css('display','none');

            },
            dataType: 'json'
        });
        @php endif; @endphp
        @php endif; @endphp
            return valid;
    });
    $("#pills-payment-method .billing_address .billing_kind input[name=billingstatus]").on('change', function () {
        if ($(this).val() == 'different') {
            $('form.differnt_billing_address').css({'display': 'block'});
        } else {
            $('form.differnt_billing_address').css({'display': 'none'});
        }
    });

    $(document).on('change','form.shippingmethodForm #samebilling',function(){
        if($('form.shippingmethodForm #samebilling').is(':checked')){
            $('#pills-payment-method .billing_address .billing_kind .row .kind input').prop('checked', false);
            $('#pills-payment-method .billing_address .billing_kind .row .kind:nth-child(1) input').prop('checked', true);
            $('form.differnt_billing_address').css('display','none');
        } else{
            $('#pills-payment-method .billing_address .billing_kind .row .kind input').prop('checked', false);
            $('#pills-payment-method .billing_address .billing_kind .row .kind:nth-child(2) input').prop('checked', true);
            $('form.differnt_billing_address').css('display','block');
        }
    });

    $(document).on('change','#pills-payment-method .payment_method table tbody tr td input[name=payment-method]',function () {
        var paymentmethod = $('#pills-payment-method .payment_method input[name=payment-method]:checked').val();
        if (paymentmethod == 'pmclain_stripe') {
            $('#pills-payment-method form.cc-method').css({'display': 'block'});
        } else {
            $('#pills-payment-method form.cc-method').css({'display': 'none'});
        }
    });
    $('#pills-payment-method .billing_address button.placeorder').on('click', function () {
        $('#pills-payment-method .error').remove();
        $('form.differnt_billing_address .error').remove();
        bill_firstname = $('form.differnt_billing_address #billingFirstname').val();
        bill_lastname = $('form.differnt_billing_address #billingLastname').val();
        bill_address = $('form.differnt_billing_address #billingAddress').val();
        bill_city = $('form.differnt_billing_address #billingCity').val();
        bill_postcode = $('form.differnt_billing_address #billingPostCode').val();
        bill_telephone = $('form.differnt_billing_address #billingTelephone').val();
        var valid ;
        var isPaymentChecked = $(".payment_method table tr td input[name=payment-method]").is(':checked');
        var billingChecked = $("#pills-payment-method .billing_address .billing_kind input[name=billingstatus]").is(':checked');
        var billingvalue = $("#pills-payment-method .billing_address .billing_kind input[name=billingstatus]:checked").val();
        var payment_method = $(".payment_method table tr td input[name=payment-method]:checked").val();

        var cc_number = $('form.cc-method input#cc-number').val();
        var cc_exp_month = $('form.cc-method input#cc-exp-month').val();
        var cc_exp_year = $('form.cc-method input#cc-exp-year').val();
        var cc_cvv = $('form.cc-method input#cc-cvv').val();

        if (isPaymentChecked == false) {
            valid = false;
            $('<p class="error" style="color:red;">This is required field.</p>').insertAfter('#pills-payment-method .payment_method table');
        } if ( bill_firstname == '' && billingChecked == true && billingvalue == 'different') {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingFirstname');
        } if ( bill_lastname == '' && billingChecked == true && billingvalue == 'different') {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingLastname');
        } if ( bill_address == '' && billingChecked == true && billingvalue == 'different') {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingAddress');
        } if ( bill_city == '' && billingChecked == true && billingvalue == 'different') {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingCity');
        } if ( bill_postcode == '' && billingChecked == true && billingvalue == 'different') {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingPostCode');
        } if ( bill_telephone == '' && billingChecked == true && billingvalue == 'different') {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingTelephone');
        } if(isPaymentChecked == true && payment_method == 'pmclain_stripe' && cc_number == ''){
            valid = false;
            $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method input#cc-number');
        } if(isPaymentChecked == true && payment_method == 'pmclain_stripe' && cc_exp_month == ''){
            valid = false;
            $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method input#cc-exp-month');
        } if(isPaymentChecked == true && payment_method == 'pmclain_stripe' && cc_exp_year == ''){
            valid = false;
            $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method input#cc-exp-year');
        } if(isPaymentChecked == true && payment_method == 'pmclain_stripe' && cc_cvv == ''){
            valid = false;
            $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method input#cc-cvv');
        } if(( bill_firstname != '' && bill_lastname != '' && bill_address != '' && bill_city != '' && bill_postcode != '' && bill_telephone != '' && billingChecked == true && billingvalue == 'different' && cc_number != '' && cc_exp_month != '' && cc_exp_year != '' && cc_cvv != '') || (isPaymentChecked == true && billingChecked == true && billingvalue == 'same' && cc_number != '' && cc_exp_month != '' && cc_exp_year != '' && cc_cvv != '')) {
            if( $(".payment_method table tr td input[name=payment-method]:checked").val() == 'pmclain_stripe'){
                var cc_number = $('form.cc-method input#cc-number').val();
                var cc_exp_month = $('form.cc-method input#cc-exp-month').val();
                var cc_exp_year = $('form.cc-method input#cc-exp-year').val();
                var cc_cvv = $('form.cc-method input#cc-cvv').val();
                var cc_noc = $('form.cc-method input#cc-nameOncard').val();
                var curr_month = new Date().getMonth()+1;
                var curr_year = new Date().getFullYear().toString().substr(-2);

                if(cc_number == ''){
                    valid = false;
                    $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method input#cc-number');
                } if(cc_exp_month == '' || cc_exp_month == undefined){
                    valid = false;
                    $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method input#cc-exp-month');
                } if(cc_exp_year == '' || cc_exp_year == undefined){
                    valid = false;
                    $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method input#cc-exp-year');
                } if(cc_cvv == ''){
                    valid = false;
                    $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method input#cc-cvv');
                } else{
                    valid = true;
                    $('form.cc-method .error').remove();
                    @php if(array_key_exists("quote_id",$session)): @endphp
                            @php if($session['quote_id'] != '' ): @endphp
                            @php if(!is_object($session['quote_id'])): @endphp
                            @php $quote_id = $session['quote_id']; @endphp
                        data = {'card_no':cc_number,'ccExpiryMonth':cc_exp_month,'ccExpiryYear':cc_exp_year,'cvvNumber':cc_cvv,'quote_id':'@php echo $quote_id; @endphp'};
                    //$('div.main-loader').css('display','block');
                    $('#pills-payment-method .billing_address button.placeorder').addClass('disabled').attr('disabled','disabled');
                    var url = '@php echo url('/'); @endphp/addmoney/stripe';
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: "POST",
                        url: url,
                        data: data,
                        success: function (data) {
                            if(data.result == 'success'){
                                localStorage.setItem('stripe_token', data.token);
                                var cc_type = detectCardType(cc_number);
                                var cc_month = cc_exp_month;
                                var cc_year = cc_exp_year;
                                var las4 = cc_number.substr(cc_number.length - 4);
                                $('#pills-payment-method .billing_address button.placeorder').addClass('disabled').attr('disabled','disabled');
                                var billingvalue = $("#pills-payment-method .billing_address .billing_kind input[name=billingstatus]:checked").val();
                                @php if(array_key_exists("quote_id",$session)): @endphp
                                @php if($session['quote_id'] != '' ): @endphp
                                @php $quote_id = $session['quote_id']; @endphp
                                @php if(!is_object($quote_id)): @endphp
                                <?php if(isset($session['customer_token'])){
                                    $url = createquote().''.$quote_id;
                                } else{
                                    $url = createquoteguest().''.$quote_id;
                                }?>
                                @php $cartdata = m2ApiCall($url,'get',''); @endphp

                                @php if(array_key_exists('items',$cartdata)): @endphp
                                @foreach($cartdata['items'] as $data)
                                @if($data['sku'] == 'Gift_Card-$50' || $data['sku'] == 'Gift_Card-$55' || $data['sku'] == 'Gift_Card-$60' || $data['sku'] == 'Gift_Card-$70' || $data['sku'] == 'Gift_Card-$75' || $data['sku'] == 'Gift_Card-$80' || $data['sku'] == 'Gift_Card-$90' || $data['sku'] == 'Gift_Card-$100' || $data['sku'] == 'Gift_Card-$75' || $data['sku'] == 'Gift_Card-$150' || $data['sku'] == 'Gift_Card-$200')
                                @php $size = 8; @endphp
                                @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp

                                $.ajax({
                                    url:'@php echo url('/').'/simpleProductData/'.$data['sku']; @endphp',
                                    method: 'GET',
                                    dataType:'json',
                                    success: function(coupon_data){
                                        console.log(coupon_data);
                                        for (var i = 0; i < coupon_data.custom_attributes.length; i++) {
                                            if(coupon_data.custom_attributes[i].attribute_code == 'gift_card'){
                                                if(coupon_data.custom_attributes[i].value == '1'){
                                                    var is_giftcard = '1';
                                                }else{
                                                    var is_giftcard = '0';
                                                }
                                            } if(coupon_data.custom_attributes[i].attribute_code == 'gift_card_rule_id'){
                                                if(coupon_data.custom_attributes[i].value != ''){
                                                    var rule_id = coupon_data.custom_attributes[i].value;
                                                    var giftcard_price = coupon_data.price;
                                                }else{
                                                    var rule_id = '';
                                                    var giftcard_price = coupon_data.price;
                                                }
                                            }
                                        }
                                        //console.log(' is gift '+is_giftcard +' - rule_id -'+ rule_id + ' - giftcard_price '+giftcard_price);
                                        if(is_giftcard == '1' && rule_id != ''){
                                            coupon_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':rule_id,'type':'1'};
                                            $.ajax({
                                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                method: 'POST',
                                                dataType:'json',
                                                data: coupon_data,
                                                success: function(coupon_data){
                                                    var generated_coupon_code = coupon_data.code;
                                                    localStorage.setItem("generated_coupon_code", generated_coupon_code);
                                                    localStorage.setItem("giftcard_price", giftcard_price);
                                                }
                                            });
                                        }
                                    }
                                });
                                @endif
                                        @endforeach
                                        @php endif; @endphp
                                        @php endif; @endphp
                                        @php endif; @endphp
                                        @php endif; @endphp
                                if (billingvalue == 'same') {
                                    var shippingEmail = $('form.shippingmethodForm #shippingEmail').val();
                                    var shippingFirstname = $('form.shippingmethodForm #shippingFirstname').val();
                                    var shippingLastname = $('form.shippingmethodForm #shippingLastname').val();
                                    var shippingAddress = $('form.shippingmethodForm #shippingAddress').val();
                                    var shippinngAddressline2 = $('form.shippingmethodForm #shippinngAddressline2').val();
                                    var shippingCity = $('form.shippingmethodForm #shippingCity').val();
                                    var shippingState = $('form.shippingmethodForm div.select div.select-styled').text();
                                    var shippingRegionId = $('form.shippingmethodForm #shippingState').find('option[data-title="' + shippingState + '"]').val();
                                    /*var shippingState = $('form.shippingmethodForm #shippingState').val();*/
                                    var shippingCountry = 'US'/*$('form.shippingmethodForm #country_id').val();*/
                                    /*var shippingCountry = $('form.shippingmethodForm div.select div.select-styled').text();
                                    var shippingCountry = $('form.shippingmethodForm #country_id').find('option[data-title="' + shippingCountry + '"]').val();*/
                                    var shippingPostCode = $('form.shippingmethodForm #shippingPostCode').val();
                                    var shippingTelephone = $('form.shippingmethodForm #shippingTelephone').val();
                                    var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                    var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                    var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                    var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                    var oldaddressId = $('form.shippingmethodForm #address_id').val();
                                            @php if(array_key_exists("quote_id",$session)): @endphp
                                            @php if($session['quote_id'] != '' ): @endphp
                                            @php $quote_id = $session['quote_id']; @endphp
                                    var quote_id = '@php echo $quote_id @endphp';
                                    var paymentmethod = $('#pills-payment-method .payment_method input[name=payment-method]:checked').val();
                                    var paymentmethodlabel = $('#pills-payment-method .payment_method input[name=payment-method]:checked').attr('methodlabel');

                                    var subtotal = $('.right-sec .subtotal tr:first-child td.text-right').html();
                                    var taxtotal = $('.right-sec .subtotal tr.tax td.text-right').html();
                                    var total = $('.right-sec .total tr td.text-right h3 b').html();
                                    var shippingcost = $('.right-sec .subtotal tr.shipping-total td.text-right').html();
                                    var stripe_token = localStorage.getItem("stripe_token");
                                    var data = {
                                        "quote_id": quote_id,
                                        "email": shippingEmail,
                                        "firstname": shippingFirstname,
                                        "lastname": shippingLastname,
                                        "address": shippingAddress,
                                        "addressline2": shippinngAddressline2,
                                        "city": shippingCity,
                                        "state": shippingState,
                                        "region_id":shippingRegionId,
                                        "country": shippingCountry,
                                        "postcode": shippingPostCode,
                                        "telephone": shippingTelephone,
                                        "receivername": shippingReceiverName,
                                        "receiveremail": shippingReceiverEmail,
                                        "message": shippingReceiverMessage,
                                        "paymentmethod": paymentmethod,
                                        "shippingmethod": shipingMethodlabel,
                                        "paymethodlabel": paymentmethodlabel,
                                        "subtotal": subtotal,
                                        "taxtotal":taxtotal,
                                        "shippingrate": shippingcost,
                                        "total": total,
                                        "cc_type":cc_type,
                                        "cc_exp_year": cc_year,
                                        "cc_exp_month":cc_month,
                                        "cc_last4":las4,
                                        "cc_token":stripe_token,
                                        "shippingFirstname":shippingFirstname,
                                        "shippingLastname":shippingLastname,
                                        "shippingaddress":shippingAddress,
                                        "shippingaddressline2":shippinngAddressline2,
                                        "shippingcity":shippingCity,
                                        "shippingstate":shippingState,
                                        "shippingpostcode":shippingPostCode,
                                        "shippingcounrty":shippingCountry,
                                        "shippingtelephone":shippingTelephone
                                    };
                                    console.log(data);
                                            @php if(isset($_COOKIE["customer_token"])): @endphp
                                            @php if(!key_exists('message',$customerData)): @endphp
                                    var url = '@php echo url('/').'/customerplaceorder'; @endphp';
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        type: "POST",
                                        url: url,
                                        data: data,
                                        success: function (data) {
                                            $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                                            if (data['message']) {
                                                $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                                            } else {
                                                localStorage.setItem('applied_coupon', '');
                                                shippingState = $('form.shippingmethodForm div.select div.select-styled').text();
                                                if($('input#saveaddress').prop('checked')){
                                                    save_address = 1;
                                                } else{
                                                    save_address = 0;
                                                }
                                                $('#order-success-step').css({'display': 'block'});
                                                $('#steps-of-checkout').css({'display': 'none'});
                                                $('#order-success-step #customer-firstname').html('').text("Thank you " + $('.shippingmethodForm #shippingFirstname').val() + " for your purchase!");
                                                $('#order-success-step #customer-email').html('').text("A confirmation email has been sent to " + $('.shippingmethodForm #shippingEmail').val());
                                                $('#order-success-step #customer-orderId').html('').text("Order ID: " + data);
                                                $('#order-success-step #order-shipping-address').html('').append("<li>Shipping Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode + "  " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                                $('#order-success-step #order-billing-address').html('').append("<li>Billing Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode + "  " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                                var shipingMethod = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                                var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                                $('#order-success-step #order-shipping-method').html('').html('<li>Shipping Method</li><li>' + shipingMethodlabel + '</li>');
                                                $('#order-success-step #order-payment-method').html('').html('<li>Payment Method</li>' + paymentmethodlabel + '</li>');
                                                $('.right-sec .promo_code').css({'display': 'none'});
                                                $('.right-sec .remove.remove-item').css({'display':'none'});
                                                $('.right-sec .form-group input.form-control.mb-0').css({'display':'none'});
                                                $('.right-sec .col-md-9 h6:nth-child(3)').addClass('price');
                                                $('.col-md-9.col-9 .form-group .quantity-select.cartitemqty').css('display','none');
                                                $('.col-md-9.col-9 .form-group .up-counter').css('display','none');
                                                $('.col-md-9.col-9 .form-group .down-counter').css('display','none');
                                                jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                                if(save_address == 1){
                                                    var shippingRegionId = $('form.shippingmethodForm #shippingState').find('option[data-title="' + shippingState + '"]').val();
                                                    if(shippinngAddressline2 != undefined){
                                                        data = {'firstname':shippingFirstname,'lastname':shippingLastname,'streetline1':shippingAddress,'streetline2':shippinngAddressline2,'telephone':shippingTelephone,'city':shippingCity,'state':shippingState,'region_id':shippingRegionId,'postcode':shippingPostCode,'country':shippingCountry,'isshipping':true,'customerfirstname':'@php echo $customerData['firstname']; @endphp','customerlastname':'@php echo $customerData['lastname']; @endphp','customeremail':'@php echo $customerData['email']; @endphp'};
                                                    } else{
                                                        data = {'firstname':shippingFirstname,'lastname':shippingLastname,'streetline1':shippingAddress,'streetline2':'','telephone':shippingTelephone,'city':shippingCity,'state':shippingState,'postcode':shippingPostCode,'country':shippingCountry,'isshipping':true,'customerfirstname':'@php echo $customerData['firstname']; @endphp','customerlastname':'@php echo $customerData['lastname']; @endphp','customeremail':'@php echo $customerData['email']; @endphp'};
                                                    }
                                                    valid = true;
                                                    $.ajax({
                                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                        type:'POST',
                                                        data:data,
                                                        url :'@php echo url('/')."/saveaddress"; @endphp',
                                                        success: function (saveaddressdata) {
                                                            console.log(saveaddressdata);
                                                        },
                                                        dataType: 'json'
                                                    });
                                                }
                                                var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                                if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                                    @php $size = 8; @endphp
                                                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                        referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                                    $.ajax({
                                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                        url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                        method: 'POST',
                                                        dataType:'json',
                                                        data: referral_data,
                                                        success: function(coupon_data){
                                                            var referral_back_generated_code = coupon_data.code;
                                                            referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email, 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                                            $.ajax({
                                                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                                url:'@php echo url('/').'/referralback'; @endphp',
                                                                method: 'POST',
                                                                dataType:'json',
                                                                data: referralBack_data,
                                                                success: function(coupon_data){

                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                                var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                                var giftcard_price = localStorage.getItem("giftcard_price");
                                                if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                                    senderemail = $('.shippingmethodForm #shippingEmail').val();
                                                    sendername =  shippingFirstname+ ' ' + shippingLastname;
                                                    var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                                    var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                                    var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                                    var gft_price = '$'+giftcard_price;
                                                    couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                                    $.ajax({
                                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                        url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                                        method : 'POST',
                                                        data: couponcode_data,
                                                        success: function(couponcode_data){
                                                            console.log(couponcode_data);
                                                        }
                                                    });
                                                }
                                                @php $size = 8; @endphp
                                                        @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                    referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'1','type':'1'};
                                                $.ajax({
                                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                    url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                    method: 'POST',
                                                    dataType:'json',
                                                    data: referral_data,
                                                    success: function(coupon_data){
                                                        var generated_referral_code = coupon_data.code;
                                                        localStorage.setItem("generated_referral_code", generated_referral_code);
                                                    }
                                                });
                                                setTimeout(function() {
                                                    $('#refer-friend').modal('show');
                                                }, 3000);
                                            }
                                            $('div.main-loader').css('display','none');
                                        },
                                        dataType: 'json'
                                    });
                                            @php else: @endphp
                                    var url = '@php echo url('/').'/placeorder'; @endphp';
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        type: "POST",
                                        url: url,
                                        data: data,
                                        success: function (data) {
                                            $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                                            console.log(data);
                                            if (data['message']) {
                                                $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                                            } else {
                                                localStorage.setItem('applied_coupon', '');
                                                $('#order-success-step').css({'display': 'block'});
                                                $('#steps-of-checkout').css({'display': 'none'});
                                                $('#order-success-step #customer-firstname').html('').text("Thank you " + $('.shippingmethodForm #shippingFirstname').val() + " for your purchase!");
                                                $('#order-success-step #customer-email').html('').text("A confirmation email has been sent to " + $('.shippingmethodForm #shippingEmail').val());
                                                $('#order-success-step #customer-orderId').html('').text("Order ID: " + data);
                                                $('#order-success-step #order-shipping-address').html('').append("<li>Shipping Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode + " " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                                $('#order-success-step #order-billing-address').html('').append("<li>Billing Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode + "  " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                                var shipingMethod = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                                var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                                $('#order-success-step #order-shipping-method').html('').append('<li>Shipping Method</li><li>' + shipingMethodlabel + '</li>');
                                                $('#order-success-step #order-payment-method').html('').append('<li>Payment Method</li>' + paymentmethodlabel + '</li>');
                                                $('.right-sec .promo_code').css({'display': 'none'});
                                                $('.right-sec .remove.remove-item').css({'display':'none'});
                                                $('.right-sec .form-group input.form-control.mb-0').css({'display':'none'});
                                                $('.right-sec .col-md-9 h6:nth-child(3)').addClass('price');
                                                $('.col-md-9.col-9 .form-group .quantity-select.cartitemqty').css('display','none');
                                                $('.col-md-9.col-9 .form-group .up-counter').css('display','none');
                                                $('.col-md-9.col-9 .form-group .down-counter').css('display','none');
                                                jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                                var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                                if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                                    @php $size = 8; @endphp
                                                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                        referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                                    $.ajax({
                                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                        url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                        method: 'POST',
                                                        dataType:'json',
                                                        data: referral_data,
                                                        success: function(coupon_data){
                                                            var referral_back_generated_code = coupon_data.code;
                                                            referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email , 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                                            $.ajax({
                                                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                                url:'@php echo url('/').'/referralback'; @endphp',
                                                                method: 'POST',
                                                                dataType:'json',
                                                                data: referralBack_data,
                                                                success: function(coupon_data){

                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                                var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                                var giftcard_price = localStorage.getItem("giftcard_price");
                                                if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                                    senderemail = $('.shippingmethodForm #shippingEmail').val();
                                                    sendername =  shippingFirstname+ ' ' + shippingLastname;
                                                    var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                                    var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                                    var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                                    var gft_price = '$'+giftcard_price;
                                                    couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                                    $.ajax({
                                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                        url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                                        method : 'POST',
                                                        data: couponcode_data,
                                                        success: function(couponcode_data){
                                                            console.log(couponcode_data);
                                                        }
                                                    })
                                                }
                                            }
                                            @php $size = 8; @endphp
                                                    @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'1','type':'1'};
                                            $.ajax({
                                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                method: 'POST',
                                                dataType:'json',
                                                data: referral_data,
                                                success: function(coupon_data){
                                                    var generated_referral_code = coupon_data.code;
                                                    localStorage.setItem("generated_referral_code", generated_referral_code);
                                                }
                                            });
                                            /*setTimeout(function() {
                                                $('#refer-friend').modal('show');
                                            }, 3000);*/
                                            $('div.main-loader').css('display','none');
                                        },
                                        dataType: 'json'
                                    });
                                            @php endif; @endphp
                                            @php else: @endphp
                                    var url = '@php echo url('/').'/placeorder'; @endphp';
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        type: "POST",
                                        url: url,
                                        data: data,
                                        success: function (data) {
                                            $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                                            console.log(data);
                                            if (data['message']) {
                                                $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                                            } else {
                                                localStorage.setItem('applied_coupon', '');
                                                $('#order-success-step').css({'display': 'block'});
                                                $('#steps-of-checkout').css({'display': 'none'});
                                                $('#order-success-step #customer-firstname').html('').text("Thank you " + $('.shippingmethodForm #shippingFirstname').val() + " for your purchase!");
                                                $('#order-success-step #customer-email').html('').text("A confirmation email has been sent to " + $('.shippingmethodForm #shippingEmail').val());
                                                $('#order-success-step #customer-orderId').html('').text("Order ID: " + data);
                                                $('#order-success-step #order-shipping-address').html('').append("<li>Shipping Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " +  shippingState + "</li><li>" + shippingPostCode + "  " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                                $('#order-success-step #order-billing-address').html('').append("<li>Billing Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode + " " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                                var shipingMethod = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                                var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                                $('#order-success-step #order-shipping-method').html('').append('<li>Shipping Method</li><li>' + shipingMethodlabel + '</li>');
                                                $('#order-success-step #order-payment-method').html('').append('<li>Payment Method</li>' + paymentmethodlabel + '</li>');
                                                $('.right-sec .promo_code').css({'display': 'none'});
                                                $('.right-sec .remove.remove-item').css({'display':'none'});
                                                $('.right-sec .form-group input.form-control.mb-0').css({'display':'none'});
                                                $('.right-sec .col-md-9 h6:nth-child(3)').addClass('price');
                                                $('.col-md-9.col-9 .form-group .quantity-select.cartitemqty').css('display','none');
                                                $('.col-md-9.col-9 .form-group .up-counter').css('display','none');
                                                $('.col-md-9.col-9 .form-group .down-counter').css('display','none');
                                                jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                                var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                                if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                                    @php $size = 8; @endphp
                                                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                        referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                                    $.ajax({
                                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                        url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                        method: 'POST',
                                                        dataType:'json',
                                                        data: referral_data,
                                                        success: function(coupon_data){
                                                            var referral_back_generated_code = coupon_data.code;
                                                            referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email, 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                                            $.ajax({
                                                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                                url:'@php echo url('/').'/referralback'; @endphp',
                                                                method: 'POST',
                                                                dataType:'json',
                                                                data: referralBack_data,
                                                                success: function(coupon_data){

                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                                var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                                var giftcard_price = localStorage.getItem("giftcard_price");
                                                if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                                    senderemail = $('.shippingmethodForm #shippingEmail').val();
                                                    sendername =  shippingFirstname+ ' ' + shippingLastname;
                                                    var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                                    var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                                    var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                                    var gft_price = '$'+giftcard_price;
                                                    couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                                    $.ajax({
                                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                        url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                                        method : 'POST',
                                                        data: couponcode_data,
                                                        success: function(couponcode_data){
                                                            console.log(couponcode_data);
                                                        }
                                                    })
                                                }
                                                @php $size = 8; @endphp
                                                        @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                    referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'1','type':'1'};
                                                $.ajax({
                                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                    url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                    method: 'POST',
                                                    dataType:'json',
                                                    data: referral_data,
                                                    success: function(coupon_data){
                                                        var generated_referral_code = coupon_data.code;
                                                        localStorage.setItem("generated_referral_code", generated_referral_code);
                                                    }
                                                });
                                                /*setTimeout(function() {
                                                    $('#refer-friend').modal('show');
                                                }, 3000);*/
                                            }
                                            $('div.main-loader').css('display','none');
                                        },
                                        dataType: 'json'
                                    });
                                    @php endif; @endphp

                                    @php endif; @endphp
                                    @php endif; @endphp
                                } else {
                                    if (!$('form.differnt_billing_address #billingFirstname').val()) {
                                        valid = false;
                                        $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingFirstname');
                                    } else if (!$('form.differnt_billing_address #billingLastname').val()) {
                                        valid = false;
                                        $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingLastname');
                                    } else if (!$('form.differnt_billing_address #billingAddress').val()) {
                                        valid = false;
                                        $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingAddress');
                                    } else if (!$('form.differnt_billing_address #billingCity').val()) {
                                        valid = false;
                                        $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingCity');
                                    } else if (!$('form.differnt_billing_address #billingPostCode').val()) {
                                        valid = false;
                                        $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingPostCode');
                                    } else {
                                        valid = true;
                                        var shippingEmail = $('form.shippingmethodForm #shippingEmail').val();
                                        var shippingFirstname = $('form.shippingmethodForm #shippingFirstname').val();
                                        var shippingLastname = $('form.shippingmethodForm #shippingLastname').val();
                                        var shippingAddress = $('form.shippingmethodForm #shippingAddress').val();
                                        var shippinngAddressline2 = $('form.shippingmethodForm #shippinngAddressline2').val();
                                        var shippingCity = $('form.shippingmethodForm #shippingCity').val();
                                        var shippingState = $('form.shippingmethodForm div.select div.select-styled').text();
                                        var shippingRegionId = $('form.shippingmethodForm #shippingState').find('option[data-title="' + shippingState + '"]').val();
                                        /*var shippingState = $('form.shippingmethodForm #shippingState').val();*/
                                        var shippingCountry = 'US'/*$('form.shippingmethodForm #country_id').val();*/
                                        /*var shippingCountry = $('form.shippingmethodForm div.select div.select-styled').text();
                                        var shippingCountry = $('form.shippingmethodForm #country_id').find('option[data-title="' + shippingCountry + '"]').val();*/
                                        var shippingPostCode = $('form.shippingmethodForm #shippingPostCode').val();
                                        var shippingTelephone = $('form.shippingmethodForm #shippingTelephone').val();
                                        var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                        var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                        var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();

                                        var billingFirstname = $('form.differnt_billing_address #billingFirstname').val();
                                        var billingLastname = $('form.differnt_billing_address #billingLastname').val();
                                        var billingAddress = $('form.differnt_billing_address #billingAddress').val();
                                        var billingAddressLine2 = $('form.differnt_billing_address #billingAddressline2').val();
                                        var billingCity = $('form.differnt_billing_address #billingCity').val();
                                        /*var billingState = $('form.differnt_billing_address #billingState').val();*/
                                        var billingState = $('form.differnt_billing_address div.select div.select-styled').text();
                                        var billingRegionId = $('form.differnt_billing_address #shippingState').find('option[data-title="' + billingState + '"]').val();
                                        /*var shippingState = $('form.shippingmethodForm #shippingState').val();*/
                                        var billingCountry = 'US'/*$('form.shippingmethodForm #country_id').val();*/
                                        /*var shippingCountry = $('form.shippingmethodForm div.select div.select-styled').text();
                                        var shippingCountry = $('form.shippingmethodForm #country_id').find('option[data-title="' + shippingCountry + '"]').val();*/
                                        var billingPostCode = $('form.differnt_billing_address #billingPostCode').val();
                                        var billingTelephone = $('form.differnt_billing_address #billingTelephone').val();
                                        var billingCountrytitle = $('form.differnt_billing_address div.select div.select-styled').text();
                                        //var billingCountry = $('form.differnt_billing_address #country_id').find('option[data-title="' + billingCountrytitle + '"]').val();
                                        var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').val();

                                                @php if(array_key_exists("quote_id",$session)): @endphp
                                                @php if($session['quote_id'] != '' ): @endphp
                                                @php $quote_id = $session['quote_id']; @endphp
                                        var quote_id = '@php echo $quote_id @endphp';
                                        var paymentmethod = $('#pills-payment-method .payment_method input[name=payment-method]:checked').val();
                                        var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                        var paymentmethodlabel = $('#pills-payment-method .payment_method input[name=payment-method]:checked').attr('methodlabel');
                                        var subtotal = $('.right-sec .subtotal tr:first-child td.text-right').html();
                                        var taxtotal = $('.right-sec .subtotal tr.tax td.text-right').html();
                                        var total = $('.right-sec .total tr td.text-right h3 b').html();
                                        var shippingcost = $('.right-sec .subtotal tr.shipping-total td.text-right').html();
                                        var stripe_token = localStorage.getItem("stripe_token");
                                        var data = {
                                            "quote_id": quote_id,
                                            "email": shippingEmail,
                                            "firstname": billingFirstname,
                                            "lastname": billingLastname,
                                            "address": billingAddress,
                                            "addressline2": billingAddressLine2,
                                            "city": billingCity,
                                            "state": billingState,
                                            "region_id": billingRegionId,
                                            "country": billingCountry,
                                            "postcode": billingPostCode,
                                            "telephone": billingTelephone,
                                            "receivername": shippingReceiverName,
                                            "receiveremail": shippingReceiverEmail,
                                            "message": shippingReceiverMessage,
                                            "receivername": shippingReceiverName,
                                            "receiveremail": shippingReceiverEmail,
                                            "message": shippingReceiverMessage,
                                            "shippingmethod": shipingMethodlabel,
                                            "paymentmethod": paymentmethod,
                                            "shippingmethod": shipingMethodlabel,
                                            "paymethodlabel": paymentmethodlabel,
                                            "subtotal": subtotal,
                                            "taxtotal": taxtotal,
                                            "shippingrate": shippingcost,
                                            "total": total,
                                            "cc_type":cc_type,
                                            "cc_exp_year": cc_year,
                                            "cc_exp_month":cc_month,
                                            "cc_last4":las4,
                                            "cc_token":stripe_token,
                                            "shippingFirstname":shippingFirstname,
                                            "shippingLastname":shippingLastname,
                                            "shippingaddress":shippingAddress,
                                            "shippingaddressline2":shippinngAddressline2,
                                            "shippingcity":shippingCity,
                                            "shippingstate":shippingState,
                                            "shippingpostcode":shippingPostCode,
                                            "shippingcounrty":shippingCountry,
                                            "shippingtelephone":shippingTelephone
                                        };
                                                @php if(isset($_COOKIE["customer_token"])): @endphp
                                                @php if(!key_exists('message',$customerData)): @endphp
                                        var url = '@php echo url('/').'/customerplaceorder'; @endphp';
                                        $.ajax({
                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                            type: "POST",
                                            url: url,
                                            data: data,
                                            success: function (data) {
                                                $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                                                console.log(data);
                                                if (data['message']) {
                                                    $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                                                } else {
                                                    localStorage.setItem('applied_coupon', '');
                                                    $('#order-success-step').css({'display': 'block'});
                                                    $('#steps-of-checkout').css({'display': 'none'});
                                                    $('#order-success-step #customer-firstname').html('').text("Thank you " + billingFirstname + " for your purchase!");
                                                    $('#order-success-step #customer-email').html('').text("A confirmation email has been sent to " + $('.shippingmethodForm #shippingEmail').val());
                                                    $('#order-success-step #customer-orderId').html('').text("Order ID: " + data);
                                                    $('#order-success-step #order-shipping-address').html('').append("<li>Shipping Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState  + "</li><li>" + shippingPostCode + " " + shippingCountry + "</li><li>" + billingTelephone + "</li>");
                                                    $('#order-success-step #order-billing-address').html('').append("<li>Billing Address</li><li>" + billingFirstname + " " + billingLastname + "</li><li>" + billingAddress + " " + billingAddressLine2 + "</li><li>" + billingCity + "  " + billingState + "</li><li>" + billingPostCode + "  " + billingCountry + "</li><li>" + billingTelephone + "</li>");
                                                    var shipingMethod = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                                    var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                                    $('#order-success-step #order-shipping-method').html('').append('<li>Shipping Method</li><li>' + shipingMethodlabel + '</li>');
                                                    $('#order-success-step #order-payment-method').html('').append('<li>Payment Method</li>' + paymentmethodlabel + '</li>');
                                                    $('.right-sec .promo_code').css({'display': 'none'});
                                                    $('.right-sec .remove.remove-item').css({'display':'none'});
                                                    $('.right-sec .form-group input.form-control.mb-0').css({'display':'none'});
                                                    $('.right-sec .col-md-9 h6:nth-child(3)').addClass('price');
                                                    $('.col-md-9.col-9 .form-group .quantity-select.cartitemqty').css('display','none');
                                                    $('.col-md-9.col-9 .form-group .up-counter').css('display','none');
                                                    $('.col-md-9.col-9 .form-group .down-counter').css('display','none');
                                                    jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                                    var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                                    if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                                        @php $size = 8; @endphp
                                                                @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                            referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                                        $.ajax({
                                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                            url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                            method: 'POST',
                                                            dataType:'json',
                                                            data: referral_data,
                                                            success: function(coupon_data){
                                                                var referral_back_generated_code = coupon_data.code;
                                                                referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email, 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                                                $.ajax({
                                                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                                    url:'@php echo url('/').'/referralback'; @endphp',
                                                                    method: 'POST',
                                                                    dataType:'json',
                                                                    data: referralBack_data,
                                                                    success: function(coupon_data){

                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                    var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                                    var giftcard_price = localStorage.getItem("giftcard_price");
                                                    if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                                        senderemail = $('.shippingmethodForm #shippingEmail').val();
                                                        sendername =  shippingFirstname+ ' ' + shippingLastname;
                                                        var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                                        var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                                        var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                                        var gft_price = '$'+giftcard_price;
                                                        couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                                        $.ajax({
                                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                            url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                                            method : 'POST',
                                                            data: couponcode_data,
                                                            success: function(couponcode_data){
                                                                console.log(couponcode_data);
                                                            }
                                                        })
                                                    }
                                                    @php $size = 8; @endphp
                                                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                        referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'1','type':'1'};
                                                    $.ajax({
                                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                        url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                        method: 'POST',
                                                        dataType:'json',
                                                        data: referral_data,
                                                        success: function(coupon_data){
                                                            var generated_referral_code = coupon_data.code;
                                                            localStorage.setItem("generated_referral_code", generated_referral_code);
                                                        }
                                                    });
                                                    setTimeout(function() {
                                                        $('#refer-friend').modal('show');
                                                    }, 3000);
                                                }
                                                $('div.main-loader').css('display','none');
                                            },
                                            dataType: 'json'
                                        });
                                                @php else: @endphp
                                        var url = '@php echo url('/').'/placeorder'; @endphp';
                                        $.ajax({
                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                            type: "POST",
                                            url: url,
                                            data: data,
                                            success: function (data) {
                                                $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                                                console.log(data);
                                                if (data['message']) {
                                                    $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                                                } else {
                                                    localStorage.setItem('applied_coupon', '');
                                                    $('#order-success-step').css({'display': 'block'});
                                                    $('#steps-of-checkout').css({'display': 'none'});
                                                    $('#order-success-step #customer-firstname').html('').text("Thank you " + billingFirstname + " for your purchase!");
                                                    $('#order-success-step #customer-email').html('').text("A confirmation email has been sent to " + $('.shippingmethodForm #shippingEmail').val());
                                                    $('#order-success-step #customer-orderId').html('').text("Order ID: " + data);
                                                    $('#order-success-step #order-shipping-address').html('').append("<li>Shipping Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode +  " " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                                    $('#order-success-step #order-billing-address').html('').append("<li>Billing Address</li><li>" + billingFirstname + " " + billingLastname + "</li><li>" + billingAddress + " " + billingAddressLine2 + "</li><li>" + billingCity + "  " + billingState + "</li><li>" + billingPostCode + " "  + billingCountry + "</li><li>" + billingTelephone + "</li>");
                                                    var shipingMethod = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').val();
                                                    var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                                    $('#order-success-step #order-shipping-method').html('').append('<li>Shipping Method</li><li>' + shipingMethodlabel + '</li>');
                                                    $('#order-success-step #order-payment-method').html('').append('<li>Payment Method</li>' + paymentmethodlabel + '</li>');
                                                    $('.right-sec .promo_code').css({'display': 'none'});
                                                    $('.right-sec .remove.remove-item').css({'display':'none'});
                                                    $('.right-sec .form-group input.form-control.mb-0').css({'display':'none'});
                                                    $('.right-sec .col-md-9 h6:nth-child(3)').addClass('price');
                                                    $('.col-md-9.col-9 .form-group .quantity-select.cartitemqty').css('display','none');
                                                    $('.col-md-9.col-9 .form-group .up-counter').css('display','none');
                                                    $('.col-md-9.col-9 .form-group .down-counter').css('display','none');
                                                    jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                                    var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                                    if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                                        @php $size = 8; @endphp
                                                                @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                            referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                                        $.ajax({
                                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                            url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                            method: 'POST',
                                                            dataType:'json',
                                                            data: referral_data,
                                                            success: function(coupon_data){
                                                                var referral_back_generated_code = coupon_data.code;
                                                                referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email, 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                                                $.ajax({
                                                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                                    url:'@php echo url('/').'/referralback'; @endphp',
                                                                    method: 'POST',
                                                                    dataType:'json',
                                                                    data: referralBack_data,
                                                                    success: function(coupon_data){

                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                    var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                                    var giftcard_price = localStorage.getItem("giftcard_price");
                                                    if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                                        senderemail = $('.shippingmethodForm #shippingEmail').val();
                                                        sendername =  shippingFirstname+ ' ' + shippingLastname;
                                                        var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                                        var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                                        var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                                        var gft_price = '$'+giftcard_price;
                                                        couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                                        $.ajax({
                                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                            url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                                            method : 'POST',
                                                            data: couponcode_data,
                                                            success: function(couponcode_data){
                                                                console.log(couponcode_data);
                                                            }
                                                        })
                                                    }
                                                    @php $size = 8; @endphp
                                                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                        referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'1','type':'1'};
                                                    $.ajax({
                                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                        url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                        method: 'POST',
                                                        dataType:'json',
                                                        data: referral_data,
                                                        success: function(coupon_data){
                                                            var generated_referral_code = coupon_data.code;
                                                            localStorage.setItem("generated_referral_code", generated_referral_code);
                                                        }
                                                    });
                                                    /*setTimeout(function() {
                                                        $('#refer-friend').modal('show');
                                                    }, 3000);*/
                                                }
                                                $('div.main-loader').css('display','none');
                                            },
                                            dataType: 'json'
                                        });
                                                @php endif; @endphp
                                                @php else: @endphp
                                        var url = '@php echo url('/').'/placeorder'; @endphp';
                                        $.ajax({
                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                            type: "POST",
                                            url: url,
                                            data: data,
                                            success: function (data) {
                                                $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                                                console.log(data);
                                                if (data['message']) {
                                                    $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                                                } else {
                                                    localStorage.setItem('applied_coupon', '');
                                                    $('#order-success-step').css({'display': 'block'});
                                                    $('#steps-of-checkout').css({'display': 'none'});
                                                    $('#order-success-step #customer-firstname').html('').text("Thank you " + billingFirstname + " for your purchase!");
                                                    $('#order-success-step #customer-email').html('').text("A confirmation email has been sent to " + $('.shippingmethodForm #shippingEmail').val());
                                                    $('#order-success-step #customer-orderId').html('').text("Order ID: " + data);
                                                    $('#order-success-step #order-shipping-address').html('').append("<li>Shipping Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode + " " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                                    $('#order-success-step #order-billing-address').html('').append("<li>Billing Address</li><li>" + billingFirstname + " " + billingLastname + "</li><li>" + billingAddress + " " + billingAddressLine2 + "</li><li>" + billingCity + "  " + billingState + "</li><li>" + billingPostCode + " " + billingCountry + "</li><li>" + billingTelephone + "</li>");
                                                    var shipingMethod = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').val();
                                                    var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                                    $('#order-success-step #order-shipping-method').html('').append('<li>Shipping Method</li><li>' + shipingMethodlabel + '</li>');
                                                    $('#order-success-step #order-payment-method').html('').append('<li>Payment Method</li>' + paymentmethodlabel + '</li>');
                                                    $('.right-sec .promo_code').css({'display': 'none'});
                                                    $('.right-sec .remove.remove-item').css({'display':'none'});
                                                    $('.right-sec .form-group input.form-control.mb-0').css({'display':'none'});
                                                    $('.right-sec .col-md-9 h6:nth-child(3)').addClass('price');
                                                    $('.col-md-9.col-9 .form-group .quantity-select.cartitemqty').css('display','none');
                                                    $('.col-md-9.col-9 .form-group .up-counter').css('display','none');
                                                    $('.col-md-9.col-9 .form-group .down-counter').css('display','none');
                                                    jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                                    var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                                    if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                                        @php $size = 8; @endphp
                                                                @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                            referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                                        $.ajax({
                                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                            url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                            method: 'POST',
                                                            dataType:'json',
                                                            data: referral_data,
                                                            success: function(coupon_data){
                                                                var referral_back_generated_code = coupon_data.code;
                                                                referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email, 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                                                $.ajax({
                                                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                                    url:'@php echo url('/').'/referralback'; @endphp',
                                                                    method: 'POST',
                                                                    dataType:'json',
                                                                    data: referralBack_data,
                                                                    success: function(coupon_data){

                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                    var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                                    var giftcard_price = localStorage.getItem("giftcard_price");
                                                    if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                                        senderemail = $('.shippingmethodForm #shippingEmail').val();
                                                        sendername =  shippingFirstname+ ' ' + shippingLastname;
                                                        var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                                        var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                                        var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                                        var gft_price = '$'+giftcard_price;
                                                        couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                                        $.ajax({
                                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                            url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                                            method : 'POST',
                                                            data: couponcode_data,
                                                            success: function(couponcode_data){
                                                                console.log(couponcode_data);
                                                            }
                                                        })
                                                    }
                                                    @php $size = 8; @endphp
                                                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                        referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'1','type':'1'};
                                                    $.ajax({
                                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                        url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                        method: 'POST',
                                                        dataType:'json',
                                                        data: referral_data,
                                                        success: function(coupon_data){
                                                            var generated_referral_code = coupon_data.code;
                                                            localStorage.setItem("generated_referral_code", generated_referral_code);
                                                        }
                                                    });
                                                    /*setTimeout(function() {
                                                        $('#refer-friend').modal('show');
                                                    }, 3000);*/
                                                }
                                                $('div.main-loader').css('display','none');
                                            },
                                            dataType: 'json'
                                        });
                                        @php endif; @endphp
                                        @php endif; @endphp
                                        @php endif; @endphp
                                    }
                                }
                            } else{
                                $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                                $('div.main-loader').css('display','none');
                                if(data.result == 'carderror'){
                                    $('form.cc-method .credit_card_form .col-md-12:nth-child(2)').append('<p class="error" style="color:red;">Please check the details you have entered.</p>');
                                } else if(data.result == 'missing-parameter'){
                                    $('form.cc-method .credit_card_form .col-md-12:nth-child(2)').append('<p class="error" style="color:red;">Required parameters missing. Please check again.</p>');
                                } else{
                                    $('form.cc-method .credit_card_form .col-md-12:nth-child(2)').append('<p class="error" style="color:red;">Exception error occured. Please try again later.</p>');
                                }
                            }
                        },
                        dataType: 'json'
                    });
                    @php endif; @endphp
                    @php endif; @endphp
                    @php endif; @endphp
                }
            } else{
                //$('div.main-loader').css('display','block');
                $('#pills-payment-method .billing_address button.placeorder').addClass('disabled').attr('disabled','disabled');
                var billingvalue = $("#pills-payment-method .billing_address .billing_kind input[name=billingstatus]:checked").val();
                @php if(array_key_exists("quote_id",$session)): @endphp
                @php if($session['quote_id'] != '' ): @endphp
                @php $quote_id = $session['quote_id']; @endphp
                @php if(!is_object($quote_id)): @endphp
                <?php if(isset($session['customer_token'])){
                    $url = createquote().''.$quote_id;
                } else{
                    $url = createquoteguest().''.$quote_id;
                }?>
                @php $cartdata = m2ApiCall($url,'get',''); @endphp
                @php if(array_key_exists('items',$cartdata)): @endphp
                @foreach($cartdata['items'] as $data)
                @if($data['sku'] == 'Gift_Card-$50' || $data['sku'] == 'Gift_Card-$55' || $data['sku'] == 'Gift_Card-$60' || $data['sku'] == 'Gift_Card-$70' || $data['sku'] == 'Gift_Card-$75' || $data['sku'] == 'Gift_Card-$80' || $data['sku'] == 'Gift_Card-$90' || $data['sku'] == 'Gift_Card-$100' || $data['sku'] == 'Gift_Card-$75' || $data['sku'] == 'Gift_Card-$150' || $data['sku'] == 'Gift_Card-$200')
                @php $size = 8; @endphp
                @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp

                $.ajax({
                    url:'@php echo url('/').'/simpleProductData/'.$data['sku']; @endphp',
                    method: 'GET',
                    dataType:'json',
                    success: function(coupon_data){
                        console.log(coupon_data);
                        for (var i = 0; i < coupon_data.custom_attributes.length; i++) {
                            if(coupon_data.custom_attributes[i].attribute_code == 'gift_card'){
                                if(coupon_data.custom_attributes[i].value == '1'){
                                    var is_giftcard = '1';
                                }else{
                                    var is_giftcard = '0';
                                }
                            } if(coupon_data.custom_attributes[i].attribute_code == 'gift_card_rule_id'){
                                if(coupon_data.custom_attributes[i].value != ''){
                                    var rule_id = coupon_data.custom_attributes[i].value;
                                    var giftcard_price = coupon_data.price;
                                }else{
                                    var rule_id = '';
                                    var giftcard_price = coupon_data.price;
                                }
                            }
                        }
                        //console.log(' is gift '+is_giftcard +' - rule_id -'+ rule_id + ' - giftcard_price '+giftcard_price);
                        if(is_giftcard == '1' && rule_id != ''){
                            coupon_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':rule_id,'type':'1'};
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                method: 'POST',
                                dataType:'json',
                                data: coupon_data,
                                success: function(coupon_data){
                                    var generated_coupon_code = coupon_data.code;
                                    localStorage.setItem("generated_coupon_code", generated_coupon_code);
                                    localStorage.setItem("giftcard_price", giftcard_price);
                                }
                            });
                        }
                    }
                });
                @endif
                        @endforeach
                        @php endif; @endphp
                        @php endif; @endphp
                        @php endif; @endphp
                        @php endif; @endphp
                if (billingvalue == 'same') {
                    var shippingEmail = $('form.shippingmethodForm #shippingEmail').val();
                    var shippingFirstname = $('form.shippingmethodForm #shippingFirstname').val();
                    var shippingLastname = $('form.shippingmethodForm #shippingLastname').val();
                    var shippingAddress = $('form.shippingmethodForm #shippingAddress').val();
                    var shippinngAddressline2 = $('form.shippingmethodForm #shippinngAddressline2').val();
                    var shippingCity = $('form.shippingmethodForm #shippingCity').val();
                    var shippingState = $('form.shippingmethodForm div.select div.select-styled').text();
                    var shippingRegionId = $('form.shippingmethodForm #shippingState').find('option[data-title="' + shippingState + '"]').val();
                    /*var shippingState = $('form.shippingmethodForm #shippingState').val();*/
                    var shippingCountry = 'US'/*$('form.shippingmethodForm #country_id').val();*/
                    /*var shippingCountry = $('form.shippingmethodForm div.select div.select-styled').text();
                    var shippingCountry = $('form.shippingmethodForm #country_id').find('option[data-title="' + shippingCountry + '"]').val();*/
                    var shippingPostCode = $('form.shippingmethodForm #shippingPostCode').val();
                    var shippingTelephone = $('form.shippingmethodForm #shippingTelephone').val();
                    var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                    var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                    var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                    var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                    var oldaddressId = $('form.shippingmethodForm #address_id').val();
                            @php if(array_key_exists("quote_id",$session)): @endphp
                            @php if($session['quote_id'] != '' ): @endphp
                            @php $quote_id = $session['quote_id']; @endphp
                    var quote_id = '@php echo $quote_id @endphp';
                    var paymentmethod = $('#pills-payment-method .payment_method input[name=payment-method]:checked').val();
                    var paymentmethodlabel = $('#pills-payment-method .payment_method input[name=payment-method]:checked').attr('methodlabel');
                    if (paymentmethod == 'payflowpro') {

                    }
                    var subtotal = $('.right-sec .subtotal tr:first-child td.text-right').html();
                    var total = $('.right-sec .total tr td.text-right h3 b').html();
                    var taxtotal = $('.right-sec .subtotal tr.tax td.text-right').html();
                    var shippingcost = $('.right-sec .subtotal tr.shipping-total td.text-right').html();
                    var data = {
                        "quote_id": quote_id,
                        "email": shippingEmail,
                        "firstname": shippingFirstname,
                        "lastname": shippingLastname,
                        "address": shippingAddress,
                        "addressline2": shippinngAddressline2,
                        "city": shippingCity,
                        "state": shippingState,
                        "region_id":shippingRegionId,
                        "country": shippingCountry,
                        "postcode": shippingPostCode,
                        "receivername": shippingReceiverName,
                        "receiveremail": shippingReceiverEmail,
                        "message": shippingReceiverMessage,
                        "paymentmethod": paymentmethod,
                        "shippingmethod": shipingMethodlabel,
                        "paymethodlabel": paymentmethodlabel,
                        "subtotal": subtotal,
                        "taxtotal":taxtotal,
                        "shippingrate": shippingcost,
                        "total": total,
                        "telephone": shippingTelephone
                    };
                    console.log(data);
                            @php if(isset($_COOKIE["customer_token"])): @endphp
                            @php if(!key_exists('message',$customerData)): @endphp
                    var url = '@php echo url('/').'/customerplaceorder'; @endphp';
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: "POST",
                        url: url,
                        data: data,
                        success: function (data) {
                            $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                            if (data['message']) {
                                $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                            } else {
                                localStorage.setItem('applied_coupon', '');
                                if($('input#saveaddress').prop('checked')){
                                    save_address = 1;
                                } else{
                                    save_address = 0;
                                }
                                $('#order-success-step').css({'display': 'block'});
                                $('#steps-of-checkout').css({'display': 'none'});
                                $('#order-success-step #customer-firstname').html('').text("Thank you " + $('.shippingmethodForm #shippingFirstname').val() + " for your purchase!");
                                $('#order-success-step #customer-email').html('').text("A confirmation email has been sent to " + $('.shippingmethodForm #shippingEmail').val());
                                $('#order-success-step #customer-orderId').html('').text("Order ID: " + data);
                                $('#order-success-step #order-shipping-address').html('').append("<li>Shipping Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode + "  " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                $('#order-success-step #order-billing-address').html('').append("<li>Billing Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode + "  " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                var shipingMethod = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                $('#order-success-step #order-shipping-method').html('').html('<li>Shipping Method</li><li>' + shipingMethodlabel + '</li>');
                                $('#order-success-step #order-payment-method').html('').html('<li>Payment Method</li>' + paymentmethodlabel + '</li>');
                                $('.right-sec .promo_code').css({'display': 'none'});
                                $('.right-sec .remove.remove-item').css({'display':'none'});
                                $('.right-sec .form-group input.form-control.mb-0').css({'display':'none'});
                                $('.right-sec .col-md-9 h6:nth-child(3)').addClass('price');
                                $('.col-md-9.col-9 .form-group .quantity-select.cartitemqty').css('display','none');
                                $('.col-md-9.col-9 .form-group .up-counter').css('display','none');
                                $('.col-md-9.col-9 .form-group .down-counter').css('display','none');
                                jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                if(save_address == 1){
                                    var shippingState = $('form.shippingmethodForm div.select div.select-styled').text();
                                    var shippingRegionId = $('form.shippingmethodForm #shippingState').find('option[data-title="' + shippingState + '"]').val();
                                    if(shippinngAddressline2 != undefined){
                                        data = {'firstname':shippingFirstname,'lastname':shippingLastname,'streetline1':shippingAddress,'streetline2':shippinngAddressline2,'telephone':shippingTelephone,'city':shippingCity,'state':shippingState,'region_id':shippingRegionId,'postcode':shippingPostCode,'country':shippingCountry,'isshipping':true,'customerfirstname':'@php echo $customerData['firstname']; @endphp','customerlastname':'@php echo $customerData['lastname']; @endphp','customeremail':'@php echo $customerData['email']; @endphp'};
                                    } else{
                                        data = {'firstname':shippingFirstname,'lastname':shippingLastname,'streetline1':shippingAddress,'streetline2':'','telephone':shippingTelephone,'city':shippingCity,'state':shippingState,'postcode':shippingPostCode,'country':shippingCountry,'isshipping':true,'customerfirstname':'@php echo $customerData['firstname']; @endphp','customerlastname':'@php echo $customerData['lastname']; @endphp','customeremail':'@php echo $customerData['email']; @endphp'};
                                    }
                                    valid = true;
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        type:'POST',
                                        data:data,
                                        url :'@php echo url('/')."/saveaddress"; @endphp',
                                        success: function (saveaddressdata) {
                                            console.log(saveaddressdata);
                                        },
                                        dataType: 'json'
                                    });
                                }
                                var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                    @php $size = 8; @endphp
                                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                        referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                        method: 'POST',
                                        dataType:'json',
                                        data: referral_data,
                                        success: function(coupon_data){
                                            var referral_back_generated_code = coupon_data.code;
                                            referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email, 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                            $.ajax({
                                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                url:'@php echo url('/').'/referralback'; @endphp',
                                                method: 'POST',
                                                dataType:'json',
                                                data: referralBack_data,
                                                success: function(coupon_data){

                                                }
                                            });
                                        }
                                    });
                                }
                                var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                var giftcard_price = localStorage.getItem("giftcard_price");
                                if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                    senderemail = $('.shippingmethodForm #shippingEmail').val();
                                    sendername =  shippingFirstname+ ' ' + shippingLastname;
                                    var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                    var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                    var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                    var gft_price = '$'+giftcard_price;
                                    couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                        method : 'POST',
                                        data: couponcode_data,
                                        success: function(couponcode_data){
                                            console.log(couponcode_data);
                                        }
                                    });
                                }
                                @php $size = 8; @endphp
                                        @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                    referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'1','type':'1'};
                                $.ajax({
                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                    url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                    method: 'POST',
                                    dataType:'json',
                                    data: referral_data,
                                    success: function(coupon_data){
                                        var generated_referral_code = coupon_data.code;
                                        localStorage.setItem("generated_referral_code", generated_referral_code);
                                    }
                                });
                                setTimeout(function() {
                                    $('#refer-friend').modal('show');
                                }, 3000);
                            }
                            $('div.main-loader').css('display','none');
                        },
                        dataType: 'json'
                    });
                            @php else: @endphp
                    var url = '@php echo url('/').'/placeorder'; @endphp';
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: "POST",
                        url: url,
                        data: data,
                        success: function (data) {
                            $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                            console.log(data);
                            if (data['message']) {
                                $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                            } else {
                                localStorage.setItem('applied_coupon', '');
                                $('#order-success-step').css({'display': 'block'});
                                $('#steps-of-checkout').css({'display': 'none'});
                                $('#order-success-step #customer-firstname').html('').text("Thank you " + $('.shippingmethodForm #shippingFirstname').val() + " for your purchase!");
                                $('#order-success-step #customer-email').html('').text("A confirmation email has been sent to " + $('.shippingmethodForm #shippingEmail').val());
                                $('#order-success-step #customer-orderId').html('').text("Order ID: " + data);
                                $('#order-success-step #order-shipping-address').html('').append("<li>Shipping Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode + " " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                $('#order-success-step #order-billing-address').html('').append("<li>Billing Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode + "  " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                var shipingMethod = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                $('#order-success-step #order-shipping-method').html('').append('<li>Shipping Method</li><li>' + shipingMethodlabel + '</li>');
                                $('#order-success-step #order-payment-method').html('').append('<li>Payment Method</li>' + paymentmethodlabel + '</li>');
                                $('.right-sec .promo_code').css({'display': 'none'});
                                $('.right-sec .remove.remove-item').css({'display':'none'});
                                $('.right-sec .form-group input.form-control.mb-0').css({'display':'none'});
                                $('.right-sec .col-md-9 h6:nth-child(3)').addClass('price');
                                $('.col-md-9.col-9 .form-group .quantity-select.cartitemqty').css('display','none');
                                $('.col-md-9.col-9 .form-group .up-counter').css('display','none');
                                $('.col-md-9.col-9 .form-group .down-counter').css('display','none');
                                jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                    @php $size = 8; @endphp
                                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                        referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                        method: 'POST',
                                        dataType:'json',
                                        data: referral_data,
                                        success: function(coupon_data){
                                            var referral_back_generated_code = coupon_data.code;
                                            referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email, 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                            $.ajax({
                                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                url:'@php echo url('/').'/referralback'; @endphp',
                                                method: 'POST',
                                                dataType:'json',
                                                data: referralBack_data,
                                                success: function(coupon_data){

                                                }
                                            });
                                        }
                                    });
                                }
                                var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                var giftcard_price = localStorage.getItem("giftcard_price");
                                if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                    senderemail = $('.shippingmethodForm #shippingEmail').val();
                                    sendername =  shippingFirstname+ ' ' + shippingLastname;
                                    var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                    var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                    var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                    var gft_price = '$'+giftcard_price;
                                    couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                        method : 'POST',
                                        data: couponcode_data,
                                        success: function(couponcode_data){
                                            console.log(couponcode_data);
                                        }
                                    })
                                }
                            }
                            @php $size = 8; @endphp
                                    @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'1','type':'1'};
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                method: 'POST',
                                dataType:'json',
                                data: referral_data,
                                success: function(coupon_data){
                                    var generated_referral_code = coupon_data.code;
                                    localStorage.setItem("generated_referral_code", generated_referral_code);
                                }
                            });
                            /*setTimeout(function() {
                                $('#refer-friend').modal('show');
                            }, 3000);*/
                            $('div.main-loader').css('display','none');
                        },
                        dataType: 'json'
                    });
                            @php endif; @endphp
                            @php else: @endphp
                    var url = '@php echo url('/').'/placeorder'; @endphp';
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: "POST",
                        url: url,
                        data: data,
                        success: function (data) {
                            $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                            console.log(data);
                            if (data['message']) {
                                $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                            } else {
                                localStorage.setItem('applied_coupon', '');
                                $('#order-success-step').css({'display': 'block'});
                                $('#steps-of-checkout').css({'display': 'none'});
                                $('#order-success-step #customer-firstname').html('').text("Thank you " + $('.shippingmethodForm #shippingFirstname').val() + " for your purchase!");
                                $('#order-success-step #customer-email').html('').text("A confirmation email has been sent to " + $('.shippingmethodForm #shippingEmail').val());
                                $('#order-success-step #customer-orderId').html('').text("Order ID: " + data);
                                $('#order-success-step #order-shipping-address').html('').append("<li>Shipping Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " +  shippingState + "</li><li>" + shippingPostCode + "  " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                $('#order-success-step #order-billing-address').html('').append("<li>Billing Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode + " " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                var shipingMethod = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                $('#order-success-step #order-shipping-method').html('').append('<li>Shipping Method</li><li>' + shipingMethodlabel + '</li>');
                                $('#order-success-step #order-payment-method').html('').append('<li>Payment Method</li>' + paymentmethodlabel + '</li>');
                                $('.right-sec .promo_code').css({'display': 'none'});
                                $('.right-sec .remove.remove-item').css({'display':'none'});
                                $('.right-sec .form-group input.form-control.mb-0').css({'display':'none'});
                                $('.right-sec .col-md-9 h6:nth-child(3)').addClass('price');
                                $('.col-md-9.col-9 .form-group .quantity-select.cartitemqty').css('display','none');
                                $('.col-md-9.col-9 .form-group .up-counter').css('display','none');
                                $('.col-md-9.col-9 .form-group .down-counter').css('display','none');
                                jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                    @php $size = 8; @endphp
                                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                        referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                        method: 'POST',
                                        dataType:'json',
                                        data: referral_data,
                                        success: function(coupon_data){
                                            var referral_back_generated_code = coupon_data.code;
                                            referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email, 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                            $.ajax({
                                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                url:'@php echo url('/').'/referralback'; @endphp',
                                                method: 'POST',
                                                dataType:'json',
                                                data: referralBack_data,
                                                success: function(coupon_data){

                                                }
                                            });
                                        }
                                    });
                                }
                                var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                var giftcard_price = localStorage.getItem("giftcard_price");
                                if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                    senderemail = $('.shippingmethodForm #shippingEmail').val();
                                    sendername =  shippingFirstname+ ' ' + shippingLastname;
                                    var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                    var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                    var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                    var gft_price = '$'+giftcard_price;
                                    couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                        method : 'POST',
                                        data: couponcode_data,
                                        success: function(couponcode_data){
                                            console.log(couponcode_data);
                                        }
                                    })
                                }
                                @php $size = 8; @endphp
                                        @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                    referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'1','type':'1'};
                                $.ajax({
                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                    url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                    method: 'POST',
                                    dataType:'json',
                                    data: referral_data,
                                    success: function(coupon_data){
                                        var generated_referral_code = coupon_data.code;
                                        localStorage.setItem("generated_referral_code", generated_referral_code);
                                    }
                                });
                                /*setTimeout(function() {
                                    $('#refer-friend').modal('show');
                                }, 3000);*/
                            }
                            $('div.main-loader').css('display','none');
                        },
                        dataType: 'json'
                    });
                    @php endif; @endphp

                    @php endif; @endphp
                    @php endif; @endphp
                } else {
                    if (!$('form.differnt_billing_address #billingFirstname').val()) {
                        valid = false;
                        $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingFirstname');
                    } else if (!$('form.differnt_billing_address #billingLastname').val()) {
                        valid = false;
                        $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingLastname');
                    } else if (!$('form.differnt_billing_address #billingAddress').val()) {
                        valid = false;
                        $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingAddress');
                    } else if (!$('form.differnt_billing_address #billingCity').val()) {
                        valid = false;
                        $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingCity');
                    } /*else if (!$('form.differnt_billing_address #billingState').val()) {
                    valid = false;
                    $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingState');
                }*/ else if (!$('form.differnt_billing_address #billingPostCode').val()) {
                        valid = false;
                        $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('form.differnt_billing_address #billingPostCode');
                    } else {
                        valid = true;
                        var shippingEmail = $('form.shippingmethodForm #shippingEmail').val();
                        var shippingFirstname = $('form.shippingmethodForm #shippingFirstname').val();
                        var shippingLastname = $('form.shippingmethodForm #shippingLastname').val();
                        var shippingAddress = $('form.shippingmethodForm #shippingAddress').val();
                        var shippinngAddressline2 = $('form.shippingmethodForm #shippinngAddressline2').val();
                        var shippingCity = $('form.shippingmethodForm #shippingCity').val();
                        var shippingState = $('form.shippingmethodForm div.select div.select-styled').text();
                        var shippingRegionId = $('form.shippingmethodForm #shippingState').find('option[data-title="' + shippingState + '"]').val();
                        /*var shippingState = $('form.shippingmethodForm #shippingState').val();*/
                        var shippingCountry = 'US'/*$('form.shippingmethodForm #country_id').val();*/
                        /*var shippingCountry = $('form.shippingmethodForm div.select div.select-styled').text();
                        var shippingCountry = $('form.shippingmethodForm #country_id').find('option[data-title="' + shippingCountry + '"]').val();*/
                        var shippingPostCode = $('form.shippingmethodForm #shippingPostCode').val();
                        var shippingTelephone = $('form.shippingmethodForm #shippingTelephone').val();
                        var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                        var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                        var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();

                        var billingFirstname = $('form.differnt_billing_address #billingFirstname').val();
                        var billingLastname = $('form.differnt_billing_address #billingLastname').val();
                        var billingAddress = $('form.differnt_billing_address #billingAddress').val();
                        var billingAddressLine2 = $('form.differnt_billing_address #billingAddressline2').val();
                        var billingCity = $('form.differnt_billing_address #billingCity').val();
                        /*var billingState = $('form.differnt_billing_address #billingState').val();*/
                        var billingState = $('form.differnt_billing_address div.select div.select-styled').text();
                        var billingRegionId = $('form.differnt_billing_address #shippingState').find('option[data-title="' + billingState + '"]').val();
                        /*var shippingState = $('form.shippingmethodForm #shippingState').val();*/
                        var billingCountry = 'US'/*$('form.shippingmethodForm #country_id').val();*/
                        /*var shippingCountry = $('form.shippingmethodForm div.select div.select-styled').text();
                        var shippingCountry = $('form.shippingmethodForm #country_id').find('option[data-title="' + shippingCountry + '"]').val();*/
                        var billingPostCode = $('form.differnt_billing_address #billingPostCode').val();
                        var billingTelephone = $('form.differnt_billing_address #billingTelephone').val();
                        var billingCountrytitle = $('form.differnt_billing_address div.select div.select-styled').text();
                        //var billingCountry = $('form.differnt_billing_address #country_id').find('option[data-title="' + billingCountrytitle + '"]').val();
                        var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').val();

                                @php if(array_key_exists("quote_id",$session)): @endphp
                                @php if($session['quote_id'] != '' ): @endphp
                                @php $quote_id = $session['quote_id']; @endphp
                        var quote_id = '@php echo $quote_id @endphp';
                        var paymentmethod = $('#pills-payment-method .payment_method input[name=payment-method]:checked').val();
                        var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                        var paymentmethodlabel = $('#pills-payment-method .payment_method input[name=payment-method]:checked').attr('methodlabel');
                        var subtotal = $('.right-sec .subtotal tr:first-child td.text-right').html();
                        var total = $('.right-sec .total tr td.text-right h3 b').html();
                        var taxtotal = $('.right-sec .subtotal tr.tax td.text-right').html();
                        var shippingcost = $('.right-sec .subtotal tr.shipping-total td.text-right').html();
                        var data = {
                            "quote_id": quote_id,
                            "email": shippingEmail,
                            "firstname": billingFirstname,
                            "lastname": billingLastname,
                            "address": billingAddress,
                            "addressline2": billingAddressLine2,
                            "city": billingCity,
                            "state": billingState,
                            "region_id": billingRegionId,
                            "country": billingCountry,
                            "postcode": billingPostCode,
                            "telephone": billingTelephone,
                            "receivername": shippingReceiverName,
                            "receiveremail": shippingReceiverEmail,
                            "message": shippingReceiverMessage,
                            "receivername": shippingReceiverName,
                            "receiveremail": shippingReceiverEmail,
                            "message": shippingReceiverMessage,
                            "shippingmethod": shipingMethodlabel,
                            "paymentmethod": paymentmethod,
                            "shippingmethod": shipingMethodlabel,
                            "paymethodlabel": paymentmethodlabel,
                            "subtotal": subtotal,
                            "taxtotal": taxtotal,
                            "shippingrate": shippingcost,
                            "total": total,
                            "shippingFirstname":shippingFirstname,
                            "shippingLastname":shippingLastname,
                            "shippingaddress":shippingAddress,
                            "shippingaddressline2":shippinngAddressline2,
                            "shippingcity":shippingCity,
                            "shippingstate":shippingState,
                            "shippingpostcode":shippingPostCode,
                            "shippingcounrty":shippingCountry,
                            "shippingtelephone":shippingTelephone
                        };
                                @php if(isset($_COOKIE["customer_token"])): @endphp
                                @php if(!key_exists('message',$customerData)): @endphp
                        var url = '@php echo url('/').'/customerplaceorder'; @endphp';
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            type: "POST",
                            url: url,
                            data: data,
                            success: function (data) {
                                $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                                console.log(data);
                                if (data['message']) {
                                    $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                                } else {
                                    localStorage.setItem('applied_coupon', '');
                                    $('#order-success-step').css({'display': 'block'});
                                    $('#steps-of-checkout').css({'display': 'none'});
                                    $('#order-success-step #customer-firstname').html('').text("Thank you " + billingFirstname + " for your purchase!");
                                    $('#order-success-step #customer-email').html('').text("A confirmation email has been sent to " + $('.shippingmethodForm #shippingEmail').val());
                                    $('#order-success-step #customer-orderId').html('').text("Order ID: " + data);
                                    $('#order-success-step #order-shipping-address').html('').append("<li>Shipping Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState  + "</li><li>" + shippingPostCode + " " + shippingCountry + "</li><li>" + billingTelephone + "</li>");
                                    $('#order-success-step #order-billing-address').html('').append("<li>Billing Address</li><li>" + billingFirstname + " " + billingLastname + "</li><li>" + billingAddress + " " + billingAddressLine2 + "</li><li>" + billingCity + "  " + billingState + "</li><li>" + billingPostCode + "  " + billingCountry + "</li><li>" + billingTelephone + "</li>");
                                    var shipingMethod = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                    var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                    $('#order-success-step #order-shipping-method').html('').append('<li>Shipping Method</li><li>' + shipingMethodlabel + '</li>');
                                    $('#order-success-step #order-payment-method').html('').append('<li>Payment Method</li>' + paymentmethodlabel + '</li>');
                                    $('.right-sec .promo_code').css({'display': 'none'});
                                    $('.right-sec .remove.remove-item').css({'display':'none'});
                                    $('.right-sec .form-group input.form-control.mb-0').css({'display':'none'});
                                    $('.right-sec .col-md-9 h6:nth-child(3)').addClass('price');
                                    $('.col-md-9.col-9 .form-group .quantity-select.cartitemqty').css('display','none');
                                    $('.col-md-9.col-9 .form-group .up-counter').css('display','none');
                                    $('.col-md-9.col-9 .form-group .down-counter').css('display','none');
                                    jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                    var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                    if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                        @php $size = 8; @endphp
                                                @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                            referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                        $.ajax({
                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                            url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                            method: 'POST',
                                            dataType:'json',
                                            data: referral_data,
                                            success: function(coupon_data){
                                                var referral_back_generated_code = coupon_data.code;
                                                referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email, 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                                $.ajax({
                                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                    url:'@php echo url('/').'/referralback'; @endphp',
                                                    method: 'POST',
                                                    dataType:'json',
                                                    data: referralBack_data,
                                                    success: function(coupon_data){

                                                    }
                                                });
                                            }
                                        });
                                    }
                                    var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                    var giftcard_price = localStorage.getItem("giftcard_price");
                                    if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                        senderemail = $('.shippingmethodForm #shippingEmail').val();
                                        sendername =  shippingFirstname+ ' ' + shippingLastname;
                                        var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                        var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                        var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                        var gft_price = '$'+giftcard_price;
                                        couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                        $.ajax({
                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                            url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                            method : 'POST',
                                            data: couponcode_data,
                                            success: function(couponcode_data){
                                                console.log(couponcode_data);
                                            }
                                        })
                                    }
                                    @php $size = 8; @endphp
                                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                        referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'1','type':'1'};
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                        method: 'POST',
                                        dataType:'json',
                                        data: referral_data,
                                        success: function(coupon_data){
                                            var generated_referral_code = coupon_data.code;
                                            localStorage.setItem("generated_referral_code", generated_referral_code);
                                        }
                                    });
                                    setTimeout(function() {
                                        $('#refer-friend').modal('show');
                                    }, 3000);
                                }
                                $('div.main-loader').css('display','none');
                            },
                            dataType: 'json'
                        });
                                @php else: @endphp
                        var url = '@php echo url('/').'/placeorder'; @endphp';
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            type: "POST",
                            url: url,
                            data: data,
                            success: function (data) {
                                $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                                console.log(data);
                                if (data['message']) {
                                    $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                                } else {
                                    localStorage.setItem('applied_coupon', '');
                                    $('#order-success-step').css({'display': 'block'});
                                    $('#steps-of-checkout').css({'display': 'none'});
                                    $('#order-success-step #customer-firstname').html('').text("Thank you " + billingFirstname + " for your purchase!");
                                    $('#order-success-step #customer-email').html('').text("A confirmation email has been sent to " + $('.shippingmethodForm #shippingEmail').val());
                                    $('#order-success-step #customer-orderId').html('').text("Order ID: " + data);
                                    $('#order-success-step #order-shipping-address').html('').append("<li>Shipping Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode +  " " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                    $('#order-success-step #order-billing-address').html('').append("<li>Billing Address</li><li>" + billingFirstname + " " + billingLastname + "</li><li>" + billingAddress + " " + billingAddressLine2 + "</li><li>" + billingCity + "  " + billingState + "</li><li>" + billingPostCode + " "  + billingCountry + "</li><li>" + billingTelephone + "</li>");
                                    var shipingMethod = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').val();
                                    var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                    $('#order-success-step #order-shipping-method').html('').append('<li>Shipping Method</li><li>' + shipingMethodlabel + '</li>');
                                    $('#order-success-step #order-payment-method').html('').append('<li>Payment Method</li>' + paymentmethodlabel + '</li>');
                                    $('.right-sec .promo_code').css({'display': 'none'});
                                    $('.right-sec .remove.remove-item').css({'display':'none'});
                                    $('.right-sec .form-group input.form-control.mb-0').css({'display':'none'});
                                    $('.right-sec .col-md-9 h6:nth-child(3)').addClass('price');
                                    $('.col-md-9.col-9 .form-group .quantity-select.cartitemqty').css('display','none');
                                    $('.col-md-9.col-9 .form-group .up-counter').css('display','none');
                                    $('.col-md-9.col-9 .form-group .down-counter').css('display','none');
                                    jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                    var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                    if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                        @php $size = 8; @endphp
                                                @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                            referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                        $.ajax({
                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                            url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                            method: 'POST',
                                            dataType:'json',
                                            data: referral_data,
                                            success: function(coupon_data){
                                                var referral_back_generated_code = coupon_data.code;
                                                referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email, 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                                $.ajax({
                                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                    url:'@php echo url('/').'/referralback'; @endphp',
                                                    method: 'POST',
                                                    dataType:'json',
                                                    data: referralBack_data,
                                                    success: function(coupon_data){

                                                    }
                                                });
                                            }
                                        });
                                    }
                                    var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                    var giftcard_price = localStorage.getItem("giftcard_price");
                                    if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                        senderemail = $('.shippingmethodForm #shippingEmail').val();
                                        sendername =  shippingFirstname+ ' ' + shippingLastname;
                                        var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                        var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                        var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                        var gft_price = '$'+giftcard_price;
                                        couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                        $.ajax({
                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                            url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                            method : 'POST',
                                            data: couponcode_data,
                                            success: function(couponcode_data){
                                                console.log(couponcode_data);
                                            }
                                        })
                                    }
                                    @php $size = 8; @endphp
                                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                        referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'1','type':'1'};
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                        method: 'POST',
                                        dataType:'json',
                                        data: referral_data,
                                        success: function(coupon_data){
                                            var generated_referral_code = coupon_data.code;
                                            localStorage.setItem("generated_referral_code", generated_referral_code);
                                        }
                                    });
                                    /*setTimeout(function() {
                                        $('#refer-friend').modal('show');
                                    }, 3000);*/
                                }
                                $('div.main-loader').css('display','none');
                            },
                            dataType: 'json'
                        });
                                @php endif; @endphp
                                @php else: @endphp
                        var url = '@php echo url('/').'/placeorder'; @endphp';
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            type: "POST",
                            url: url,
                            data: data,
                            success: function (data) {
                                $('#pills-payment-method .billing_address button.placeorder').removeClass('disabled').removeAttr('disabled');
                                console.log(data);
                                if (data['message']) {
                                    $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                                } else {
                                    localStorage.setItem('applied_coupon', '');
                                    $('#order-success-step').css({'display': 'block'});
                                    $('#steps-of-checkout').css({'display': 'none'});
                                    $('#order-success-step #customer-firstname').html('').text("Thank you " + billingFirstname + " for your purchase!");
                                    $('#order-success-step #customer-email').html('').text("A confirmation email has been sent to " + $('.shippingmethodForm #shippingEmail').val());
                                    $('#order-success-step #customer-orderId').html('').text("Order ID: " + data);
                                    $('#order-success-step #order-shipping-address').html('').append("<li>Shipping Address</li><li>" + shippingFirstname + " " + shippingLastname + "</li><li>" + shippingAddress + " " + shippinngAddressline2 + "</li><li>" + shippingCity + "  " + shippingState + "</li><li>" + shippingPostCode + " " + shippingCountry + "</li><li>" + shippingTelephone + "</li>");
                                    $('#order-success-step #order-billing-address').html('').append("<li>Billing Address</li><li>" + billingFirstname + " " + billingLastname + "</li><li>" + billingAddress + " " + billingAddressLine2 + "</li><li>" + billingCity + "  " + billingState + "</li><li>" + billingPostCode + " " + billingCountry + "</li><li>" + billingTelephone + "</li>");
                                    var shipingMethod = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').val();
                                    var shipingMethodlabel = $('#pills-shipping-method .shipping_method input[name=shipping-method]:checked').attr('methodlabel');
                                    $('#order-success-step #order-shipping-method').html('').append('<li>Shipping Method</li><li>' + shipingMethodlabel + '</li>');
                                    $('#order-success-step #order-payment-method').html('').append('<li>Payment Method</li>' + paymentmethodlabel + '</li>');
                                    $('.right-sec .promo_code').css({'display': 'none'});
                                    $('.right-sec .remove.remove-item').css({'display':'none'});
                                    $('.right-sec .form-group input.form-control.mb-0').css({'display':'none'});
                                    $('.right-sec .col-md-9 h6:nth-child(3)').addClass('price');
                                    $('.col-md-9.col-9 .form-group .quantity-select.cartitemqty').css('display','none');
                                    $('.col-md-9.col-9 .form-group .up-counter').css('display','none');
                                    $('.col-md-9.col-9 .form-group .down-counter').css('display','none');
                                    jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                    var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                    if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                        @php $size = 8; @endphp
                                                @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                            referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                        $.ajax({
                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                            url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                            method: 'POST',
                                            dataType:'json',
                                            data: referral_data,
                                            success: function(coupon_data){
                                                var referral_back_generated_code = coupon_data.code;
                                                referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email, 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                                $.ajax({
                                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                    url:'@php echo url('/').'/referralback'; @endphp',
                                                    method: 'POST',
                                                    dataType:'json',
                                                    data: referralBack_data,
                                                    success: function(coupon_data){

                                                    }
                                                });
                                            }
                                        });
                                    }
                                    var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                    var giftcard_price = localStorage.getItem("giftcard_price");
                                    if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                        senderemail = $('.shippingmethodForm #shippingEmail').val();
                                        sendername =  shippingFirstname+ ' ' + shippingLastname;
                                        var shippingReceiverName = $('form.shippingmethodForm #shippingReceiverName').val();
                                        var shippingReceiverEmail = $('form.shippingmethodForm #shippingReceiverEmail').val();
                                        var shippingReceiverMessage = $('form.shippingmethodForm #shippingReceiverMessage').val();
                                        var gft_price = '$'+giftcard_price;
                                        couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                        $.ajax({
                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                            url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                            method : 'POST',
                                            data: couponcode_data,
                                            success: function(couponcode_data){
                                                console.log(couponcode_data);
                                            }
                                        })
                                    }
                                    @php $size = 8; @endphp
                                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                        referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'1','type':'1'};
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                        method: 'POST',
                                        dataType:'json',
                                        data: referral_data,
                                        success: function(coupon_data){
                                            var generated_referral_code = coupon_data.code;
                                            localStorage.setItem("generated_referral_code", generated_referral_code);
                                        }
                                    });
                                    /*setTimeout(function() {
                                        $('#refer-friend').modal('show');
                                    }, 3000);*/
                                }
                                $('div.main-loader').css('display','none');
                            },
                            dataType: 'json'
                        });
                        @php endif; @endphp
                        @php endif; @endphp
                        @php endif; @endphp
                    }
                }
            }
        }
        return valid;
    });
    {{--$('.continue-shopping').on('click', function () {--}}
    {{--window.location.href = "@php echo url('/'); @endphp";--}}
    {{--});--}}
    $(document).on('change', '.checkout.checkout-web .cart-items .row input.cartitemqty , #cart_modal .cart-items input.cartitemqty', function (event) {
       console.log("this part is running");
        var qty = $(this).val();
        console.log(qty);
        var cart_id = $(this).parent().parent().find('span.remove-item').attr('data-id');
        var item_id = $(this).parent().parent().find('span.remove-item').attr('data-cart-item');
        $(this).closest('.cart-items').find('.badge-light').text(qty);
        if(qty != '' && qty != 0 && !isNaN(qty)){
            console.log("section1");
            if($('#coupon_code').val() != ''){
                coupon_code = $('#coupon_code').val();
            } else{
                coupon_code = 'blank';
            }
            url = "@php echo url('/').'/updatecartitem/'; @endphp" + cart_id + '/' + item_id + '/' + qty + '/' + coupon_code;
            $('div.main-loader').css('display','block');
            $.ajax({
                method: 'GET',
                url: url,
                success: function (data) {
                    var jsonTotal = JSON.parse(JSON.stringify(data));
                    console.log(jsonTotal);
                    //$('.right-sec .cart-items').remove();
                    $('.right-sec .total').html('');
                    //$('#cart_modal .cart-items').remove();
                    $('#cart_modal .total').remove();
                    $('#mobile-step-2 .checkout-mob-items ul.cart-mob-items li').remove();
                    var qty = 0;
                    for(var i = 0; i < $('#cart_modal .modal-body .cart-items').length; i++){
                        child = i+1;
                        qty += parseInt($('#cart_modal .modal-body .cart-items:nth-child('+ child +') .cartitemqty').val());
                    }
                    //testing lines
                    $('.right-sec div.total').html('<table class="table m-0"><tbody><tr><td>Total:</td><td class="text-right"><h3><b>$'+ (jsonTotal.total).toFixed(2) +'</b></h3></td></tr></tbody></table>');
                    $('.right-sec div.subtotal table tr:first-child td.text-right').text('$'+ (jsonTotal.subtotal).toFixed(2));
                    $('<div class="total"><div class="row"><div class="col-md-6 col-6 text-left"><h2>Subtotal:</h2></div><div class="col-md-6 col-6 text-right"><h2>$'+ (jsonTotal.subtotal).toFixed(2) +'</h2></div></div></div>').insertAfter('#cart_modal .modal-body.p-0 .cart-items:last-child()');
                    $('#mobile-step-2 .cart-total-mob .row div.col-6:nth-child(2) h6 , #mobile-step-1 .cart-total-mob .row div.col-6:nth-child(2) h6 ').html('$'+(jsonTotal.subtotal).toFixed(2));
                    $('#mobile-step-2 .cart-total-mob .row div.col-6:last-child() h2 , #mobile-step-1 .cart-total-mob .row div.col-6:last-child() h2').html('$'+(jsonTotal.total).toFixed(2));
                    // mobile_tax
                    console.log('mobile_tax_working');
                  //  $('#mobile-step-2 .cart-total-mob .row div.col-6.tax-amount-mob h6 , #mobile-step-1 .cart-total-mob .row div.col-6.tax-amount-mob h6').html('$'+(jsonTotal.total).toFixed(2));
                    $('#mobile-step-2 .discount-text-mob , #mobile-step-1 .discount-text-mob , #mobile-step-2 .discount-amount-mob , #mobile-step-1 .discount-amount-mob').css('display','none');
                    $('tr.discount').css('display','none');
                    $('.discount .text-right').text('-');
                    $('#coupon_code').val('');
                    $('.cancel_coupon').css('display','none');
                    $('#apply_coupon .apply').css({'display':'block','margin-top':'0px'});
                    $('div.main-loader').css('display','none');
                    $('#apply_coupon p').remove();
                            @php if(isMobile()): @endphp
                    var address = $('#add-address-modal #addressline1').val();
                    var city = $('#add-address-modal #city').val();
                    var postcode = $('#add-address-modal #postcode').val();
                    if(address != '' && city != '' && postcode != ''){
                        $('#add-address-modal button.btn.btn-ansel.btn-block.mt-4').trigger('click');
                    }
                            @php else: @endphp
                    var address = $('form.shippingmethodForm #shippingAddress').val();
                    var city = $('form.shippingmethodForm #shippingCity').val();
                    var postcode = $('form.shippingmethodForm #shippingPostCode').val();
                    if(address != '' && city != '' && postcode != ''){
                        if(!$('#pills-customer-info').hasClass('active')){
                            $('form.shippingmethodForm button.continue-to-shipping').trigger('click');
                       console.log('triggering click on continue-to-shipping ');
                        }
                    }
                    @php endif; @endphp
                    productnameresize();
                },
                dataType: 'json'
            });
        } else{
            console.log("section2");
            var remove_item_index = $(this).parent().parent().parent().parent().parent().index();
            $('div.main-loader').css('display','block');
            if($('#coupon_code').val() != ''){
                coupon_code = $('#coupon_code').val();
            } else{
                coupon_code = 'blank';
            }
                    @php if(isset($session['customer_token'])): @endphp
            var data_id = $(this).parent().parent().find('span.remove-item').attr('data-id');
            var cart_item = $(this).parent().parent().find('span.remove-item').attr('data-cart-item');
            data = {'cartId':data_id,'itemId':cart_item,'coupon_code':coupon_code};
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"POST",
                url: '{{url('/')}}/removeItem',
                data: data,
                dataType: 'json',
                success: function (data) {
                    var jsonTotal = JSON.parse(JSON.stringify(data));
                    $('.right-sec .cart-items').remove();
                    $('.right-sec .total').html('');
                    $('#cart_modal .modal-body .cart-items').remove();
                    $('#cart_modal .modal-body .total').remove();
                    $('#mobile-step-2 .checkout-mob-items .cart-mob-items').html('');
                    var qty = 0;
                    if(data.cart.length != '0' ){
                        for (var i = 0; i < data.cart.length; i++) {
                            var counter = data.cart[i];
                            var sku = counter.sku;
                            qty = qty + counter.qty;
                            var replace_name = counter.name.replace(/ /g,"-");
                            replace_name = replace_name.toLowerCase();
                            var product_url = "@php echo url('/'); @endphp/product/"+ replace_name +"/" ;
                            $('#nav .nav-right ul li.nav-item a.nav-link span.badge.badge-light').text(qty);
                            if(sku != 'dummy_product'){
                                var productId = counter.items.productId;
                                var image = counter.items.image;
                                $('#cart_modal .modal-body').append('<div class="cart-items"><div class="col-md-12 p-0"><div class="row"><div class="col-md-3 col-3 pr-0"><a href="'+ product_url + ''+ productId +'"><img class="img-fluid" src="'+image+'"></a></div><div class="col-md-9 col-9"><span id="quote_id" style="display:none;">'+counter.quote_id+'</span><a href="'+ product_url + ''+ productId +'"><h4>'+counter.name+'</h4></a><h6 class="ww">'+counter.sku+'</h6><h6>$'+counter.price+'</h6><span data-id="'+counter.quote_id+'" data-cart-item="'+counter.item_id+'" class="remove remove-item">Remove</span><div class="form-group"><input value="'+counter.qty+'" class="form-control quantity-select cartitemqty" type="tel"><span class="up-counter"><img src="@php echo url('/'); @endphp/public/images/counter-up-black.png" width="8px"></span><span class="down-counter"><img src="@php echo url('/'); @endphp/public/images/counter-down-black.png" width="8px"></span></div></div></div></div></div>');
                                $('<div class="cart-items"><div class="col-md-12 p-0"><div class="row"><div class="col-md-3 col-3 pr-0"><a href="'+ product_url + ''+ productId +'"><img class="img-fluid" src="'+image+'"></a></div><div class="col-md-9 col-9"><span id="quote_id" style="display:none;">'+counter.quote_id+'</span><a href="'+ product_url + ''+ productId +'"><h4>'+counter.name+'</h4></a><h6 class="ww">'+counter.sku+'</h6><h6>$'+counter.price+'</h6><span data-id="'+counter.quote_id+'" data-cart-item="'+counter.item_id+'" class="remove remove-item">Remove</span><div class="form-group"><input value="'+counter.qty+'" class="form-control quantity-select cartitemqty" type="tel"><span class="up-counter"><img src="@php echo url('/'); @endphp/public/images/counter-up-black.png" width="8px"></span><span class="down-counter"><img src="@php echo url('/'); @endphp/public/images/counter-down-black.png" width="8px"></span></div></div></div></div></div>').insertBefore('.right-sec .promo_code');
                                $('#mobile-step-2 .checkout-mob-items .cart-mob-items').append('<li class="list-inline-item"><a href="'+ product_url + ''+ productId +'"><img width="75px" height="75px" src="'+image+'"></a><a href="'+ product_url + ''+ productId +'"><p>'+counter.name+'</p></a></li>');
                            }
                        }
                        $('#nav .nav-right ul li.nav-item a.nav-link span.badge.badge-light').text(qty);
                        $('span#counter_badge').remove();
                        $('<span id="counter_badge"><span class="badge badge-light">'+qty+'</span></span>').insertAfter('.fixed-top.nav-bar-mobile .row div.col-2.text-right:last-child() a img');
                        $('.right-sec div.total').html('<table class="table m-0"><tbody><tr><td>Total:</td><td class="text-right"><h3><b>$'+ (jsonTotal.total).toFixed(2) +'</b></h3></td></tr></tbody></table>');
                        $('.right-sec div.subtotal table tr:first-child td.text-right').text('$'+ (jsonTotal.total).toFixed(2));
                        $('#mobile-step-2 .cart-total-mob .row div.col-6:nth-child(2) h6 , #mobile-step-1 .cart-total-mob .row div.col-6:nth-child(2) h6 ').html('$'+(jsonTotal.subtotal).toFixed(2));
                        $('#mobile-step-2 .cart-total-mob .row div.col-6:last-child() h2 , #mobile-step-1 .cart-total-mob .row div.col-6:last-child() h2').html('$'+(jsonTotal.total).toFixed(2));
                        $('#mobile-step-2 .discount-text-mob , #mobile-step-1 .discount-text-mob , #mobile-step-2 .discount-amount-mob , #mobile-step-1 .discount-amount-mob').css('display','none');
                        $('tr .discount').css('display','none');
                        $('.discount .text-right').text('-');
                        $('#coupon_code').val('');
                        $('.cancel_coupon').css('display','none');
                        $('#apply_coupon .apply').css({'display':'block','margin-top':'0px'});
                        $('div.main-loader').css('display','none');
                        $('#apply_coupon p').remove();
                        var location_href = 'location.href = ';
                        var redirectTo = '<?php echo url("/") . "/checkout"; ?>';
                        $('#cart_modal .modal-header-custom .cart-incentive').remove();
                        if(jsonTotal.total < 75){
                            var less_amount = parseInt(75) - parseInt(jsonTotal.total);
                            $("<p class='cart-incentive'>You're so close. Add another $"+ less_amount +" to your cart for FREE Shipping.</p>").insertAfter('#cart_modal .modal-header-custom h5.heading.text-left');
                        } else{
                            $("<p class='cart-incentive'>Yes! You've qualified for FREE Shipping.</p>").insertAfter('#cart_modal .modal-header-custom h5.heading.text-left');
                        }
                        $('#cart_modal .modal-body').append('<div class="total"><div class="row"><div class="col-md-6 col-6 text-left"><h2>Subtotal:</h2></div><div class="col-md-6 col-6 text-right"><h2>$' + (jsonTotal.subtotal).toFixed(2) + '</h2></div></div></div>');
                        $('#cart_modal .modal-footer-custom').html('');
                        $('#cart_modal .modal-footer-custom').append('<div class="col-md-12"><button type="button" onclick="' + location_href + "'" + redirectTo + "'" + '" class="btn btn-ansel btn-checkout">CHECKOUT</button></div><div class="col-md-12"><h6>Shipping and taxes calculated during checkout.</h6></div>');
                        $('div.main-loader').css('display','none');
                                @php if(isMobile()): @endphp
                        var address = $('#add-address-modal #addressline1').val();
                        var city = $('#add-address-modal #city').val();
                        var postcode = $('#add-address-modal #postcode').val();
                        if(address != '' && city != '' && postcode != ''){
                            $('#add-address-modal button.btn.btn-ansel.btn-block.mt-4').trigger('click');
                        }
                                @php else: @endphp
                        var address = $('form.shippingmethodForm #shippingAddress').val();
                        var city = $('form.shippingmethodForm #shippingCity').val();
                        var postcode = $('form.shippingmethodForm #shippingPostCode').val();
                        if(address != '' && city != '' && postcode != ''){
                            if(!$('#pills-customer-info').hasClass('active')){
                                $('form.shippingmethodForm button.continue-to-shipping').trigger('click');
                            }
                        }
                        @php endif; @endphp
                    } else{
                        window.location.href = '@php url('/'); @endphp/category/plants/3';
                        $('span#counter_badge').remove();
                        $('<span id="counter_badge"><span class="badge badge-light">'+qty+'</span></span>').insertAfter('#nav .nav-right ul li.nav-item:last-child() a img');
                        $('<span id="counter_badge"><span class="badge badge-light">'+qty+'</span></span>').insertAfter('.fixed-top.nav-bar-mobile .row div.col-2.text-right:last-child() a img');
                        $('#cart_modal .modal-body').append('<div class="empty-cart"><p class="m-0">0 products in your cart</p></div>');
                        $('#cart_modal .modal-footer-custom').append('<div class="col-md-12"><button type="button" class="btn btn-ansel btn-continue-shopping" data-dismiss="modal">CONTINUE SHOPPING</button></div><div class="col-md-12"><h6>Shipping and taxes calculated during checkout.</h6></div>');
                        $('.right-sec div.subtotal table td.text-right').text('-');
                    }
                    removeItemSliderMobile(remove_item_index);
                    productnameresize();
                }
            });
                    @php else: @endphp
            var data_id = $(this).parent().parent().find('span.remove-item').attr('data-id');
            var cart_item = $(this).parent().parent().find('span.remove-item').attr('data-cart-item');
            data = {'cartId':data_id,'itemId':cart_item,'coupon_code':coupon_code};
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"POST",
                url: '{{url('/')}}/removeItem',
                data: data,
                dataType: 'json',
                success: function (data) {
                    var jsonTotal = JSON.parse(JSON.stringify(data));
                    console.log(data);
                    $('.right-sec .cart-items').remove();
                    $('.right-sec .total').html('');
                    $('#nav .nav-right ul li.nav-item:nth-child(4) a.nav-link').attr('data-toggle','modal');
                    $('#nav .nav-right ul li.nav-item:nth-child(4) a.nav-link').attr('data-target','#cart_modal');
                    $('#cart_modal .empty-cart').css({'display':'none'});
                    $('#cart_modal .modal-footer-custom').html('');
                    $('#cart_modal .modal-body .cart-items').remove();
                    $('#cart_modal .modal-body .total').remove();
                    $('#mobile-step-2 .checkout-mob-items .cart-mob-items').html('');
                    if(data.cart.length != '0' ) {
                        var qty = 0;
                        for (var i = 0; i < data.cart.length; i++) {
                            var counter = data.cart[i];
                            var sku = counter.sku;
                            qty = qty + counter.qty;
                            var replace_name = counter.name.replace(/ /g,"-");
                            replace_name = replace_name.toLowerCase();
                            var product_url = "@php echo url('/'); @endphp/product/"+ replace_name +"/" ;
                            $('#nav .nav-right ul li.nav-item a.nav-link span.badge.badge-light').text(qty);
                            if(sku != 'dummy_product'){
                                var productId = counter.items.productId;
                                var image = counter.items.image;
                                $('#cart_modal .modal-body').append('<div class="cart-items"><div class="col-md-12 p-0"><div class="row"><div class="col-md-3 col-3 pr-0"><a href="'+ product_url + ''+ productId +'"><img class="img-fluid" src="'+image+'"></a></div><div class="col-md-9 col-9"><span id="quote_id" style="display:none;">'+counter.quote_id+'</span><a href="'+ product_url + ''+ productId +'"><h4>'+counter.name+'</h4></a><h6 class="ww">'+counter.sku+'</h6><h6>$'+counter.price+'</h6><span data-id="'+counter.quote_id+'" data-cart-item="'+counter.item_id+'" class="remove remove-item">Remove</span><div class="form-group"><input value="'+counter.qty+'" class="form-control quantity-select cartitemqty" type="tel"><span class="up-counter"><img src="@php echo url('/'); @endphp/public/images/counter-up-black.png" width="8px"></span><span class="down-counter"><img src="@php echo url('/'); @endphp/public/images/counter-down-black.png" width="8px"></span></div></div></div></div></div>');
                                $('<div class="cart-items"><div class="col-md-12 p-0"><div class="row"><div class="col-md-3 col-3 pr-0"><a href="'+ product_url + ''+ productId +'"><img class="img-fluid" src="'+image+'"></a></div><div class="col-md-9 col-9"><span id="quote_id" style="display:none;">'+counter.quote_id+'</span><a href="'+ product_url + ''+ productId +'"><h4>'+counter.name+'</h4></a><h6 class="ww">'+counter.sku+'</h6><h6>$'+counter.price+'</h6><span data-id="'+counter.quote_id+'" data-cart-item="'+counter.item_id+'" class="remove remove-item">Remove</span><div class="form-group"><input value="'+counter.qty+'" class="form-control quantity-select cartitemqty" type="tel"><span class="up-counter"><img src="@php echo url('/'); @endphp/public/images/counter-up-black.png" width="8px"></span><span class="down-counter"><img src="@php echo url('/'); @endphp/public/images/counter-down-black.png" width="8px"></span></div></div></div></div></div>').insertBefore('.right-sec .promo_code');
                                $('#mobile-step-2 .checkout-mob-items .cart-mob-items').append('<li class="list-inline-item"><a href="'+ product_url + ''+ productId +'"><img width="75px" height="75px" src="'+image+'"></a><a href="'+ product_url + ''+ productId +'"><p>'+counter.name+'</p></a></li>');
                            }
                        }
                        $('#nav .nav-right ul li.nav-item a.nav-link span.badge.badge-light').text(qty);
                        $('span#counter_badge').remove();
                        $('<span id="counter_badge"><span class="badge badge-light">'+qty+'</span></span>').insertAfter('.fixed-top.nav-bar-mobile .row div.col-2.text-right:last-child() a img');
                        $('.right-sec div.total').html('<table class="table m-0"><tbody><tr><td>Total:</td><td class="text-right"><h3><b>$'+ (jsonTotal.total).toFixed(2) +'</b></h3></td></tr></tbody></table>');
                        $('.right-sec div.subtotal table tr:first-child td.text-right').text('$'+ (jsonTotal.total).toFixed(2));
                        $('#mobile-step-2 .cart-total-mob .row div.col-6:nth-child(2) h6 , #mobile-step-1 .cart-total-mob .row div.col-6:nth-child(2) h6 ').html('$'+(jsonTotal.subtotal).toFixed(2));
                        $('#mobile-step-2 .cart-total-mob .row div.col-6:last-child() h2 , #mobile-step-1 .cart-total-mob .row div.col-6:last-child() h2').html('$'+(jsonTotal.total).toFixed(2));
                        $('#mobile-step-2 .discount-text-mob , #mobile-step-1 .discount-text-mob , #mobile-step-2 .discount-amount-mob , #mobile-step-1 .discount-amount-mob').css('display','none');
                        $('tr .discount').css('display','none');
                        $('.discount .text-right').text('-');
                        $('#coupon_code').val('');
                        $('.cancel_coupon').css('display','none');
                        $('#apply_coupon .apply').css({'display':'block','margin-top':'0px'});
                        $('div.main-loader').css('display','none');
                        $('#apply_coupon p').remove();
                        var location_href = 'location.href = ';
                        var redirectTo = '<?php echo url("/") . "/checkout"; ?>';
                        $('#cart_modal .modal-header-custom .cart-incentive').remove();
                        if(jsonTotal.total < 75){
                            var less_amount = parseInt(75) - parseInt(jsonTotal.total);
                            $("<p class='cart-incentive'>You're so close. Add another $"+ less_amount +" to your cart for FREE Shipping.</p>").insertAfter('#cart_modal .modal-header-custom h5.heading.text-left');
                        } else{
                            $("<p class='cart-incentive'>Yes! You've qualified for FREE Shipping.</p>").insertAfter('#cart_modal .modal-header-custom h5.heading.text-left');
                        }
                        $('#cart_modal .modal-body').append('<div class="total"><div class="row"><div class="col-md-6 col-6 text-left"><h2>Subtotal:</h2></div><div class="col-md-6 col-6 text-right"><h2>$' + (jsonTotal.subtotal).toFixed(2) + '</h2></div></div></div>');
                        $('#cart_modal .modal-footer-custom').html('');
                        $('#cart_modal .modal-footer-custom').append('<div class="col-md-12"><button type="button" onclick="' + location_href + "'" + redirectTo + "'" + '" class="btn btn-ansel btn-checkout">CHECKOUT</button></div><div class="col-md-12"><h6>Shipping and taxes calculated during checkout.</h6></div>');
                        $('div.main-loader').css('display','none');
                                @php if(isMobile()): @endphp
                        var address = $('#add-address-modal #addressline1').val();
                        var city = $('#add-address-modal #city').val();
                        var postcode = $('#add-address-modal #postcode').val();
                        if(address != '' && city != '' && postcode != ''){
                            $('#add-address-modal button.btn.btn-ansel.btn-block.mt-4').trigger('click');
                        }
                                @php else: @endphp
                        var address = $('form.shippingmethodForm #shippingAddress').val();
                        var city = $('form.shippingmethodForm #shippingCity').val();
                        var postcode = $('form.shippingmethodForm #shippingPostCode').val();
                        if(address != '' && city != '' && postcode != ''){
                            if(!$('#pills-customer-info').hasClass('active')){
                                $('form.shippingmethodForm button.continue-to-shipping').trigger('click');
                            }
                        }
                        @php endif; @endphp
                    }else{
                        window.location.href = '@php echo url('/'); @endphp/category/plants/3';
                        $('span#counter_badge').remove();
                        $('<span id="counter_badge"><span class="badge badge-light">'+qty+'</span></span>').insertAfter('#nav .nav-right ul li.nav-item:last-child() a img');
                        $('<span id="counter_badge"><span class="badge badge-light">'+qty+'</span></span>').insertAfter('.fixed-top.nav-bar-mobile .row div.col-2.text-right:last-child() a img');
                        $('#cart_modal .modal-body').append('<div class="empty-cart"><p class="m-0">0 products in your cart</p></div>');
                        $('.right-sec div.subtotal table td.text-right').text('-');
                    }
                    removeItemSliderMobile(remove_item_index);
                    productnameresize();
                }
            });
            @php endif; @endphp
        }

    });
    function removeItemSliderMobile(remove_item_index){
        var remove_index = remove_item_index + 1;
        console.log(remove_index + ' nth child ');
        $('ul.cart-mob-items li:nth-child('+ remove_index +')').remove();
    }
    $(document).on('click','.continue-shopping',function(){
        window.location.href = '@php echo url("/") @endphp';
    });
    $(document).on('click','form.referral-form .referral-send',function(){
        $('form.referral-form .error').remove();
        valid = false;
        var ref_email = $('form.referral-form #referral-email').val();
        var ref_message = $('form.referral-form #referral-message').val();
        var sender_email = $('.shippingmethodForm #shippingEmail').val();
        var regEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
        var multipleEmail = /^(\s?[^\s,]+@[^\s,]+\.[^\s,]+\s?,)*(\s?[^\s,]+@[^\s,]+\.[^\s,]+)$/;
        //var generated_referral_code = localStorage.getItem("generated_referral_code");
        if(ref_email == ''){
            valid = false;
            $('<p class="error" style="color:red;">This is required field.</p>').insertAfter('form.referral-form #referral-email');
            if(ref_message == '') {
                valid = false;
                $('<p class="error" style="color:red;">This is required field.</p>').insertAfter('form.referral-form #referral-message');
            }
        } else if(ref_message == ''){
            valid = false;
            $('<p class="error" style="color:red;">This is required field.</p>').insertAfter('form.referral-form #referral-message');
        } /*else if(ref_email != '' && !regEmail.test(ref_email)){
            valid = false;
            $('<p class="error" style="color:red;">Please Enter valid Email.</p>').insertAfter('form.referral-form #referral-email');
            if(ref_message == '') {
                valid = false;
                $('<p class="error" style="color:red;">This is required field.</p>').insertAfter('form.referral-form #referral-message');
            }
        }*/ else if(ref_email != '' && !multipleEmail.test(ref_email)){
            valid = false;
            $('<p class="error" style="color:red;">Please Enter valid comma separated Emails.</p>').insertAfter('form.referral-form #referral-email');
        } else{
            valid = true;
            //$('div.main-loader').css('display','block');
            $('form.referral-form .referral-send').addClass('disabled').attr('disabled');
            ref_emails = ref_email.split(',');
            for (var i = 0; i < ref_emails.length; i++) {
                var ref_email = ref_emails[i].replace(' ','');
                coupon_code = Math.random().toString(36).substr(2, 8).toUpperCase();
                generateCouponCode(coupon_code,sender_email,ref_email,ref_message);
                if(i == (ref_emails.length - 1)){
                    $('div.main-loader').css('display','none');
                }
            }
        }
        return valid;
    });

    function generateCouponCode(coupon_code,sender_email,ref_email,ref_message){
        referral_data = {'couponcode':coupon_code,'rule_id':'1','type':'1'};
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url:'@php echo url('/').'/generatecouponcode'; @endphp',
            method: 'POST',
            dataType:'json',
            data: referral_data,
            success: function(coupon_data){
                var generated_referral_code = coupon_data.code;
                referralCodeSend(sender_email,ref_email,ref_message,generated_referral_code);
            }
        });
    }
    function referralCodeSend(sender_email,ref_email,ref_message,generated_referral_code){
        referralcode_data = {'senderemail':sender_email,'receiveremail':ref_email,'message':ref_message,'referral_code':generated_referral_code};
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url : '@php echo url('/')."/referralcode"; @endphp',
            method : 'POST',
            data: referralcode_data,
            success: function(referralcode_data){
                $('form.referral-form .referral-send').removeClass('disabled').removeAttr('disabled');
                $('#refer-friend').modal('hide');
                $('form.referral-form').trigger("reset");
                data = {'receiver_email':ref_email,'coupopn_code':generated_referral_code};
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url:'@php echo url('/').'/ref_data'; @endphp',
                    method: 'POST',
                    dataType:'json',
                    data: data,
                    success: function(ref_data){
                        console.log(ref_data);
                    }
                });
                $('.alert.alert-warning.alert-dismissible').css('display','none');
            }
        });
    }

    //localStorage.removeItem("generated_referral_code");
    //ocalStorage.removeItem("generated_coupon_code");
    $(document).ready(function () {
        var email = window.location.href;
        var email = email.split('?');
        var email = email[1];
        if(email != '' && email != undefined){
            $('form.mobile_guestEmailform #guestEmail').val();
        }
    });

    $(document).on('click','#address-details button.btn.btn-ansel.btn-block.mt-4',function(){
        var valid = false;
        var regName = /^[a-z ,.'-]+$/i;
        var regEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
        $('.error').remove();
        if (!$('#address-details #firstname').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#address-details #firstname');
        } else if (!$('#address-details #lastname').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#address-details #lastname');
        } else if (!$('#address-details #addressline1').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#address-details #addressline1');
        } else if (!$('#address-details #city').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#address-details #city');
        } else if (!$('#address-details #country').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#address-details #country');
        } else if (!$('#address-details #postcode').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#address-details #postcode');
        } else if ($('#address-details #firstname').val() && !regName.test($('#address-details #firstname').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter('#firstname');
        } else if ($('#address-details #lastname').val() && !regName.test($('#address-details #lastname').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter('#address-details #lastname');
        } else if ($('#address-details #city').val() && !regName.test($('#address-details #city').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid city</p>').insertAfter('#address-details #city');
        } else{
            if($('#mobile-step-2 div.col-12.checkout-cards input[name=have_recipient]').is(':checked')) {
                var recipientName = $('#address-details #add-recipient-modal #recipient-name').val();
                var recipientEmail = $('#address-details #add-recipient-modal #recipient-email').val();
                var recipientMessage = $('#address-details #add-recipient-modal #recipient-message').val();
                if(recipientName == ''){
                    $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#address-details #add-recipient-modal #recipient-name');
                    valid = false;
                } else if(recipientEmail == ''){
                    $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#address-details #add-recipient-modal #recipient-email');
                    valid = false;
                } else if(recipientMessage == ''){
                    $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#address-details #add-recipient-modal #recipient-message');
                    valid = false;
                } else if (recipientName && !regName.test(recipientName)) {
                    valid = false;
                    $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter('#address-details #add-recipient-modal #recipient-name');
                } else if (recipientEmail && !regEmail.test(recipientEmail)) {
                    valid = false;
                    $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter('#address-details #add-recipient-modal #recipient-email');
                } else{
                    valid = true;
                    //$('div.main-loader').css('display','block');
                    $('#address-details button.btn.btn-ansel.btn-block.mt-4').addClass('disabled').attr('disabled','disabled');
                    var email = $('form.mobile_guestEmailform #guestEmail').val();
                    var firstname = $('#address-details #firstname').val();
                    var lastname = $('#address-details #lastname').val();
                    var addressline1 = $('#address-details #addressline1').val();
                    var addressline2 = $('#address-details #addressline2').val();
                    var city = $('#address-details #city').val();
                    var country = $('#address-details #country').val();
                    var postcode = $('#address-details #postcode').val();
                    var region_id = $('#address-details .select.state.select-hidden option:selected').val();
                    var state = $('#address-details .select.state.select-hidden option:selected').text();
                    var samebilling = 1;
                    var quote_id = '@php echo $quote_id @endphp';
                    var telephone = $('#address-details #telephone').val();

                    var data = {"quote_id": quote_id,
                        "email": email,
                        "firstname": firstname,
                        "lastname": lastname,
                        "address": addressline1,
                        "addressline2": addressline2,
                        "city": city,
                        "state": state,
                        "region_id": region_id,
                        "country": "US",
                        "postcode": postcode,
                        "receivername": recipientName,
                        "receiveremail": recipientEmail,
                        "message": recipientMessage,
                        "same_as_billing": samebilling,
                        "telephone": telephone};

                    valid = true;
                            @php if(isset($_COOKIE["customer_token"])): @endphp
                            @php if(!key_exists('message',$customerData)): @endphp
                    var url = '@php echo url('/').'/estimateshipping'; @endphp';
                            @php else: @endphp
                    var url = '@php echo url('/').'/estimateguestshipping'; @endphp';
                            @php endif; @endphp
                            @php else: @endphp
                    var url = '@php echo url('/').'/estimateguestshipping'; @endphp';
                    @php endif; @endphp
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: "POST",
                        url: url,
                        data: data,
                        success: function (data) {
                            var jsonData = JSON.parse(JSON.stringify(data));
                            var shipping_method = '';
                            for (var i = 0; i < jsonData.length; i++) {
                                var counter = jsonData[i];
                                if(i == 0){
                                    if(counter['amount'] == '0'){
                                        shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" checked="checked" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>Free</p></div>';
                                    }else{
                                        shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" checked="checked" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>$'+ counter['amount'] +'</p></div>';
                                    }
                                }else{
                                    if(counter['amount'] == '0'){
                                        shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>Free</p></div>';
                                    }else {
                                        shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>$' + counter['amount'] + '</p></div>';
                                    }
                                }
                            }
                            $('#shipping_append').html(shipping_method);
                            $('.disabled_div').remove();
                            var method_code = $('#shipping_append input[name=shipping-method]:checked').val();
                            var career_code = $('#shipping_append input[name=shipping-method]:checked').val();
                            var data = {
                                "quote_id": quote_id,
                                "email": email,
                                "firstname": firstname,
                                "lastname": lastname,
                                "address": addressline1,
                                "addressline2": addressline2,
                                "city": city,
                                "state": state,
                                "region_id": region_id,
                                "country": "US",
                                "postcode": postcode,
                                "receivername": recipientName,
                                "receiveremail": recipientEmail,
                                "message": recipientMessage,
                                "shippingCarrierCode": career_code,
                                "shippingMethodCode": method_code,
                                "telephone": telephone
                            };

                            var url = '@php echo url('/').'/shippinginformation'; @endphp';
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (data) {
                                    $('#pills-shipping-method .continue-to-payment').removeClass('disabled').removeAttr('disabled');
                                    var jsonData = JSON.parse(JSON.stringify(data));
                                    var string = '';
                                    for (var i = 0; i < jsonData['payment_methods'].length; i++) {
                                        var counter = jsonData['payment_methods'][i];
                                        if(i == 0){
                                            string += '<div class="billing-mob"><label class="custom-radio">' + counter['title'] + '<input checked="checked" name="payment-method" value="' + counter['code'] + '" type="radio" methodlabel="' + counter['title'] + '" required><span class="checkmark"></span></label></div>';
                                        } else{
                                            string += '<div class="billing-mob"><label class="custom-radio">' + counter['title'] + '<input name="payment-method" value="' + counter['code'] + '" type="radio" methodlabel="' + counter['title'] + '" required><span class="checkmark"></span></label></div>';
                                        }
                                    }
                                    $('#payment_method_mob').html(string);
                                    var billing_method = '<div class="div-divider"></div><h6>Billing Address</h6><div class="billing-mob"><label class="custom-radio">Same as shipping address<input type="radio" checked="checked" name="radio"><span class="checkmark"></span></label></div><div class="billing-mob border-0"><label class="custom-radio">Use a different billing address<input type="radio" name="radio"><span class="checkmark"></span></label></div>';
                                    $('#billing_method_mob').html(billing_method);
                                    $('.col-md-12.cart-total-mob .row div:nth-child(2) h6').text('$'+(jsonData['totals'].base_subtotal).toFixed(2));
                                    if(jsonData['totals'].shipping_amount == '0'){
                                        $('.col-md-12.cart-total-mob .row div:nth-child(4) h6').text('Free');
                                    }else{
                                        $('.col-md-12.cart-total-mob .row div:nth-child(4) h6').text('$'+(jsonData['totals'].shipping_amount).toFixed(2));
                                    }
                                    $('.col-md-12.cart-total-mob .row div:nth-child(4)').css('display','block');
                                    $('.col-md-12.cart-total-mob .row div:nth-child(3)').css('display','block');
                                    $('.col-md-12.cart-total-mob .row div:last-child h2').text('$'+(jsonData['totals'].grand_total).toFixed(2));
                                    $('.cart-total-mob button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled');
                                    var address_string = '<h6>Customer Info</h6><span class="change"><a href="javascript:void(0);" data-toggle="modal" data-target="#add-address-modal">Change</a></span><p>'+ firstname + ' ' + lastname +'</p><p>'+ addressline1 + ' ' + addressline2 +'</p><p>'+ city +', '+ state + ', ' + postcode +'</p><p>'+ country +'</p><p>'+ telephone +'</p><label class="custom-checkbox mb-3">This is a Gift<input type="checkbox" checked="checked" name="have_recipient"><span class="checkmark"></span></label><button type="button" class="btn btn-ansel btn-block add_recipient_info">Edit Recipient info</button>';
                                    $('.checkout-mobile .row div#mobile-step-2 div:nth-child(2) div.col-12.checkout-cards:nth-child(2)').html(address_string).addClass('customer-info-mob');
                                    //$('#add-address-modal').modal('hide');
                                    $('.modal#add-address-modal .modal-body #firstname').val(firstname);
                                    $('.modal#add-address-modal .modal-body #lastname').val(lastname);
                                    $('.modal#add-address-modal .modal-body #addressline1').val(addressline1);
                                    $('.modal#add-address-modal .modal-body #addressline2').val(addressline2);
                                    $('.modal#add-address-modal .modal-body #city').val(city);
                                    $('.modal#add-address-modal .modal-body .select option[value="' + state + '"]').prop('selected', true);
                                    $('.modal#add-address-modal .modal-body .select-styled').html($('#add-address-modal .modal-body .select option[data-title="' + state + '"]').text());
                                    $('.modal#add-address-modal .modal-body #postcode').val(postcode);
                                    $('.modal#add-address-modal .modal-body #telephone').val(telephone);
                                    $('.modal#add-recipient-modal #recipient-name').val(recipientName);
                                    $('.modal#add-recipient-modal #recipient-email').val(recipientEmail);
                                    $('.modal#add-recipient-modal #recipient-message').val(recipientMessage);
                                    $('.disabled_div').remove();
                                    $('#address-details button.btn.btn-ansel.btn-block.mt-4').removeClass('disabled').removeAttr('disabled');
                                    $('div.main-loader').css('display','none');
                                },
                                dataType:'json'
                            });
                        },
                        dataType:'json'
                    });
                }
            } else{
                $('#address-details button.btn.btn-ansel.btn-block.mt-4').addClass('disabled').attr('disabled','disabled');
                //$('div.main-loader').css('display','block');
                var email = $('form.mobile_guestEmailform #guestEmail').val();
                var firstname = $('#address-details #firstname').val();
                var lastname = $('#address-details #lastname').val();
                var addressline1 = $('#address-details #addressline1').val();
                var addressline2 = $('#address-details #addressline2').val();
                var city = $('#address-details #city').val();
                var country = $('#address-details #country').val();
                var postcode = $('#address-details #postcode').val();
                var region_id = $('#address-details .select.state.select-hidden option:selected').val();
                var state = $('#address-details .select.state.select-hidden option:selected').text();
                var samebilling = 1;
                var quote_id = '@php echo $quote_id @endphp';
                var telephone = $('#address-details #telephone').val();

                var data = {"quote_id": quote_id,
                    "email": email,
                    "firstname": firstname,
                    "lastname": lastname,
                    "address": addressline1,
                    "addressline2": addressline2,
                    "city": city,
                    "state": state,
                    "region_id": region_id,
                    "country": "US",
                    "postcode": postcode,
                    "receivername": "",
                    "receiveremail": "",
                    "message": "",
                    "same_as_billing": samebilling,
                    "telephone": telephone};

                valid = true;
                        @php if(isset($_COOKIE["customer_token"])): @endphp
                        @php if(!key_exists('message',$customerData)): @endphp
                var url = '@php echo url('/').'/estimateshipping'; @endphp';
                        @php else: @endphp
                var url = '@php echo url('/').'/estimateguestshipping'; @endphp';
                        @php endif; @endphp
                        @php else: @endphp
                var url = '@php echo url('/').'/estimateguestshipping'; @endphp';
                @php endif; @endphp
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (data) {
                        var jsonData = JSON.parse(JSON.stringify(data));
                        var shipping_method = '';
                        for (var i = 0; i < jsonData.length; i++) {
                            var counter = jsonData[i];
                            if(i == 0){
                                if(counter['amount'] == '0'){
                                    shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" checked="checked" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>Free</p></div>';
                                }else{
                                    shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" checked="checked" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>$'+ counter['amount'] +'</p></div>';
                                }
                            }else{
                                if(counter['amount'] == '0'){
                                    shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>Free</p></div>';
                                }else {
                                    shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>$' + counter['amount'] + '</p></div>';
                                }
                            }
                        }
                        $('#shipping_append').html(shipping_method);
                        $('.disabled_div').remove();
                        var method_code = $('#shipping_append input[name=shipping-method]:checked').val();
                        var career_code = $('#shipping_append input[name=shipping-method]:checked').val();
                        var data = {
                            "quote_id": quote_id,
                            "email": email,
                            "firstname": firstname,
                            "lastname": lastname,
                            "address": addressline1,
                            "addressline2": addressline2,
                            "city": city,
                            "state": state,
                            "region_id": region_id,
                            "country": "US",
                            "postcode": postcode,
                            "receivername": '',
                            "receiveremail": '',
                            "message": '',
                            "shippingCarrierCode": career_code,
                            "shippingMethodCode": method_code,
                            "telephone": telephone
                        };

                        var url = '@php echo url('/').'/shippinginformation'; @endphp';
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            type: "POST",
                            url: url,
                            data: data,
                            success: function (data) {
                                $('#pills-shipping-method .continue-to-payment').removeClass('disabled').removeAttr('disabled');
                                var jsonData = JSON.parse(JSON.stringify(data));
                                var string = '';
                                for (var i = 0; i < jsonData['payment_methods'].length; i++) {
                                    var counter = jsonData['payment_methods'][i];
                                    if(i == 0){
                                        string += '<div class="billing-mob"><label class="custom-radio">' + counter['title'] + '<input checked="checked" name="payment-method" value="' + counter['code'] + '" type="radio" methodlabel="' + counter['title'] + '" required><span class="checkmark"></span></label></div>';
                                    } else{
                                        string += '<div class="billing-mob"><label class="custom-radio">' + counter['title'] + '<input name="payment-method" value="' + counter['code'] + '" type="radio" methodlabel="' + counter['title'] + '" required><span class="checkmark"></span></label></div>';
                                    }
                                }
                                $('#payment_method_mob').html(string);
                                var billing_method = '<div class="div-divider"></div><h6>Billing Address</h6><div class="billing-mob"><label class="custom-radio">Same as shipping address<input type="radio" checked="checked" name="radio"><span class="checkmark"></span></label></div><div class="billing-mob border-0"><label class="custom-radio">Use a different billing address<input type="radio" name="radio"><span class="checkmark"></span></label></div>';
                                $('#billing_method_mob').html(billing_method);
                                $('.col-md-12.cart-total-mob .row div:nth-child(2) h6').text('$'+(jsonData['totals'].base_subtotal).toFixed(2));
                                if(jsonData['totals'].shipping_amount == '0'){
                                    $('.col-md-12.cart-total-mob .row div:nth-child(4) h6').text('Free');
                                }else{
                                    $('.col-md-12.cart-total-mob .row div:nth-child(4) h6').text('$'+(jsonData['totals'].shipping_amount).toFixed(2));
                                }
                                $('.col-md-12.cart-total-mob .row div:nth-child(4)').css('display','block');
                                $('.col-md-12.cart-total-mob .row div:nth-child(3)').css('display','block');
                                $('.col-md-12.cart-total-mob .row div:last-child h2').text('$'+(jsonData['totals'].grand_total).toFixed(2));
                                $('.cart-total-mob button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled');
                                var address_string = '<h6>Customer Info</h6><span class="change"><a href="javascript:void(0);" data-toggle="modal" data-target="#add-address-modal">Change</a></span><p>'+ firstname + ' ' + lastname +'</p><p>'+ addressline1 + ' ' + addressline2 +'</p><p>'+ city +', '+ state + ', ' + postcode +'</p><p>'+ country +'</p><p>'+ telephone +'</p><label class="custom-checkbox mb-3">This is a Gift<input type="checkbox" name="have_recipient"><span class="checkmark"></span></label><button type="button" class="btn btn-ansel btn-block add_recipient_info">Add Recipient info</button>';
                                $('.checkout-mobile .row div#mobile-step-2 div:nth-child(2) div.col-12.checkout-cards:nth-child(2)').html(address_string).addClass('customer-info-mob');
                                //$('#add-address-modal').modal('hide');
                                $('.modal#add-address-modal .modal-body #firstname').val(firstname);
                                $('.modal#add-address-modal .modal-body #lastname').val(lastname);
                                $('.modal#add-address-modal .modal-body #addressline1').val(addressline1);
                                $('.modal#add-address-modal .modal-body #addressline2').val(addressline2);
                                $('.modal#add-address-modal .modal-body #city').val(city);
                                $('.modal#add-address-modal .modal-body .select option[value="' + state + '"]').prop('selected', true);
                                $('.modal#add-address-modal .modal-body .select-styled').html($('#add-address-modal .modal-body .select option[data-title="' + state + '"]').text());
                                $('.modal#add-address-modal .modal-body #postcode').val(postcode);
                                $('.modal#add-address-modal .modal-body #telephone').val(telephone);
                                $('.disabled_div').remove();
                                $('#address-details button.btn.btn-ansel.btn-block.mt-4').removeClass('disabled').removeAttr('disabled');
                                $('div.main-loader').css('display','none');
                            },
                            dataType:'json'
                        });
                    },
                    dataType:'json'
                });
            }

        }
        return valid;
    });

    $(document).on('click','.modal#add-address-modal button.btn.btn-ansel.btn-block.mt-4',function(){
        var valid = false;
        var regName = /^[a-z ,.'-]+$/i;
        var regEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
        $('.error').remove();
        if (!$('.modal#add-address-modal .modal-body #firstname').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('.modal#add-address-modal .modal-body #firstname');
        } else if (!$('.modal#add-address-modal .modal-body #lastname').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('.modal#add-address-modal .modal-body #lastname');
        } else if (!$('.modal#add-address-modal .modal-body #addressline1').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('.modal#add-address-modal .modal-body #addressline1');
        } else if (!$('.modal#add-address-modal .modal-body #city').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('.modal#add-address-modal .modal-body #city');
        } else if (!$('.modal#add-address-modal .modal-body #country').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('.modal#add-address-modal .modal-body #country');
        } else if (!$('.modal#add-address-modal .modal-body #postcode').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('.modal#add-address-modal .modal-body #postcode');
        } else if ($('.modal#add-address-modal .modal-body #firstname').val() && !regName.test($('.modal#add-address-modal .modal-body #firstname').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter('.modal#add-address-modal .modal-body #firstname');
        } else if ($('.modal#add-address-modal .modal-body #lastname').val() && !regName.test($('.modal#add-address-modal .modal-body #lastname').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter('.modal#add-address-modal .modal-body #lastname');
        } else if ($('.modal#add-address-modal .modal-body #city').val() && !regName.test($('.modal#add-address-modal .modal-body #city').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid city</p>').insertAfter('.modal#add-address-modal .modal-body #city');
        } else{
            if($('#mobile-step-2 div.col-12.checkout-cards input[name=have_recipient]').is(':checked')) {
                var recipientName = $('#add-address-modal #add-recipient-modal #recipient-name').val();
                var recipientEmail = $('#add-address-modal #add-recipient-modal #recipient-email').val();
                var recipientMessage = $('#add-address-modal #add-recipient-modal #recipient-message').val();
                if(recipientName == ''){
                    $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#add-address-modal #add-recipient-modal #recipient-name');
                    valid = false;
                } else if(recipientEmail == ''){
                    $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#add-address-modal #add-recipient-modal #recipient-email');
                    valid = false;
                } else if(recipientMessage == ''){
                    $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#add-address-modal #add-recipient-modal #recipient-message');
                    valid = false;
                } else if (recipientName && !regName.test(recipientName)) {
                    valid = false;
                    $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter('#add-address-modal #add-recipient-modal #recipient-name');
                } else if (recipientEmail && !regEmail.test(recipientEmail)) {
                    valid = false;
                    $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter('#add-address-modal #add-recipient-modal #recipient-email');
                } else{
                    valid = true;
                    $('.modal#add-address-modal button.btn.btn-ansel.btn-block.mt-4').addClass('disabled').attr('disabled','disabled');
                    //$('div.main-loader').css('display','block');
                    var email = $('form.mobile_guestEmailform #guestEmail').val();
                    var firstname = $('.modal#add-address-modal .modal-body #firstname').val();
                    var lastname = $('.modal#add-address-modal .modal-body #lastname').val();
                    var addressline1 = $('.modal#add-address-modal .modal-body #addressline1').val();
                    var addressline2 = $('.modal#add-address-modal .modal-body #addressline2').val();
                    var city = $('.modal#add-address-modal .modal-body #city').val();
                    var country = $('.modal#add-address-modal .modal-body #country').val();
                    var postcode = $('.modal#add-address-modal .modal-body #postcode').val();
                    var region_id = $('.modal#add-address-modal .modal-body .select.state.select-hidden option:selected').val();
                    var state = $('.modal#add-address-modal .modal-body .select.state.select-hidden option:selected').text();
                    var samebilling = 1;
                    var quote_id = '@php echo $quote_id @endphp';
                    var telephone = $('.modal#add-address-modal .modal-body #telephone').val();

                    var data = {"quote_id": quote_id,
                        "email": email,
                        "firstname": firstname,
                        "lastname": lastname,
                        "address": addressline1,
                        "addressline2": addressline2,
                        "city": city,
                        "state": state,
                        "region_id": region_id,
                        "country": "US",
                        "postcode": postcode,
                        "receivername": "",
                        "receiveremail": "",
                        "message": "",
                        "same_as_billing": samebilling,
                        "telephone": telephone};
                    valid = true;
                            @php if(isset($_COOKIE["customer_token"])): @endphp
                            @php if(!key_exists('message',$customerData)): @endphp
                    var url = '@php echo url('/').'/estimateshipping'; @endphp';
                            @php else: @endphp
                    var url = '@php echo url('/').'/estimateguestshipping'; @endphp';
                            @php endif; @endphp
                            @php else: @endphp
                    var url = '@php echo url('/').'/estimateguestshipping'; @endphp';
                    @php endif; @endphp
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: "POST",
                        url: url,
                        data: data,
                        success: function (data) {
                            var jsonData = JSON.parse(JSON.stringify(data));
                            var shipping_method = '';
                            for (var i = 0; i < jsonData.length; i++) {
                                var counter = jsonData[i];
                                if(i == 0){
                                    if(counter['amount'] == '0'){
                                        shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" checked="checked" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>Free</p></div>';
                                    }else{
                                        shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" checked="checked" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>$'+ counter['amount'] +'</p></div>';
                                    }
                                }else{
                                    if(counter['amount'] == '0'){
                                        shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>Free</p></div>';
                                    }else {
                                        shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>$' + counter['amount'] + '</p></div>';
                                    }
                                }
                            }
                            $('#shipping_append').html(shipping_method);
                            var method_code = $('#shipping_append input[name=shipping-method]:checked').val();
                            var career_code = $('#shipping_append input[name=shipping-method]:checked').val();
                            var data = {
                                "quote_id": quote_id,
                                "email": email,
                                "firstname": firstname,
                                "lastname": lastname,
                                "address": addressline1,
                                "addressline2": addressline2,
                                "city": city,
                                "state": state,
                                "region_id": region_id,
                                "country": "US",
                                "postcode": postcode,
                                "receivername": '',
                                "receiveremail": '',
                                "message": '',
                                "shippingCarrierCode": career_code,
                                "shippingMethodCode": method_code,
                                "telephone": telephone
                            };

                            var url = '@php echo url('/').'/shippinginformation'; @endphp';
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (data) {
                                    $('#pills-shipping-method .continue-to-payment').removeClass('disabled').removeAttr('disabled');
                                    var jsonData = JSON.parse(JSON.stringify(data));
                                    var string = '';
                                    for (var i = 0; i < jsonData['payment_methods'].length; i++) {
                                        var counter = jsonData['payment_methods'][i];
                                        if(i == 0){
                                            string += '<div class="billing-mob"><label class="custom-radio">' + counter['title'] + '<input checked="checked" name="payment-method" value="' + counter['code'] + '" type="radio" methodlabel="' + counter['title'] + '" required><span class="checkmark"></span></label></div>';
                                        } else{
                                            string += '<div class="billing-mob"><label class="custom-radio">' + counter['title'] + '<input name="payment-method" value="' + counter['code'] + '" type="radio" methodlabel="' + counter['title'] + '" required><span class="checkmark"></span></label></div>';
                                        }
                                    }
                                    $('#payment_method_mob').html(string);
                                    var billing_method = '<div class="div-divider"></div><h6>Billing Address</h6><div class="billing-mob"><label class="custom-radio">Same as shipping address<input type="radio" checked="checked" name="radio"><span class="checkmark"></span></label></div><div class="billing-mob border-0"><label class="custom-radio">Use a different billing address<input type="radio" name="radio"><span class="checkmark"></span></label></div>';
                                    $('#billing_method_mob').html(billing_method);
                                    $('.col-md-12.cart-total-mob .row div:nth-child(2) h6').text('$'+(jsonData['totals'].base_subtotal).toFixed(2));
                                    if(jsonData['totals'].shipping_amount == '0'){
                                        $('.col-md-12.cart-total-mob .row div:nth-child(4) h6').text('Free');
                                    }else{
                                        $('.col-md-12.cart-total-mob .row div:nth-child(4) h6').text('$'+(jsonData['totals'].shipping_amount).toFixed(2));
                                    }
                                    $('.col-md-12.cart-total-mob .row div:nth-child(4)').css('display','block');
                                    $('.col-md-12.cart-total-mob .row div:nth-child(3)').css('display','block');
                                    $('.col-md-12.cart-total-mob .row div:last-child h2').text('$'+(jsonData['totals'].grand_total).toFixed(2));
                                    $('.cart-total-mob button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled');
                                    var address_string = '<h6>Customer Info</h6><span class="change"><a href="javascript:void(0);" data-toggle="modal" data-target="#add-address-modal">Change</a></span><p>'+ firstname + ' ' + lastname +'</p><p>'+ addressline1 + ' ' + addressline2 +'</p><p>'+ city +', '+ state + ', ' + postcode +'</p><p>'+ country +'</p><p>'+ telephone +'</p>';
                                    $('.checkout-mobile .row div#mobile-step-2 div:nth-child(2) div.col-12.checkout-cards:nth-child(2)').html(address_string).addClass('customer-info-mob');
                                    $('#add-address-modal').modal('hide');
                                    $('.modal#add-address-modal .modal-body #firstname').val(firstname);
                                    $('.modal#add-address-modal .modal-body #lastname').val(lastname);
                                    $('.modal#add-address-modal .modal-body #addressline1').val(addressline1);
                                    $('.modal#add-address-modal .modal-body #addressline2').val(addressline2);
                                    $('.modal#add-address-modal .modal-body #city').val(city);
                                    $('.modal#add-address-modal .modal-body .select option[value="' + state + '"]').prop('selected', true);
                                    $('.modal#add-address-modal .modal-body .select-styled').html($('#add-address-modal .modal-body .select option[data-title="' + state + '"]').text());
                                    $('.modal#add-address-modal .modal-body #postcode').val(postcode);
                                    $('.modal#add-address-modal .modal-body #telephone').val(telephone);
                                    $('.modal#add-address-modal button.btn.btn-ansel.btn-block.mt-4').removeClass('disabled').removeAttr('disabled');
                                    $('div.main-loader').css('display','none');
                                    $('div.main-loader').css('display','none');
                                },
                                dataType:'json'
                            });
                        },
                        dataType:'json'
                    });
                }
            } else{
                $('.modal#add-address-modal button.btn.btn-ansel.btn-block.mt-4').addClass('disabled').attr('disabled','disabled');
                //$('div.main-loader').css('display','block');
                var email = $('form.mobile_guestEmailform #guestEmail').val();
                var firstname = $('.modal#add-address-modal .modal-body #firstname').val();
                var lastname = $('.modal#add-address-modal .modal-body #lastname').val();
                var addressline1 = $('.modal#add-address-modal .modal-body #addressline1').val();
                var addressline2 = $('.modal#add-address-modal .modal-body #addressline2').val();
                var city = $('.modal#add-address-modal .modal-body #city').val();
                var country = $('.modal#add-address-modal .modal-body #country').val();
                var postcode = $('.modal#add-address-modal .modal-body #postcode').val();
                var region_id = $('.modal#add-address-modal .modal-body .select.state.select-hidden option:selected').val();
                var state = $('.modal#add-address-modal .modal-body .select.state.select-hidden option:selected').text();
                var samebilling = 1;
                var quote_id = '@php echo $quote_id @endphp';
                var telephone = $('.modal#add-address-modal .modal-body #telephone').val();

                var data = {"quote_id": quote_id,
                    "email": email,
                    "firstname": firstname,
                    "lastname": lastname,
                    "address": addressline1,
                    "addressline2": addressline2,
                    "city": city,
                    "state": state,
                    "region_id": region_id,
                    "country": "US",
                    "postcode": postcode,
                    "receivername": "",
                    "receiveremail": "",
                    "message": "",
                    "same_as_billing": samebilling,
                    "telephone": telephone};
                valid = true;
                        @php if(isset($_COOKIE["customer_token"])): @endphp
                        @php if(!key_exists('message',$customerData)): @endphp
                var url = '@php echo url('/').'/estimateshipping'; @endphp';
                        @php else: @endphp
                var url = '@php echo url('/').'/estimateguestshipping'; @endphp';
                        @php endif; @endphp
                        @php else: @endphp
                var url = '@php echo url('/').'/estimateguestshipping'; @endphp';
                @php endif; @endphp
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (data) {
                        var jsonData = JSON.parse(JSON.stringify(data));
                        var shipping_method = '';
                        for (var i = 0; i < jsonData.length; i++) {
                            var counter = jsonData[i];
                            if(i == 0){
                                if(counter['amount'] == '0'){
                                    shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" checked="checked" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>Free</p></div>';
                                }else{
                                    shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" checked="checked" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>$'+ counter['amount'] +'</p></div>';
                                }
                            }else{
                                if(counter['amount'] == '0'){
                                    shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>Free</p></div>';
                                }else {
                                    shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>$' + counter['amount'] + '</p></div>';
                                }
                            }
                        }
                        $('#shipping_append').html(shipping_method);
                        var method_code = $('#shipping_append input[name=shipping-method]:checked').val();
                        var career_code = $('#shipping_append input[name=shipping-method]:checked').val();
                        var data = {
                            "quote_id": quote_id,
                            "email": email,
                            "firstname": firstname,
                            "lastname": lastname,
                            "address": addressline1,
                            "addressline2": addressline2,
                            "city": city,
                            "state": state,
                            "region_id": region_id,
                            "country": "US",
                            "postcode": postcode,
                            "receivername": '',
                            "receiveremail": '',
                            "message": '',
                            "shippingCarrierCode": career_code,
                            "shippingMethodCode": method_code,
                            "telephone": telephone
                        };

                        var url = '@php echo url('/').'/shippinginformation'; @endphp';
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            type: "POST",
                            url: url,
                            data: data,
                            success: function (data) {
                                $('#pills-shipping-method .continue-to-payment').removeClass('disabled').removeAttr('disabled');
                                var jsonData = JSON.parse(JSON.stringify(data));
                                var string = '';
                                for (var i = 0; i < jsonData['payment_methods'].length; i++) {
                                    var counter = jsonData['payment_methods'][i];
                                    if(i == 0){
                                        string += '<div class="billing-mob"><label class="custom-radio">' + counter['title'] + '<input checked="checked" name="payment-method" value="' + counter['code'] + '" type="radio" methodlabel="' + counter['title'] + '" required><span class="checkmark"></span></label></div>';
                                    } else{
                                        string += '<div class="billing-mob"><label class="custom-radio">' + counter['title'] + '<input name="payment-method" value="' + counter['code'] + '" type="radio" methodlabel="' + counter['title'] + '" required><span class="checkmark"></span></label></div>';
                                    }
                                }
                                $('#payment_method_mob').html(string);
                                var billing_method = '<div class="div-divider"></div><h6>Billing Address</h6><div class="billing-mob"><label class="custom-radio">Same as shipping address<input type="radio" checked="checked" name="radio"><span class="checkmark"></span></label></div><div class="billing-mob border-0"><label class="custom-radio">Use a different billing address<input type="radio" name="radio"><span class="checkmark"></span></label></div>';
                                $('#billing_method_mob').html(billing_method);
                                $('.col-md-12.cart-total-mob .row div:nth-child(2) h6').text('$'+(jsonData['totals'].base_subtotal).toFixed(2));
                                if(jsonData['totals'].shipping_amount == '0'){
                                    $('.col-md-12.cart-total-mob .row div:nth-child(4) h6').text('Free');
                                }else{
                                    $('.col-md-12.cart-total-mob .row div:nth-child(4) h6').text('$'+(jsonData['totals'].shipping_amount).toFixed(2));
                                }
                                $('.col-md-12.cart-total-mob .row div:nth-child(4)').css('display','block');
                                $('.col-md-12.cart-total-mob .row div:nth-child(3)').css('display','block');
                                $('.col-md-12.cart-total-mob .row div:last-child h2').text('$'+(jsonData['totals'].grand_total).toFixed(2));
                                $('.cart-total-mob button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled');
                                var address_string = '<h6>Customer Info</h6><span class="change"><a href="javascript:void(0);" data-toggle="modal" data-target="#add-address-modal">Change</a></span><p>'+ firstname + ' ' + lastname +'</p><p>'+ addressline1 + ' ' + addressline2 +'</p><p>'+ city +', '+ state + ', ' + postcode +'</p><p>'+ country +'</p><p>'+ telephone +'</p>';
                                $('.checkout-mobile .row div#mobile-step-2 div:nth-child(2) div.col-12.checkout-cards:nth-child(2)').html(address_string).addClass('customer-info-mob');
                                $('#add-address-modal').modal('hide');
                                $('.modal#add-address-modal .modal-body #firstname').val(firstname);
                                $('.modal#add-address-modal .modal-body #lastname').val(lastname);
                                $('.modal#add-address-modal .modal-body #addressline1').val(addressline1);
                                $('.modal#add-address-modal .modal-body #addressline2').val(addressline2);
                                $('.modal#add-address-modal .modal-body #city').val(city);
                                $('.modal#add-address-modal .modal-body .select option[value="' + state + '"]').prop('selected', true);
                                $('.modal#add-address-modal .modal-body .select-styled').html($('#add-address-modal .modal-body .select option[data-title="' + state + '"]').text());
                                $('.modal#add-address-modal .modal-body #postcode').val(postcode);
                                $('.modal#add-address-modal .modal-body #telephone').val(telephone);
                                $('.modal#add-address-modal button.btn.btn-ansel.btn-block.mt-4').removeClass('disabled').removeAttr('disabled');
                                $('div.main-loader').css('display','none');
                            },
                            dataType:'json'
                        });
                    },
                    dataType:'json'
                });
            }

        }
        return valid;
    });

    $(document).on('click','#mobile-step-2 .edit-email',function(){
        $('#mobile-step-2').css('display','none');
        $('#email-forms-mob').css('display','block');
    });

    $(document).on('change','#billing_method_mob div input[name=radio]',function(){
        if($('#billing_method_mob div.billing-mob.border-0 input[name=radio]').is(':checked')){
            $('#add-billing-address-modal').modal('show');
            $('button.btn-placeorder-mob').addClass('disabled').attr('disabled','disabled');
            //$('#billing_method_mob #add-billing-address-modal').show();
        } else{
            //$('#billing_method_mob #add-billing-address-modal').hide();
            $('button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled');
        }
    });
    $(document).on('change','#mobile-step-2 div.col-12.checkout-cards input[name=have_recipient]',function(){
        if($('#mobile-step-2 div.col-12.checkout-cards input[name=have_recipient]').is(':checked')){
            $('#mobile-step-2 #add-recipient-modal').css('display','block');
            $('button.btn-placeorder-mob').addClass('disabled').attr('disabled','disabled');
        } else{
            $('button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled','disabled');
            $('#mobile-step-2 #add-recipient-modal').css('display','none');
        }
    });
    $(document).on('click','button.add_recipient_info',function(){
        if($('#mobile-step-2 div.col-12.checkout-cards input[name=have_recipient]').is(':checked')){
            $('#add-recipient-modal').modal('show');
        }
    });
    $(document).on('click', '#add-recipient-modal button.btn-ansel.btn',function(){
        if($('#mobile-step-2 div.col-12.checkout-cards input[name=have_recipient]').is(':checked')){
            $('#add-recipient-modal .error').remove();
            var regEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
            var regName = /^[a-z ,.'-]+$/i;
            var valid = false;
            if($('#add-recipient-modal #recipient-name').val() == ''){
                $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#add-recipient-modal #recipient-name');
                valid = false;
            } else if($('#add-recipient-modal #recipient-email').val() == ''){
                $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#add-recipient-modal #recipient-email');
                valid = false;
            } else if($('#add-recipient-modal #recipient-message').val() == ''){
                $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#add-recipient-modal #recipient-message');
                valid = false;
            } else if($('#add-recipient-modal #recipient-name').val() != '' && !regName.test($('#add-recipient-modal #recipient-name').val())){
                $('<p class="error" style="color:red;">Please enter valid name.</p>').insertAfter('#add-recipient-modal #recipient-name');
                valid = false;
            } else if($('#add-recipient-modal #recipient-email').val() != '' && !regEmail.test($('#add-recipient-modal #recipient-email').val())){
                $('<p class="error" style="color:red;">Please enter valid name.</p>').insertAfter('#add-recipient-modal #recipient-email');
                valid = false;
            } else{
                $('#add-recipient-modal button.btn-ansel.btn').addClass('disabled').attr('disabled','disabled');
                //$('div.main-loader').css('display','block');
                var email = $('form.mobile_guestEmailform #guestEmail').val();
                var firstname = $('#firstname').val();
                var lastname = $('#lastname').val();
                var addressline1 = $('#addressline1').val();
                var addressline2 = $('#addressline2').val();
                var city = $('#city').val();
                var country = $('#country').val();
                var postcode = $('#postcode').val();
                var region_id = $('#add-address-modal .select.state.select-hidden option:selected').val();
                var state = $('#add-address-modal .select.state.select-hidden option:selected').text();
                var samebilling = 1;
                var quote_id = '@php echo $quote_id @endphp';
                var telephone = $('#add-address-modal #telephone').val();
                var receivername = $('#add-recipient-modal #recipient-name').val();
                var receiveremail = $('#add-recipient-modal #recipient-email').val();
                var message = $('#add-recipient-modal #recipient-message').val();

                var data = {"quote_id": quote_id,
                    "email": email,
                    "firstname": firstname,
                    "lastname": lastname,
                    "address": addressline1,
                    "addressline2": addressline2,
                    "city": city,
                    "state": state,
                    "region_id": region_id,
                    "country": "US",
                    "postcode": postcode,
                    "receivername": receivername,
                    "receiveremail": receiveremail,
                    "message": message,
                    "same_as_billing": samebilling,
                    "telephone": telephone};
                valid = true;
                        @php if(isset($_COOKIE["customer_token"])): @endphp
                        @php if(!key_exists('message',$customerData)): @endphp
                var url = '@php echo url('/').'/estimateshipping'; @endphp';
                        @php else: @endphp
                var url = '@php echo url('/').'/estimateguestshipping'; @endphp';
                        @php endif; @endphp
                        @php else: @endphp
                var url = '@php echo url('/').'/estimateguestshipping'; @endphp';
                @php endif; @endphp
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (data) {
                        var jsonData = JSON.parse(JSON.stringify(data));
                        var shipping_method = '';
                        for (var i = 0; i < jsonData.length; i++) {
                            var counter = jsonData[i];
                            if(i == 0){
                                shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" checked="checked" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>$' + counter['amount'] + '</p></div>';
                            } else{
                                shipping_method += '<div class="shipping-mob"><label class="custom-radio">' + counter['carrier_title'] + ' - ' + counter['method_title'] + '<input type="radio" name="shipping-method" value="' + counter['method_code'] + '" methodlabel="' + counter['carrier_title'] + ' - ' + counter['method_title'] + '"><span class="checkmark"></span></label><p>$' + counter['amount'] + '</p></div>';
                            }
                        }
                        $('#shipping_append').html(shipping_method);
                        $('#add-recipient-modal').modal('hide');
                        $('button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled','disabled');
                        $('#add-recipient-modal button.btn-ansel.btn').removeClass('disabled').removeAttr('disabled');
                        $('div.main-loader').css('display','none');
                    },
                    dataType:'json'
                });
            }
            return valid;
        }
    });
    $('#login-with-existing-account').on('click',function(){
        $('form.create-acc').css('display','none');
        $('form.existing_customer').css('display','block');
    });
    $('#add-billing-address-modal button.btn.btn-ansel').on('click',function(){
        var valid = false;
        var regName = /^[a-z ,.'-]+$/i;
        $('.error').remove();
        if (!$('#add-billing-address-modal #bill-firstname').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#add-billing-address-modal #bill-firstname');
        } else if (!$('#add-billing-address-modal #bill-lastname').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#add-billing-address-modal #bill-lastname');
        } else if (!$('#add-billing-address-modal #bill-addressline1').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#add-billing-address-modal #bill-addressline1');
        } else if (!$('#add-billing-address-modal #bill-city').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#add-billing-address-modal #bill-city');
        } else if (!$('#add-billing-address-modal #bill-country').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#add-billing-address-modal #bill-country');
        } else if (!$('#add-billing-address-modal #bill-postcode').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is a required field.</p>').insertAfter('#add-billing-address-modal #bill-postcode');
        } else if ($('#add-billing-address-modal #bill-firstname').val() && !regName.test($('#add-billing-address-modal #bill-firstname').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter('#add-billing-address-modal #bill-firstname');
        } else if ($('#add-billing-address-modal #bill-lastname').val() && !regName.test($('#add-billing-address-modal #bill-lastname').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid name</p>').insertAfter('#add-billing-address-modal #bill-lastname');
        } else if ($('#add-billing-address-modal #bill-city').val() && !regName.test($('#add-billing-address-modal #bill-city').val())) {
            valid = false;
            $('<p class="error" style="color:red;">Please enter valid city</p>').insertAfter('#add-billing-address-modal #bill-city');
        } else if (!$('#add-billing-address-modal #bill-telephone').val()) {
            valid = false;
            $('<p class="error" style="color:red;">This is required field.</p>').insertAfter('#add-billing-address-modal #bill-telephone');
        } else{
            valid = true;
            $('#add-billing-address-modal').modal('hide');
            $('button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled');
        }
        return valid;
    });
    $(document).on('click','button.btn-placeorder-mob',function(){
                @php if(isset($_COOKIE["customer_token"])): @endphp
                @php if(!key_exists('message',$customerData)): @endphp
        var email = '@php echo $customerData['email']; @endphp';
                @php else: @endphp
        var email = $('form.mobile_guestEmailform #guestEmail').val();
                @php endif; @endphp
                @php else: @endphp
        var email = $('form.mobile_guestEmailform #guestEmail').val();
                @php endif; @endphp

        var quote_id = '@php echo $quote_id @endphp';
        var paymentmethod = $('#payment_method_mob input[name=payment-method]:checked').val();
        var paymentmethodlabel = $('#payment_method_mob input[name=payment-method]:checked').attr('methodlabel');
        var shipingMethodlabel = $('#shipping_append input[name=shipping-method]:checked').attr('methodlabel');
        var subtotal = $('.col-md-12.cart-total-mob .row div:nth-child(2) h6').text();
        var shippingcost = $('.col-md-12.cart-total-mob .row div:nth-child(4) h6').text();
        var total = $('.col-md-12.cart-total-mob .row div:last-child h2').text();
        if($('#billing_method_mob div.billing-mob.border-0 input[name=radio]').is(':checked')){
            shippingfirstname = $('.modal#add-address-modal .modal-body #firstname').val();
            shippinglastname = $('.modal#add-address-modal .modal-body #lastname').val();
            shippingaddressline1 = $('.modal#add-address-modal .modal-body #addressline1').val();
            shippingaddressline2 = $('.modal#add-address-modal .modal-body #addressline2').val();
            shippingcity = $('.modal#add-address-modal .modal-body #city').val();
            shippingcountry = $('.modal#add-address-modal .modal-body #country').val();
            shippingpostcode = $('.modal#add-address-modal .modal-body #postcode').val();
            shippingregion_id = $('.modal#add-address-modal .modal-body .select.state.select-hidden option:selected').val();
            shippingstate = $('.modal#add-address-modal .modal-body .select.state.select-hidden option:selected').text();
            shippingtelephone = $('.modal#add-address-modal .modal-body #telephone').val();

            firstname = $('#add-billing-address-modal #bill-firstname').val();
            lastname = $('#add-billing-address-modal #bill-lastname').val();
            addressline1 = $('#add-billing-address-modal #bill-addressline1').val();
            addressline2 = $('#add-billing-address-modal #bill-addressline2').val();
            city = $('#add-billing-address-modal #bill-city').val();
            country = $('#add-billing-address-modal #bill-country').val();
            postcode = $('#add-billing-address-modal #bill-postcode').val();
            telephone = $('#add-billing-address-modal #bill-telephone').val();
            region_id = $('#add-billing-address-modal .select.state.select-hidden option:selected').val();
            state = $('#add-billing-address-modal .select.state.select-hidden option:selected').text();
            var samebilling = 0;
        } else{
            var firstname = $('#add-address-modal #firstname').val();
            var lastname = $('#add-address-modal #lastname').val();
            var addressline1 = $('#add-address-modal #addressline1').val();
            var addressline2 = $('#add-address-modal #addressline2').val();
            var city = $('#add-address-modal #city').val();
            var postcode = $('#add-address-modal #postcode').val();
            var region_id = $('#add-address-modal .select.state.select-hidden option:selected').val();
            var state = $('#add-address-modal .select.state.select-hidden option:selected').text();
            var telephone = $('#add-address-modal #telephone').val();
            var samebilling = 1;
            var country = $('#add-address-modal #country').val();
        }

        if (paymentmethod == 'pmclain_stripe') {
            var cc_number = $('form.cc-method-mobile input#cc-number').val();
            var cc_exp_month = $('form.cc-method-mobile input#cc-exp-month').val();
            var cc_exp_year = $('form.cc-method-mobile input#cc-exp-year').val();
            var cc_cvv = $('form.cc-method-mobile input#cc-cvv').val();
            var cc_noc = $('form.cc-method-mobile input#cc-nameOncard').val();
            var curr_month = new Date().getMonth()+1;
            var curr_year = new Date().getFullYear().toString().substr(-2);

            if(cc_number == ''){
                valid = false;
                $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method-mobile input#cc-number');
            } if(cc_exp_month == '' || cc_exp_month == undefined){
                valid = false;
                $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method-mobile input#cc-exp-month');
            } if(cc_exp_year == '' || cc_exp_year == undefined){
                valid = false;
                $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method-mobile input#cc-exp-year');
            } if(cc_cvv == ''){
                valid = false;
                $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method-mobile input#cc-cvv');
            } if(cc_noc == ''){
                valid = false;
                $('<p class="error" style="color:red;">This is a required field</p>').insertAfter('form.cc-method-mobile input#cc-nameOncard');
            } else {
                $('button.btn-placeorder-mob').addClass('disabled').attr('disabled','disabled');
                //$('div.main-loader').css('display','block');
                valid = true;
                $('form.cc-method-mobile .error').remove();
                @php if(array_key_exists("quote_id",$session)): @endphp
                        @php if($session['quote_id'] != '' ): @endphp
                        @php if(!is_object($session['quote_id'])): @endphp
                        @php $quote_id = $session['quote_id']; @endphp
                    data = {
                    'card_no': cc_number,
                    'ccExpiryMonth': cc_exp_month,
                    'ccExpiryYear': cc_exp_year,
                    'cvvNumber': cc_cvv,
                    'quote_id': '@php echo $quote_id; @endphp'
                };
                @php endif; @endphp
                @php endif; @endphp
                @php endif; @endphp
                //$('div.main-loader').css('display', 'block');
                var url = '@php echo url('/'); @endphp/addmoney/stripe';
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (data) {
                        if (data.result == 'success') {
                            localStorage.setItem('stripe_token', data.token);

                            @php if(array_key_exists("quote_id",$session)): @endphp
                            @php if($session['quote_id'] != '' ): @endphp
                            @php $quote_id = $session['quote_id']; @endphp
                            @php if(!is_object($quote_id)): @endphp
                            <?php if(isset($session['customer_token'])){
                                $url = createquote().''.$quote_id;
                            } else{
                                $url = createquoteguest().''.$quote_id;
                            }?>
                            @php $cartdata = m2ApiCall($url,'get',''); @endphp

                            @php if(array_key_exists('items',$cartdata)): @endphp
                            @foreach($cartdata['items'] as $data)
                            @if($data['sku'] == 'Gift_Card-$50' || $data['sku'] == 'Gift_Card-$55' || $data['sku'] == 'Gift_Card-$60' || $data['sku'] == 'Gift_Card-$70' || $data['sku'] == 'Gift_Card-$75' || $data['sku'] == 'Gift_Card-$80' || $data['sku'] == 'Gift_Card-$90' || $data['sku'] == 'Gift_Card-$100' || $data['sku'] == 'Gift_Card-$75' || $data['sku'] == 'Gift_Card-$150' || $data['sku'] == 'Gift_Card-$200')
                            @php $size = 8; @endphp
                            @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp

                            $.ajax({
                                url:'@php echo url('/').'/simpleProductData/'.$data['sku']; @endphp',
                                method: 'GET',
                                dataType:'json',
                                success: function(coupon_data){
                                    console.log(coupon_data);
                                    for (var i = 0; i < coupon_data.custom_attributes.length; i++) {
                                        if(coupon_data.custom_attributes[i].attribute_code == 'gift_card'){
                                            if(coupon_data.custom_attributes[i].value == '1'){
                                                var is_giftcard = '1';
                                            }else{
                                                var is_giftcard = '0';
                                            }
                                        } if(coupon_data.custom_attributes[i].attribute_code == 'gift_card_rule_id'){
                                            if(coupon_data.custom_attributes[i].value != ''){
                                                var rule_id = coupon_data.custom_attributes[i].value;
                                                var giftcard_price = coupon_data.price;
                                            }else{
                                                var rule_id = '';
                                                var giftcard_price = coupon_data.price;
                                            }
                                        }
                                    }
                                    //console.log(' is gift '+is_giftcard +' - rule_id -'+ rule_id + ' - giftcard_price '+giftcard_price);
                                    if(is_giftcard == '1' && rule_id != ''){
                                        coupon_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':rule_id,'type':'1'};
                                        $.ajax({
                                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                            url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                            method: 'POST',
                                            dataType:'json',
                                            data: coupon_data,
                                            success: function(coupon_data){
                                                var generated_coupon_code = coupon_data.code;
                                                localStorage.setItem("generated_coupon_code", generated_coupon_code);
                                                localStorage.setItem("giftcard_price", giftcard_price);
                                            }
                                        });
                                    }
                                }
                            });
                                    @endif
                                    @endforeach
                                    @php endif; @endphp
                                    @php endif; @endphp
                                    @php endif; @endphp
                                    @php endif; @endphp

                            var cc_type = detectCardType(cc_number);
                            var cc_month = cc_exp_month;
                            var cc_year = cc_exp_year;
                            var las4 = cc_number.substr(cc_number.length - 4);
                            var stripe_token = localStorage.getItem("stripe_token");
                            var data = {
                                "quote_id": quote_id,
                                "email": email,
                                "firstname": firstname,
                                "lastname": lastname,
                                "address": addressline1,
                                "addressline2": addressline2,
                                "city": city,
                                "state": state,
                                "region_id":region_id,
                                "country": "US",
                                "postcode": postcode,
                                "telephone": telephone,
                                "receivername": $('#add-recipient-modal #recipient-name').val(),
                                "receiveremail": $('#add-recipient-modal #recipient-email').val(),
                                "message": $('#add-recipient-modal #recipient-message').val(),
                                "paymentmethod": paymentmethod,
                                "shippingmethod": shipingMethodlabel,
                                "paymethodlabel": paymentmethodlabel,
                                "subtotal": subtotal,
                                "shippingrate": shippingcost,
                                "total": total,
                                "cc_type":cc_type,
                                "cc_exp_year": cc_year,
                                "cc_exp_month":cc_month,
                                "cc_last4":las4,
                                "cc_token":stripe_token
                            };
                                    @php if(isset($_COOKIE["customer_token"])): @endphp
                                    @php if(!key_exists('message',$customerData)): @endphp
                            var url = '@php echo url('/').'/customerplaceorder'; @endphp';
                                    @php else: @endphp
                            var url = '@php echo url('/').'/placeorder'; @endphp';
                                    @php endif; @endphp
                                    @php else: @endphp
                            var url = '@php echo url('/').'/placeorder'; @endphp';
                            @php endif; @endphp
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (data) {
                                    $('button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled');
                                    console.log(data);
                                    var order_id = data;
                                    if (data['message']) {
                                        $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                                    } else {
                                        localStorage.setItem('applied_coupon', '');
                                        @php if(isset($_COOKIE["customer_token"])): @endphp
                                                @php if(!key_exists('message',$customerData)): @endphp
                                        if($('#add-address-modal input#saveaddress').is(":checked") == true){
                                            if(addressline2 != undefined){
                                                data = {'firstname':firstname,'lastname':lastname,'streetline1':addressline1,'streetline2':addressline2,'telephone':telephone,'city':city,'state':state,'region_id':region_id,'postcode':postcode,'country':"US",'isshipping':true,'customerfirstname':'@php echo $customerData['firstname']; @endphp','customerlastname':'@php echo $customerData['lastname']; @endphp','customeremail':'@php echo $customerData['email']; @endphp'};
                                            } else{
                                                data = {'firstname':firstname,'lastname':lastname,'streetline1':addressline1,'streetline2':'','telephone':telephone,'city':city,'state':state,'postcode':postcode,'country':"US",'isshipping':true,'customerfirstname':'@php echo $customerData['firstname']; @endphp','customerlastname':'@php echo $customerData['lastname']; @endphp','customeremail':'@php echo $customerData['email']; @endphp'};
                                            }
                                            valid = true;
                                            $.ajax({
                                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                type:'POST',
                                                data:data,
                                                url :'@php echo url('/')."/saveaddress"; @endphp',
                                                success: function (saveaddressdata) {
                                                    console.log(saveaddressdata);
                                                },
                                                dataType: 'json'
                                            });
                                        }
                                        @php endif; @endphp
                                        @php endif; @endphp
                                        $('button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled');
                                        //window.location.href = '@php //echo url("/") @endphp';
                                        if($('#billing_method_mob div.billing-mob.border-0 input[name=radio]').is(':checked')){
                                            billing_firstname = $('#add-billing-address-modal #bill-firstname').val();
                                            billing_lastname = $('#add-billing-address-modal #bill-lastname').val();
                                            billing_addressline1 = $('#add-billing-address-modal #bill-addressline1').val();
                                            billing_addressline2 = $('#add-billing-address-modal #bill-addressline2').val();
                                            billing_city = $('#add-billing-address-modal #bill-city').val();
                                            billing_country = $('#add-billing-address-modal #bill-country').val();
                                            billing_postcode = $('#add-billing-address-modal #bill-postcode').val();
                                            billing_telephone = $('#add-billing-address-modal #bill-telephone').val();
                                            billing_region_id = $('#add-billing-address-modal .select.state.select-hidden option:selected').val();
                                            billing_state = $('#add-billing-address-modal .select.state.select-hidden option:selected').text();

                                            shippingfirstname = $('.modal#add-address-modal .modal-body #firstname').val();
                                            shippinglastname = $('.modal#add-address-modal .modal-body #lastname').val();
                                            shippingaddressline1 = $('.modal#add-address-modal .modal-body #addressline1').val();
                                            shippingaddressline2 = $('.modal#add-address-modal .modal-body #addressline2').val();
                                            shippingcity = $('.modal#add-address-modal .modal-body #city').val();
                                            shippingcountry = $('.modal#add-address-modal .modal-body #country').val();
                                            shippingpostcode = $('.modal#add-address-modal .modal-body #postcode').val();
                                            shippingregion_id = $('.modal#add-address-modal .modal-body .select.state.select-hidden option:selected').val();
                                            shippingstate = $('.modal#add-address-modal .modal-body .select.state.select-hidden option:selected').text();
                                            shippingtelephone = $('.modal#add-address-modal .modal-body #telephone').val();
                                        } else{
                                            var billing_firstname = $('#add-address-modal #firstname').val();
                                            var billing_lastname = $('#add-address-modal #lastname').val();
                                            var billing_addressline1 = $('#add-address-modal #addressline1').val();
                                            var billing_addressline2 = $('#add-address-modal #addressline2').val();
                                            var billing_city = $('#add-address-modal #city').val();
                                            var billing_postcode = $('#add-address-modal #postcode').val();
                                            var billing_region_id = $('#add-address-modal .select.state.select-hidden option:selected').val();
                                            var billing_state = $('#add-address-modal .select.state.select-hidden option:selected').text();
                                            var billing_telephone = $('#add-address-modal #telephone').val();
                                            var billing_country = $('#add-address-modal #country').val();

                                            shippingfirstname = $('#add-address-modal #firstname').val();
                                            shippinglastname = $('#add-address-modal #lastname').val();
                                            shippingaddressline1 = $('#add-address-modal #addressline1').val();
                                            shippingaddressline2 = $('#add-address-modal #addressline2').val();
                                            shippingcity = $('#add-address-modal #city').val();
                                            shippingpostcode = $('#add-address-modal #postcode').val();
                                            shippingregion_id = $('#add-address-modal .select.state.select-hidden option:selected').val();
                                            shippingstate = $('#add-address-modal .select.state.select-hidden option:selected').text();
                                            shippingtelephone = $('#add-address-modal #telephone').val();
                                            shippingcountry = $('#add-address-modal #country').val();
                                        }
                                        console.log(order_id);
                                        var string = '<div class="col-md-12 checkout checkout-web order-success"><div class="row"><div class="col-md-7 l-s"><div class="left-sec" id="order-success-step" style="display: block;"><div class="order-top-sec text-center"><img width="68px" src="{{ url('/') }}/public/images/success.png"><h1>We\'re on it!</h1><h2 id="customer-firstname">Thank you '+ billing_firstname +' for your purchase!</h2><h5 id="customer-email">A confirmation email has been sent to '+ email +'</h5><h6 id="customer-orderId">Order ID: '+ order_id +'</h6></div><div class="order-detail"><p>Please allow 2-4 days for your order to ship. Each plant is handpicked by our team and we like to ensure that we\'re getting you the fullest and freshest plant possible. We\'ll be sure to send you an email when your plant ships. </p><div class="col-md-12 return return-mobile"><div class="row"><div class="col col-12"><button type="button" class="btn btn-ansel btn-block continue-shopping m-auto">CONTINUE SHOPPING </button></div><div class="col col-12"><h6><a href="{{ url('/') }}/help">Need Help?</a><a href="{{ url('/') }}/contact" target="_blank"><span class="ul"> Contact Us</span></a></h6></div></div></div></div><div class="col-md-12 ci"><div class="row"><h5>Customer Information</h5></div></div><div class="col-md-12 order-summary"><div class="row"><div class="col-md-6"><ul class="list-unstyled" id="order-shipping-address"><li>Shipping Address</li><li>'+ shippingfirstname +' '+ shippinglastname +'</li><li>'+ shippingaddressline1 +'</li><li>'+ shippingcity +'  '+ shippingstate +'</li><li>'+ shippingpostcode +' United States </li><li>'+ shippingtelephone +'</li></ul></div><div class="col-md-6"><ul class="list-unstyled" id="order-billing-address"><li>Billing Address</li><li>'+ billing_firstname +' '+ billing_lastname +'</li><li>'+ billing_addressline1 +'</li><li>'+ billing_city +'  '+ billing_state +'</li><li>'+ billing_postcode +' United Stetes</li><li>'+ billing_telephone +'</li></ul></div><div class="col-md-6"><ul class="list-unstyled" id="order-shipping-method"><li>Shipping Method</li><li>'+ shipingMethodlabel +'</li></ul></div><div class="col-md-6"><ul class="list-unstyled" id="order-payment-method"><li>Payment Method</li>'+ paymentmethodlabel +'</ul></div></div></div><div class="col-md-12 return"><div class="row"><div class="col"><h6><a href="{{ url('/') }}/help">Need Help?</a><a href="{{ url('/') }}/contact" target="_blank"><span class="ul"> Contact Us</span></a></h6></div><div class="col"><button type="button" class="btn btn-ansel float-right continue-shopping">CONTINUE SHOPPING</button></div></div></div></div></div><div class="col-md-5 r-s"></div></div></div>';
                                        $('#mobile-step-2').html(string);
                                        jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                                        $('div.main-loader').css('display','none');

                                        var get_referral_sender_email = localStorage.getItem("referral_sender_email");
                                        if(get_referral_sender_email != '' && get_referral_sender_email != undefined){
                                            @php $size = 8; @endphp
                                                    @php $couponcode = strtoupper(substr(md5(time().rand(10000,99999)), 0, $size)); @endphp
                                                referral_data = {'couponcode':'@php echo $couponcode @endphp','rule_id':'16','type':'1'};
                                            $.ajax({
                                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                url:'@php echo url('/').'/generatecouponcode'; @endphp',
                                                method: 'POST',
                                                dataType:'json',
                                                data: referral_data,
                                                success: function(coupon_data){
                                                    var referral_back_generated_code = coupon_data.code;
                                                    referralBack_data = {'ref_back_code':referral_back_generated_code,'email':get_referral_sender_email, 'reffered_email': $('.shippingmethodForm #shippingEmail').val()};
                                                    $.ajax({
                                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                        url:'@php echo url('/').'/referralback'; @endphp',
                                                        method: 'POST',
                                                        dataType:'json',
                                                        data: referralBack_data,
                                                        success: function(coupon_data){

                                                        }
                                                    });
                                                }
                                            });
                                        }
                                        var generated_coupon_code = localStorage.getItem("generated_coupon_code");
                                        var giftcard_price = localStorage.getItem("giftcard_price");
                                        if(generated_coupon_code != '' && generated_coupon_code != undefined){
                                            senderemail = email;
                                            sendername =  billing_firstname + ' ' + billing_lastname;
                                            var shippingReceiverName = $('#add-recipient-modal #recipient-name').val();
                                            var shippingReceiverEmail = $('#add-recipient-modal #recipient-email').val();
                                            var shippingReceiverMessage = $('#add-recipient-modal #recipient-message').val();
                                            var gft_price = '$'+giftcard_price;
                                            couponcode_data = {'couponcode':generated_coupon_code,'sendername':sendername,'senderemail':senderemail,'receiveremail':shippingReceiverEmail,'receivername':shippingReceiverName,'receivermessage':shippingReceiverMessage,'paymethod':paymentmethodlabel,'giftcard_price':gft_price};
                                            $.ajax({
                                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                url : '@php echo url('/')."/emailcouponcode"; @endphp',
                                                method : 'POST',
                                                data: couponcode_data,
                                                success: function(couponcode_data){
                                                    console.log(couponcode_data);
                                                }
                                            });
                                        }
                                    }
                                },
                                dataType:'json'
                            });

                        } else {
                            $('div.main-loader').css('display','none');
                            if(data.result == 'carderror'){
                                $('form.cc-method-mobile .credit_card_form .col-md-12:nth-child(2)').append('<p class="error" style="color:red;">Please check the details you have entered.</p>');
                            } else if(data.result == 'missing-parameter'){
                                $('form.cc-method-mobile .credit_card_form .col-md-12:nth-child(2)').append('<p class="error" style="color:red;">Required parameters missing. Please check again.</p>');
                            } else{
                                $('form.cc-method-mobile .credit_card_form .col-md-12:nth-child(2)').append('<p class="error" style="color:red;">Exception error occured. Please try again later.</p>');
                            }
                        }
                    },
                    dataType: 'json'
                });
            }
        } else{
            var data = {
                "quote_id": quote_id,
                "email": email,
                "firstname": firstname,
                "lastname": lastname,
                "address": addressline1,
                "addressline2": addressline2,
                "city": city,
                "state": state,
                "region_id":region_id,
                "country": "US",
                "postcode": postcode,
                "receivername": $('#add-recipient-modal #recipient-name').val(),
                "receiveremail": $('#add-recipient-modal #recipient-email').val(),
                "message": $('#add-recipient-modal #recipient-message').val(),
                "paymentmethod": paymentmethod,
                "shippingmethod": shipingMethodlabel,
                "paymethodlabel": paymentmethodlabel,
                "subtotal": subtotal,
                "shippingrate": shippingcost,
                "total": total,
                "telephone": telephone
            };
                    @php if(isset($_COOKIE["customer_token"])): @endphp
                    @php if(!key_exists('message',$customerData)): @endphp
            var url = '@php echo url('/').'/customerplaceorder'; @endphp';
                    @php else: @endphp
            var url = '@php echo url('/').'/placeorder'; @endphp';
                    @php endif; @endphp
                    @php else: @endphp
            var url = '@php echo url('/').'/placeorder'; @endphp';
            @php endif; @endphp
            $('button.btn-placeorder-mob').addClass('disabled').attr('disabled','disabled');
            //$('div.main-loader').css('display','block');
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: url,
                data: data,
                success: function (data) {
                    $('button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled');
                    console.log(data);
                    if (data['message']) {
                        $('<p class="error" style="color:red;">' + data['message'] + '</p>').insertAfter('#pills-payment-method .payment_method form.cc-method');
                    } else {
                        localStorage.setItem('applied_coupon', '');
                        @php if(isset($_COOKIE["customer_token"])): @endphp
                                @php if(!key_exists('message',$customerData)): @endphp
                        if($('#add-address-modal input#saveaddress').is(":checked") == true){
                            if(addressline2 != undefined){
                                data = {'firstname':firstname,'lastname':lastname,'streetline1':addressline1,'streetline2':addressline2,'telephone':telephone,'city':city,'state':state,'region_id':region_id,'postcode':postcode,'country':"US",'isshipping':true,'customerfirstname':'@php echo $customerData['firstname']; @endphp','customerlastname':'@php echo $customerData['lastname']; @endphp','customeremail':'@php echo $customerData['email']; @endphp'};
                            } else{
                                data = {'firstname':firstname,'lastname':lastname,'streetline1':addressline1,'streetline2':'','telephone':telephone,'city':city,'state':state,'postcode':postcode,'country':"US",'isshipping':true,'customerfirstname':'@php echo $customerData['firstname']; @endphp','customerlastname':'@php echo $customerData['lastname']; @endphp','customeremail':'@php echo $customerData['email']; @endphp'};
                            }
                            valid = true;
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                type:'POST',
                                data:data,
                                url :'@php echo url('/')."/saveaddress"; @endphp',
                                success: function (saveaddressdata) {
                                    console.log(saveaddressdata);
                                },
                                dataType: 'json'
                            });
                        }
                        @php endif; @endphp
                        @php endif; @endphp
                        $('button.btn-placeorder-mob').removeClass('disabled').removeAttr('disabled');
                        //window.location.href = '@php //echo url("/") @endphp';
                        if($('#billing_method_mob div.billing-mob.border-0 input[name=radio]').is(':checked')){
                            billing_firstname = $('#add-billing-address-modal #bill-firstname').val();
                            billing_lastname = $('#add-billing-address-modal #bill-lastname').val();
                            billing_addressline1 = $('#add-billing-address-modal #bill-addressline1').val();
                            billing_addressline2 = $('#add-billing-address-modal #bill-addressline2').val();
                            billing_city = $('#add-billing-address-modal #bill-city').val();
                            billing_country = $('#add-billing-address-modal #bill-country').val();
                            billing_postcode = $('#add-billing-address-modal #bill-postcode').val();
                            billing_telephone = $('#add-billing-address-modal #bill-telephone').val();
                            billing_region_id = $('#add-billing-address-modal .select.state.select-hidden option:selected').val();
                            billing_state = $('#add-billing-address-modal .select.state.select-hidden option:selected').text();

                            shippingfirstname = $('.modal#add-address-modal .modal-body #firstname').val();
                            shippinglastname = $('.modal#add-address-modal .modal-body #lastname').val();
                            shippingaddressline1 = $('.modal#add-address-modal .modal-body #addressline1').val();
                            shippingaddressline2 = $('.modal#add-address-modal .modal-body #addressline2').val();
                            shippingcity = $('.modal#add-address-modal .modal-body #city').val();
                            shippingcountry = $('.modal#add-address-modal .modal-body #country').val();
                            shippingpostcode = $('.modal#add-address-modal .modal-body #postcode').val();
                            shippingregion_id = $('.modal#add-address-modal .modal-body .select.state.select-hidden option:selected').val();
                            shippingstate = $('.modal#add-address-modal .modal-body .select.state.select-hidden option:selected').text();
                            shippingtelephone = $('.modal#add-address-modal .modal-body #telephone').val();
                        } else{
                            var billing_firstname = $('#add-address-modal #firstname').val();
                            var billing_lastname = $('#add-address-modal #lastname').val();
                            var billing_addressline1 = $('#add-address-modal #addressline1').val();
                            var billing_addressline2 = $('#add-address-modal #addressline2').val();
                            var billing_city = $('#add-address-modal #city').val();
                            var billing_postcode = $('#add-address-modal #postcode').val();
                            var billing_region_id = $('#add-address-modal .select.state.select-hidden option:selected').val();
                            var billing_state = $('#add-address-modal .select.state.select-hidden option:selected').text();
                            var billing_telephone = $('#add-address-modal #telephone').val();
                            var billing_country = $('#add-address-modal #country').val();

                            shippingfirstname = $('#add-address-modal #firstname').val();
                            shippinglastname = $('#add-address-modal #lastname').val();
                            shippingaddressline1 = $('#add-address-modal #addressline1').val();
                            shippingaddressline2 = $('#add-address-modal #addressline2').val();
                            shippingcity = $('#add-address-modal #city').val();
                            shippingpostcode = $('#add-address-modal #postcode').val();
                            shippingregion_id = $('#add-address-modal .select.state.select-hidden option:selected').val();
                            shippingstate = $('#add-address-modal .select.state.select-hidden option:selected').text();
                            shippingtelephone = $('#add-address-modal #telephone').val();
                            shippingcountry = $('#add-address-modal #country').val();
                        }
                        var string = '<div class="col-md-12 checkout checkout-web order-success"><div class="row"><div class="col-md-7 l-s"><div class="left-sec" id="order-success-step" style="display: block;"><div class="order-top-sec text-center"><img width="68px" src="{{ url('/') }}/public/images/success.png"><h1>We\'re on it!</h1><h2 id="customer-firstname">Thank you '+ billing_firstname +' for your purchase!</h2><h5 id="customer-email">A confirmation email has been sent to '+ email +'</h5><h6 id="customer-orderId">Order ID: '+ data +'</h6></div><div class="order-detail"><p>Please allow 2-4 days for your order to ship. Each plant is handpicked by our team and we like to ensure that we\'re getting you the fullest and freshest plant possible. We\'ll be sure to send you an email when your plant ships. </p><div class="col-md-12 return return-mobile"><div class="row"><div class="col col-12"><button type="button" class="btn btn-ansel btn-block continue-shopping m-auto">CONTINUE SHOPPING </button></div><div class="col col-12"><h6><a href="{{ url('/') }}/help">Need Help?</a><a href="{{ url('/') }}/contact" target="_blank"><span class="ul"> Contact Us</span></a></h6></div></div></div></div><div class="col-md-12 ci"><div class="row"><h5>Customer Information</h5></div></div><div class="col-md-12 order-summary"><div class="row"><div class="col-md-6"><ul class="list-unstyled" id="order-shipping-address"><li>Shipping Address</li><li>'+ shippingfirstname +' '+ shippinglastname +'</li><li>'+ shippingaddressline1 +'</li><li>'+ shippingcity +'  '+ shippingstate +'</li><li>'+ shippingpostcode +' United States </li><li>'+ shippingtelephone +'</li></ul></div><div class="col-md-6"><ul class="list-unstyled" id="order-billing-address"><li>Billing Address</li><li>'+ billing_firstname +' '+ billing_lastname +'</li><li>'+ billing_addressline1 +'</li><li>'+ billing_city +'  '+ billing_state +'</li><li>'+ billing_postcode +' United Stetes</li><li>'+ billing_telephone +'</li></ul></div><div class="col-md-6"><ul class="list-unstyled" id="order-shipping-method"><li>Shipping Method</li><li>'+ shipingMethodlabel +'</li></ul></div><div class="col-md-6"><ul class="list-unstyled" id="order-payment-method"><li>Payment Method</li>'+ paymentmethodlabel +'</ul></div></div></div><div class="col-md-12 return"><div class="row"><div class="col"><h6><a href="{{ url('/') }}/help">Need Help?</a><a href="{{ url('/') }}/contact" target="_blank"><span class="ul"> Contact Us</span></a></h6></div><div class="col"><button type="button" class="btn btn-ansel float-right continue-shopping">CONTINUE SHOPPING</button></div></div></div></div></div><div class="col-md-5 r-s"></div></div></div>';
                        $('#mobile-step-2').html(string);
                        jQuery("html, body").animate({ scrollTop: 0 }, "fast");
                        $('div.main-loader').css('display','none');
                    }
                },
                dataType:'json'
            });
        }
    });
    $(document).on('change', '#shipping_append .custom-radio input' ,function () {
        var shipping_cost = $(this).parent().parent().find("p").html();
        $('.cart-total-mob .row div.col-6.text-right:nth-child(4) h6').text(shipping_cost);
        if(shipping_cost == 'Free'){
            shipping_cost = 'Free';
            shipping_amount = '$0';
        } else{
            shipping_amount = $(this).parent().parent().find("p").html();
            shipping_cost = shipping_amount;
        }
        var subtotal = $('.cart-total-mob .row div.col-6:nth-child(2) h6').html();
        var subtotal = subtotal.replace('$', '');
        var shippingCost = shipping_amount.replace('$', '');
        if($('.cart-total-mob div.row div.discount-amount-mob h6').html() != '' && $('.cart-total-mob div.row div.discount-amount-mob h6').html() != '$0') {
            var discount = $('.cart-total-mob div.row div.discount-amount-mob h6').html();
            var discount = discount.replace('$', '');
            if (discount === '') {
                discount = 0;
            }
            console.log(subtotal+shippingCost+discount)
            var grandtotal = eval(subtotal) + eval(shippingCost) - eval(discount);
        } else {
            console.log(subtotal+shippingCost)
            var grandtotal = eval(subtotal) + eval(shippingCost);
        }
        $('.cart-total-mob .row div.col-6.text-right:last-child h2').html('$' + (grandtotal).toFixed(2));
    });
    $(document).on('click','#payment_method_mob input[name=payment-method]',function(){
        var payment_method = $('#payment_method_mob input[name=payment-method]:checked').val();
        if (payment_method == 'pmclain_stripe') {
            $('.payment_method_mobile form.cc-method-mobile').css({'display': 'block'});
        } else {
            $('.payment_method_mobile form.cc-method-mobile').css({'display': 'none'});
        }
    });

    function detectCardType(number) {
        var re = {
            electron: /^(4026|417500|4405|4508|4844|4913|4917)\d+$/,
            maestro: /^(5018|5020|5038|5612|5893|6304|6759|6761|6762|6763|0604|6390)\d+$/,
            dankort: /^(5019)\d+$/,
            interpayment: /^(636)\d+$/,
            unionpay: /^(62|88)\d+$/,
            visa: /^4[0-9]{12}(?:[0-9]{3})?$/,
            mastercard: /^5[1-5][0-9]{14}$/,
            amex: /^3[47][0-9]{13}$/,
            diners: /^3(?:0[0-5]|[68][0-9])[0-9]{11}$/,
            discover: /^6(?:011|5[0-9]{2})[0-9]{12}$/,
            jcb: /^(?:2131|1800|35\d{3})\d{11}$/
        }

        for(var key in re) {
            if(re[key].test(number)) {
                if(key == 'electron'){
                    var card_type = 'Electron';
                } else if( key == 'maestro'){
                    var card_type = 'Maestro';
                } else if( key == 'visa'){
                    var card_type = 'Visa';
                } else if( key == 'mastercard'){
                    var card_type = 'MasterCard';
                } else if( key == 'amex'){
                    var card_type = 'American Express';
                } else if( key == 'discover'){
                    var card_type = 'Discover';
                } else if( key == 'diners'){
                    var card_type = 'Diners Club';
                } else if( key == 'jcb'){
                    var card_type = 'JCB';
                } else if(key == 'dankort'){
                    var card_type = 'Dankort';
                } else if(key == 'interpayment'){
                    var card_type = 'InterPayment';
                } else{
                    var card_type = 'Other';
                }
                return card_type;
            }
        }
    }
    $(document).on('click','form.existing_customer .create_account a',function(){
        $('form.existing_customer').css('display','none');
        $('form.create-acc').css('display','block');
    });
    $(document).on('click','#cart_modal .cart-items .up-counter',function(){
        var curr_val = $(this).parent().find('input.cartitemqty').val();
        if($.isNumeric(curr_val)){
            curr_val = parseInt(curr_val) + parseInt(1);
            $(this).parent().find('input.cartitemqty').val(curr_val);
        } else{
            $(this).parent().find('input.cartitemqty').val('1');
        }
        $(this).parent().find('input.cartitemqty').trigger('change');
    });
    $(document).on('click','#cart_modal .cart-items .down-counter',function(){
        var curr_val = $(this).parent().find('input.cartitemqty').val();
        if($.isNumeric(curr_val)){
            curr_val = parseInt(curr_val) - parseInt(1);
            if(curr_val > 0){
                $(this).parent().find('input.cartitemqty').val(curr_val);
            } else{
                $(this).parent().find('input.cartitemqty').val('1');
            }
        } else{
            $(this).parent().find('input.cartitemqty').val('1');
        }
        $(this).parent().find('input.cartitemqty').trigger('change');
    });
    $(document).on('click','.checkout.checkout-web .cart-items .up-counter',function(){
        var curr_val = $(this).parent().find('input.cartitemqty').val();
        if($.isNumeric(curr_val)){
            curr_val = parseInt(curr_val) + parseInt(1);
            $(this).parent().find('input.cartitemqty').val(curr_val);
        } else{
            $(this).parent().find('input.cartitemqty').val('1');
        }
        $(this).parent().find('input.cartitemqty').trigger('change');
    });
    $(document).on('click','.checkout.checkout-web .cart-items .down-counter',function(){
        var curr_val = $(this).parent().find('input.cartitemqty').val();
        if($.isNumeric(curr_val)){
            curr_val = parseInt(curr_val) - parseInt(1);
            if(curr_val > 0){
                $(this).parent().find('input.cartitemqty').val(curr_val);
            } else{
                $(this).parent().find('input.cartitemqty').val('1');
            }
        } else{
            $(this).parent().find('input.cartitemqty').val('1');
        }
        $(this).parent().find('input.cartitemqty').trigger('change');
    });
    $(document).on('click','#mobile-step-1 .cart-total-mob button.btn.btn-ansel.btn-block',function(){
        if($('.remove_coupan_code').length > 0){
            $(".remove_coupan_code").val('');
            $(".cc_text_step2").val('');
            $(".remove_coupan_code").siblings('p.error').remove();
            $(".remove_coupan_code").removeAttr('id');
            $(".form-control").removeClass('remove_coupan_code');
        }
        console.log("checkout triggered");
        @php if(array_key_exists("quote_id",$session)): @endphp
        @php if($session['quote_id'] != '' ): @endphp
        @php $quote_id = $session['quote_id']; @endphp
        @php if(isset($session['customer_token'])): @endphp
        $('#mobile-step-2').css('display','block');
        $('#mobile-step-1').css('display','none'); 
        @php else : @endphp
        $('#email-forms-mob').css('display','block');
        $('#mobile-step-1').css('display','none');
        @php endif; @endphp
        @php else : @endphp
        $('#email-forms-mob').css('display','block');
        $('#mobile-step-1').css('display','none');
        @php endif; @endphp
        @php else : @endphp
        $('#email-forms-mob').css('display','block');
        $('#mobile-step-1').css('display','none');
        @php endif; @endphp
    });
    $(document).on('change','#mobile-step-1 #apply_coupon #coupon_code',function(){
        var coupon_code = $('#mobile-step-1 #apply_coupon #coupon_code').val();
        $('#mobile-step-2 #apply_coupon #coupon_code').val(coupon_code);
    });
    $(document).on('click','#mobile-step-1 #apply_coupon .cancel_coupon', function(){
        $('#mobile-step-2 #apply_coupon #coupon_code').val();
    });
    $(document).on('change blur','form.cc-method input#cc-exp-month , form.cc-method input#cc-exp-year , form.cc-method-mobile #cc-exp-month , form.cc-method-mobile input#cc-exp-year',function(){
        var valid = false;
        cc_exp_month = $('form.cc-method input#cc-exp-month').val();
        cc_exp_year = $('form.cc-method input#cc-exp-year').val();
        cc_exp_month_mob = $('form.cc-method-mobile input#cc-exp-month').val();
        cc_exp_year_mob = $('form.cc-method-mobile input#cc-exp-year').val();
        var curr_month = new Date().getMonth()+1;
        var curr_year = new Date().getFullYear().toString().substr(-2);
        if(cc_exp_month != '' && cc_exp_year != ''){
            if((parseInt(cc_exp_month) > 12) && parseInt(cc_exp_year) < parseInt(curr_year)){
                valid = false;
                if($('form.cc-method input#cc-exp-month').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method input#cc-exp-month');
                }
                if($('form.cc-method input#cc-exp-year').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method input#cc-exp-year');
                }
                $('button.btn.btn-ansel.placeorder').attr('disabled','disable').addClass('disabled');
            } else if((parseInt(cc_exp_month) > 12) && parseInt(cc_exp_year) > parseInt(curr_year)){
                valid = false;
                if($('form.cc-method input#cc-exp-month').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method input#cc-exp-month');
                }
                $('form.cc-method input#cc-exp-year').parent().find('.error').remove();
                $('button.btn.btn-ansel.placeorder').attr('disabled','disable').addClass('disabled');
            } else if((parseInt(cc_exp_month) < curr_month) && parseInt(cc_exp_year) == parseInt(curr_year)){
                valid = false;
                if($('form.cc-method input#cc-exp-month').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method input#cc-exp-month');
                }
                $('form.cc-method input#cc-exp-year').parent().find('.error').remove();
                $('button.btn.btn-ansel.placeorder').attr('disabled','disable').addClass('disabled');
            } else if((parseInt(cc_exp_month) < curr_month) && parseInt(cc_exp_year) < parseInt(curr_year)){
                valid = false;
                if($('form.cc-method input#cc-exp-month').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method input#cc-exp-month');
                }
                if($('form.cc-method input#cc-exp-year').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method input#cc-exp-year');
                }
                $('button.btn.btn-ansel.placeorder').attr('disabled','disable').addClass('disabled');
            } else if((parseInt(cc_exp_month) >= curr_month) && parseInt(cc_exp_year) < parseInt(curr_year)){
                valid = false;
                if($('form.cc-method input#cc-exp-year').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method input#cc-exp-year');
                }
                $('form.cc-method input#cc-exp-month').parent().find('.error').remove();
                $('button.btn.btn-ansel.placeorder').attr('disabled','disable').addClass('disabled');
            } else{
                $('form.cc-method input#cc-exp-month').parent().find('.error').remove();
                $('form.cc-method input#cc-exp-year').parent().find('.error').remove();
                $('button.btn.btn-ansel.placeorder').removeAttr('disabled').removeClass('disabled');
                valid = true;
            }
        }
        return valid;
    });
    $(document).on('blur','form.cc-method-mobile #cc-exp-month , form.cc-method-mobile input#cc-exp-year',function(){
        var valid = false;
        cc_exp_month_mob = $('form.cc-method-mobile input#cc-exp-month').val();
        cc_exp_year_mob = $('form.cc-method-mobile input#cc-exp-year').val();
        var curr_month = new Date().getMonth()+1;
        var curr_year = new Date().getFullYear().toString().substr(-2);
        if(cc_exp_month != '' && cc_exp_year != ''){
            if((parseInt(cc_exp_month_mob) > 12) && parseInt(cc_exp_year_mob) < parseInt(curr_year)){
                valid = false;
                if($('form.cc-method-mobile input#cc-exp-month').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method-mobile-mobile input#cc-exp-month');
                }
                if($('form.cc-method-mobile input#cc-exp-year').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method-mobile input#cc-exp-year');
                }
                $('button.btn.btn-ansel.btn-placeorder-mob').attr('disabled','disable').addClass('disabled');
            } else if((parseInt(cc_exp_month_mob) > 12) && parseInt(cc_exp_year_mob) > parseInt(curr_year)){
                valid = false;
                if($('form.cc-method-mobile input#cc-exp-month').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method-mobile input#cc-exp-month');
                }
                $('form.cc-method-mobile input#cc-exp-year').parent().find('.error').remove();
                $('button.btn.btn-ansel.btn-placeorder-mob').attr('disabled','disable').addClass('disabled');
            } else if((parseInt(cc_exp_month_mob) < curr_month) && parseInt(cc_exp_year_mob) == parseInt(curr_year)){
                valid = false;
                if($('form.cc-method-mobile input#cc-exp-month').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method-mobile input#cc-exp-month');
                }
                $('form.cc-method-mobile input#cc-exp-year').parent().find('.error').remove();
                $('button.btn.btn-ansel.btn-placeorder-mob').attr('disabled','disable').addClass('disabled');
            } else if((parseInt(cc_exp_month_mob) < curr_month) && parseInt(cc_exp_year_mob) < parseInt(curr_year)){
                valid = false;
                if($('form.cc-method-mobile input#cc-exp-month').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method-mobile input#cc-exp-month');
                }
                if($('form.cc-method-mobile input#cc-exp-year').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method-mobile input#cc-exp-year');
                }
                $('button.btn.btn-ansel.btn-placeorder-mob').attr('disabled','disable').addClass('disabled');
            } else if((parseInt(cc_exp_month_mob) >= curr_month) && parseInt(cc_exp_year_mob) < parseInt(curr_year)){
                valid = false;
                if($('form.cc-method-mobile input#cc-exp-year').parent().find('.error').length == 0){
                    $('<p class="error" style="color:red;">Invalid card details.</p>').insertAfter('form.cc-method-mobile input#cc-exp-year');
                }
                $('form.cc-method-mobile input#cc-exp-month').parent().find('.error').remove();
                $('button.btn.btn-ansel.btn-placeorder-mob').attr('disabled','disable').addClass('disabled');
            } else{
                $('form.cc-method-mobile input#cc-exp-month').parent().find('.error').remove();
                $('form.cc-method-mobile input#cc-exp-year').parent().find('.error').remove();
                $('button.btn.btn-ansel.btn-placeorder-mob').removeAttr('disabled').removeClass('disabled');
                valid = true;
            }
        }
        return valid;
    });

    $(document).on('click','#mobile-step-2 #apply_coupon .apply' ,function () {
        var valid = true;
        $('#apply_coupon .error').remove();
        $('#apply_coupon .form-group p').remove();
        if (!$('#coupon_code').val()) {
            valid = false;
            $('<p style="color:red;" class="error">Please enter coupon code</p>').insertAfter('#coupon_code');
        } else {
            //$('div.main-loader').css('display','block');
            $('#apply_coupon .apply').addClass('disabled').attr('disabled','disabled');
            $('.right-sec').append('<div class="dev-loader"></div>');
            var coupon_code = $('#coupon_code').val();
            valid = true;
            var url = '@php echo url('/').'/coupons/'.$session['quote_id'].'/'; @endphp' + coupon_code;
            $.ajax({
                type: "GET",
                url: url,
                success: function (data) {
                    $.ajax({
                        type: "GET",
                        url : '@php echo url("/"); @endphp/checkcoupon/'+coupon_code,
                        success : function (data){
                            if(data.result == 'coupon_found'){
                                var referral_sender_email = data.coupondata[0].sender_email;
                                localStorage.setItem('referral_sender_email', referral_sender_email);
                            } else{
                                localStorage.setItem('referral_sender_email', '');
                            }
                        },
                        dataType: 'json'
                    });
                    $('#apply_coupon .apply').removeClass('disabled').removeAttr('disabled');
                    if (data.coupon == true) {
                        $('.coupon_applied').remove();
                        $('<p class="coupon_applied" style="color:green">Code applied.</p>').insertAfter('#coupon_code');
                        $('#apply_coupon button.cancel_coupon').css({'display': 'block', 'margin-top': '0px'});
                        $('#apply_coupon button.apply').css({'display': 'none'});

                        var discount = Math.abs(data.total.discount_amount);
                        @php if(isMobile()): @endphp
                        $('.cart-total-mob .row .discount-amount-mob h6').html('$' + (discount).toFixed(2));
                        $('.cart-total-mob .row .discount-amount-mob').css('display','block');
                        $('.cart-total-mob .row .discount-text-mob').css('display','block');
                        var grandtotal = data.total.grand_total;
                        var shipping = $('.cart-total-mob .row div.col-6:nth-child(4) h6').html();
                        if (shipping != '-' && shipping != '') {
                            if(shipping == 'Free'){
                                shipping = '$0';
                            }
                            shipping = shipping.replace('$', '');
                            grandtotal = eval(shipping) + eval(data.total.subtotal) + eval(data.total.discount_amount);
                        } else {
                            grandtotal = eval(data.total.grand_total) + eval(data.total.discount_amount) ;
                        }
                        $('.cart-total-mob .row div.col-6:last-child h2').html('$' + (grandtotal).toFixed(2));
                        @php else: @endphp
                        $('.subtotal .discount td.text-right').html('$' + (discount).toFixed(2));
                        // }
                        $('.right-sec .dev-loader').remove();
                        var grandtotal = data.total.grand_total;
                        var shipping = $('.subtotal table tr.shipping-total td.text-right').text();
                        if (shipping != '-' && shipping != '') {
                            if(shipping == 'Free'){
                                shipping = '$0';
                            }
                            shipping = shipping.replace('$', '');
                            grandtotal = eval(shipping) + eval(data.total.subtotal) + eval(data.total.discount_amount);
                        } else {
                            grandtotal = eval(data.total.grand_total) + eval(data.total.discount_amount);
                        }
                        $('.subtotal .discount').css({'display': 'table-row'});
                        $('.total td.text-right h3 b').html('$' + (grandtotal).toFixed(2));
                        @php endif; @endphp
                    } else {
                        $("#coupon_code").siblings("p").remove();
                        $('<p class="error" style="color:red">Oops! Invalid code.</p>').insertAfter('#coupon_code');
                    }
                    $('div.main-loader').css('display','none');
                },
                dataType: 'json'
            });
        }
        return valid;
    });
    // Check email exist

    $(document).on('click', '#mobile-step-2 #apply_coupon .cancel_coupon' ,function () {
        //$('div.main-loader').css('display','block');
        $('#apply_coupon .cancel_coupon').addClass('disabled').attr('disabled','disabled');
        $('.right-sec').append('<div class="dev-loader"></div>');
        var coupon_code = $('#coupon_code').val();
        $('#apply_coupon .form-group p').remove();
        var url = '@php echo url('/').'/removecoupon/'.$session['quote_id'].'/'; @endphp' + coupon_code;
        $.ajax({
            type: "GET",
            url: url,
            success: function (data) {
                $('#apply_coupon .cancel_coupon').removeClass('disabled').removeAttr('disabled','disabled');
                if (data.coupon == true) {
                    $('<p style="color:green">Code removed.</p>').insertAfter('#coupon_code');
                    $('#apply_coupon button.cancel_coupon').css({'display': 'none'});
                    $('#apply_coupon button.apply').css({'display': 'block', 'margin-top': '0px'});
                    $('#apply_coupon #coupon_code').val('');

                    var discount = Math.abs(data.total.discount_amount);
                    @php if(isMobile()): @endphp
                    $('.cart-total-mob .row .discount-amount-mob h6').html('$' + (discount).toFixed(2));
                    $('.cart-total-mob .row .discount-amount-mob').css('display','none');
                    $('.cart-total-mob .row .discount-text-mob').css('display','none');
                    var grandtotal = data.total.grand_total;
                    var shipping = $('.cart-total-mob .row div.col-6:nth-child(4) h6').html();
                    if (shipping != '-' && shipping != '') {
                        if(shipping == 'Free'){
                            shipping = '$0';
                        }
                        shipping = shipping.replace('$', '');
                        grandtotal = eval(shipping) + eval(data.total.subtotal) + eval(data.total.discount_amount);
                    } else {
                        grandtotal = eval(data.total.grand_total) + eval(data.total.discount_amount);
                    }
                    $('.cart-total-mob .row div.col-6:last-child h2').html('$' + (grandtotal).toFixed(2));
                    @php else: @endphp
                    $('.subtotal .discount td.text-right').html('$' + (discount).toFixed(2));
                    // }
                    $('.right-sec .dev-loader').remove();
                    var grandtotal = data.total.grand_total;
                    var shipping = $('.subtotal table tr.shipping-total td.text-right').text();
                    if (shipping != '-' && shipping != '') {
                        if(shipping == 'Free'){
                            shipping = '$0';
                        }
                        shipping = shipping.replace('$', '');
                        grandtotal = eval(shipping) + eval(data.total.subtotal) + eval(data.total.discount_amount);
                    } else {
                        grandtotal = eval(data.total.grand_total) + eval(data.total.discount_amount);
                    }
                    $('.subtotal .discount').css({'display': 'none'});
                    $('.total td.text-right h3 b').html('$' + (grandtotal).toFixed(2));
                    // $('.subtotal tr.discount').hide();
                    $('.right-sec .dev-loader').remove();
                    @php endif; @endphp

                } else {
                    $('<p class="error" style="color:red">Coupon code is not valid!</p>').insertAfter('#coupon_code');
                }
                $('div.main-loader').css('display','none');
            },
            dataType: 'json'
        });
    });

    function excerpt(excerptElement, number , more = "..."){
        excerptElement.each(function(){
            var productTitle = $(this).text(),
                productTitleExcerpt,
                toArray = productTitle.split("", number),
                joinArray = toArray.join(''),
                joinArrayToArray = joinArray.split(" "),
                joinArrayToArrayPop = joinArrayToArray.pop(),
                joinArrayToArrayPopPush = joinArrayToArray.push(more),
                joinArrayToArrayPopPushJoin = joinArrayToArray.join(' '),
                productTitleExcerpt = joinArrayToArrayPopPushJoin;

            if(productTitle.length > number){
                productTitle = productTitleExcerpt;
                $(this).text(productTitle);
            }
        });
    }
    function productnameresize(){
        var productnameH3 = $('#cart_modal .col-md-9.col-9').find('a').find('h4');
        excerpt(productnameH3, 22);
    }
    $(document).ready(function(){
        productnameresize();
        var applied_coupon = localStorage.getItem("applied_coupon");
        if(applied_coupon != '' && applied_coupon != undefined){
            $('#coupon_code').val(applied_coupon);
            $('#apply_coupon button.cancel_coupon').css({'display': 'block', 'margin-top': '0px'});
            $('#apply_coupon button.apply').css({'display': 'none'});
        }
    });
    $('.view_cart_modal').on('click',function(){
        $('#cart_modal').modal('show');
    });
    $('.select-styled').attr('tabindex','0');
</script>
@include('layout.endfooter')
