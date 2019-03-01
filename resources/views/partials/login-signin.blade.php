<div class="fs-0 popup-header-action">
    <a href="javascript:void(0)" class="inline-block" data-type="patient">I'm a Patient</a>
    <a href="javascript:void(0)" class="inline-block" data-type="dentist">I'm a Dentist</a>
</div>
<div class="fs-0 popup-body">
    <div class="patient inline-block">
        <div class="form-login">
            <h2>LOG IN</h2>
            <div class="padding-bottom-10">
                <a href="javascript:void(0)" class="facebook-custom-btn social-login-btn calibri-regular fs-20" data-url="//api.dentacoin.com/api/register" data-platform="assurance" data-type="patient">with Facebook</a>
            </div>
            <div>
                <a href="javascript:void(0)"  class="civic-custom-btn social-login-btn calibri-regular fs-20" data-url="//api.dentacoin.com/api/register" data-platform="assurance" data-type="patient">with Civic</a>
            </div>
            <div class="popup-half-footer">
                Don't have an account? <a href="javascript:void(0)" class="call-sign-up">Sign up</a>
            </div>
        </div>
        <div class="form-register">
            <h2>SIGN UP</h2>
            <div class="padding-bottom-10">
                <a href="javascript:void(0)" class="facebook-custom-btn social-login-btn calibri-regular fs-20" data-url="//api.dentacoin.com/api/register" data-platform="assurance" data-type="patient" custom-stopper="true">with Facebook</a>
            </div>
            <div>
                <a href="javascript:void(0)"  class="civic-custom-btn social-login-btn calibri-regular fs-20" data-url="//api.dentacoin.com/api/register" data-platform="assurance" data-type="patient" custom-stopper="true">with Civic</a>
            </div>
            <div class="privacy-policy-row padding-top-20">
                <div class="pretty p-svg p-curve on-blue-green-background">
                    <input type="checkbox" id="privacy-policy-registration-patient"/>
                    <div class="state p-success">
                        <!-- svg path -->
                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                        </svg>
                        <label class="fs-16">By continuing you agree to our <a href="//dentacoin.com/privacy-policy" target="_blank">Privacy Policy</a></label>
                    </div>
                </div>
            </div>
            <div class="step-errors-holder"></div>
            <div class="popup-half-footer">
                Already have an account? <a href="javascript:void(0)" class="call-log-in">Log in</a>
            </div>
        </div>
    </div>
    <div class="dentist inline-block custom-hide">
        <div class="form-login">
            <h2>LOG IN</h2>
            <form method="POST" action="{{ route('dentist-login') }}" id="dentist-login">
                <div class="padding-bottom-10">
                    <input class="custom-input" name="email" maxlength="100" type="email" placeholder="Email address"/>
                </div>
                <div class="padding-bottom-20">
                    <input class="custom-input" name="password" maxlength="50" type="password" placeholder="Password"/>
                </div>
                <div class="btn-container text-center">
                    <input type="submit" value="Log in" class="white-blue-green-btn"/>
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </div>
                <div class="text-center padding-top-20">Don't have an account? <a href="javascript:void(0)" class="call-sign-up">Sign up</a></div>
            </form>
            <div class="popup-half-footer">
                <a href="{{route('forgotten-password')}}">Forgotten password?</a>
            </div>
        </div>
        <div class="form-register">
            <h2>SIGN UP</h2>
            <form method="POST" enctype="multipart/form-data" id="dentist-register" action="{{ route('dentist-register') }}">
                <div class="step first visible" data-step="first">
                    <div class="padding-bottom-10">
                        <input class="custom-input" name="dentist-or-practice-name" type="text" maxlength="100" placeholder="Dentist or Practice Name"/>
                    </div>
                    <div class="padding-bottom-10">
                        <input class="custom-input" name="email" type="email" maxlength="100" placeholder="Email address"/>
                    </div>
                    <div class="padding-bottom-10">
                        <input class="custom-input password" name="password" minlength="6" maxlength="50" type="password" placeholder="Password"/>
                    </div>
                    <div class="padding-bottom-20">
                        <input class="custom-input repeat-password" name="repeat-password" minlength="6" maxlength="50" type="password" placeholder="Repeat password"/>
                    </div>
                </div>
                <div class="step second address-suggester-wrapper" data-step="second">
                    <div class="padding-bottom-20 fs-16 radio-buttons-holder">
                        <div class="pretty p-icon p-round">
                            <input type="radio" name="work-type" value="independent-dental-practitioner"/>
                            <div class="state p-primary">
                                <i class="fa fa-check icon" aria-hidden="true"></i>
                                <label>I work as an independent dental practitioner</label>
                            </div>
                        </div>
                        <div class="pretty p-icon p-round">
                            <input type="radio" name="work-type" value="represent-dental-practice"/>
                            <div class="state p-primary">
                                <i class="fa fa-check icon" aria-hidden="true"></i>
                                <label>I represent a dental practice/clinic with more than one dentist</label>
                            </div>
                        </div>
                        <div class="pretty p-icon p-round">
                            <input type="radio" name="work-type" value="an-associate-dentist"/>
                            <div class="state p-primary">
                                <i class="fa fa-check icon" aria-hidden="true"></i>
                                <label>I work as an associate dentist at a dental clinic</label>
                            </div>
                        </div>
                    </div>
                    <div class="padding-bottom-10">
                        <select name="country-code" id="dentist-country" class="custom-input country-select">
                            @php($current_phone_code = '+')
                            @if(!empty($countries))
                                @foreach($countries as $country)
                                    @php($selected = '')
                                    @if(!empty($current_user_country_code))
                                        @if($current_user_country_code == $country->code)
                                            @php($current_phone_code = $current_phone_code.$country->phone_code)
                                            @php($selected = 'selected')
                                        @endif
                                    @endif
                                    <option value="{{$country->code}}" data-code="{{$country->phone_code}}" {{$selected}}>{{$country->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="padding-bottom-10 suggester-parent module">
                        <input type="text" name="address" class="custom-input address-suggester" autocomplete="off" placeholder="City, Street">
                        <div class="suggester-map-div margin-top-10 margin-bottom-10"></div>
                        <div class="alert alert-notice geoip-confirmation margin-top-10 margin-bottom-10 hide-this">Please check the map to make sure we got your correct address. If you're not happy - please drag the map to adjust it.</div>
                        <div class="alert alert-warning geoip-hint margin-top-10 margin-bottom-10">Please enter a valid address for your practice (including street name and number)</div>
                    </div>
                    <div class="padding-bottom-10 phone">
                        <div class="country-code" name="phone-code">{{$current_phone_code}}</div>
                        <div class="input-phone">
                            <input class="custom-input" name="phone" maxlength="50" type="number" placeholder="Phone number"/>
                        </div>
                    </div>
                    <div class="padding-bottom-20">
                        <input class="custom-input" name="website" maxlength="250" type="url" placeholder="Website"/>
                    </div>
                </div>
                <div class="step third" data-step="third">
                    <div class="padding-bottom-20 fs-0">
                        <div class="inline-block-top avatar module upload-file">
                            <input type="file" class="visualise-image inputfile" id="custom-upload-avatar" name="image" accept=".jpg,.png,.jpeg,.svg,.bmp"/>
                            <button type="button"></button>
                        </div>
                        <div class="inline-block-top specializations">
                            <h4>Please select your specializations:</h4>
                            @foreach((new \App\Http\Controllers\APIRequestsController())->getAllEnums()->specialisations as $key => $specialisation)
                                <div class="pretty p-svg p-curve on-white-background">
                                    <input type="checkbox" name="specialization[]" value="{{$key}}"/>
                                    <div class="state p-success">
                                        <!-- svg path -->
                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                        </svg>
                                        <label class="fs-14">{{$specialisation}}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="search-for-clinic padding-top-15 padding-bottom-15"></div>
                        <div class="fs-0 captcha-parent padding-bottom-15">
                            <div class="inline-block fs-14 width-50 padding-right-10">
                                <input type="text" name="captcha" id="register-captcha" placeholder="Enter captcha" maxlength="5" class="custom-input"/>
                            </div>
                            <div class="inline-block width-50">
                                <div class="captcha-container flex text-center">
                                    <span>{!! captcha_img() !!}</span>
                                    <button type="button" class="refresh-captcha">
                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="privacy-policy-row">
                            <div class="pretty p-svg p-curve on-white-background">
                                <input type="checkbox" id="privacy-policy-registration"/>
                                <div class="state p-success">
                                    <!-- svg path -->
                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                    </svg>
                                    <label class="fs-14">I’ve read and agree to the <a href="//dentacoin.com/privacy-policy" target="_blank">Privacy Policy</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="step-errors-holder padding-top-10"></div>
                    </div>
                </div>
                <div class="btns-container">
                    <div class="inline-block">
                        <input type="button" value="< back" class="prev-step"/>
                    </div>
                    <div class="inline-block text-right">
                        <input type="button" value="Next" class="white-blue-green-btn next-step" data-current-step="first"/>
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                    </div>
                </div>
            </form>
            <div class="popup-half-footer">
                Already have an account? <a href="javascript:void(0)" class="call-log-in">Log in</a>
            </div>
        </div>
    </div>
</div>