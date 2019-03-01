@extends("layout")
@section("content")
    <section class="edit-account padding-top-100">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 fs-0">
                    @include('partials.my-profile-menu')
                    <div class="my-profile-page-content inline-block-top">
                        <div class="profile-page-title padding-bottom-50">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Edit account icon" src="/assets/uploads/edit-account-icon.svg"/>
                            </figure>
                            <h2 class="fs-24 lato-semibold inline-block">Edit account</h2>
                        </div>
                        <form method="POST" enctype="multipart/form-data" class="address-suggester-wrapper" id="patient-update-profile" action="{{route('update-account')}}">
                            <div class="form-row padding-bottom-15 fs-0">
                                <label class="inline-block fs-16" for="full-name">Your Name</label>
                                <input class="inline-block fs-16 custom-input" minlength="6" maxlength="100" type="text" name="full-name" id="full-name" @if(!empty($user_data) && !empty($user_data->name)) value="{{$user_data->name}}" @endif/>
                            </div>
                            <div class="form-row padding-bottom-15 fs-0">
                                <label class="inline-block fs-16" for="email">Your Email</label>
                                <input class="inline-block fs-16 custom-input" maxlength="100" type="email" name="email" id="email" @if(!empty($user_data) && !empty($user_data->email)) value="{{$user_data->email}}" @endif/>
                            </div>
                            <div class="form-row padding-bottom-15 fs-0">
                                <label class="inline-block fs-16" for="country">Your Country</label>
                                <select class="inline-block fs-16 custom-input country-select" id="country" name="country">
                                    @foreach($countries as $country)
                                        <option value="{{$country->code}}" data-code="{{$country->phone_code}}" @if(!empty($user_data) && !empty($user_data->country_id) && $user_data->country_id == $country->id) selected @endif>{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row padding-bottom-15 fs-0 suggester-parent module">
                                <label class="inline-block fs-16" for="country">Postal Address</label>
                                <input type="text" name="address" class="custom-input fs-16 address-suggester" autocomplete="off" placeholder="City, Street">
                                <div class="suggester-map-div margin-top-10 margin-bottom-10"></div>
                                <div class="alert alert-notice geoip-confirmation margin-top-10 margin-bottom-10 hide-this">Please check the map to make sure we got your correct address. If you're not happy - please drag the map to adjust it.</div>
                                <div class="alert alert-warning geoip-hint margin-top-10 margin-bottom-10">Please enter a valid address for your practice (including street name and number)</div>
                            </div>
                            @if((new \App\Http\Controllers\UserController())->checkDentistSession())
                                {{var_dump($user_data)}}
                                <div class="form-row padding-bottom-15 fs-0">
                                    <label class="inline-block fs-16" for="email">Your Phone</label>
                                    <input class="inline-block fs-16 custom-input" maxlength="20" type="text" name="phone" id="phone" @if(!empty($user_data) && !empty($user_data->phone)) value="{{$user_data->phone}}" @endif/>
                                </div>
                                <div class="form-row padding-bottom-15 fs-0">
                                    <label class="inline-block fs-16" for="email">Your Website</label>
                                    <input class="inline-block fs-16 custom-input" maxlength="255" type="text" name="website" id="website" @if(!empty($user_data) && !empty($user_data->website)) value="{{$user_data->website}}" @endif/>
                                </div>
                            @endif
                            <div class="form-row padding-bottom-40 fs-0">
                                <label class="inline-block-top fs-16">Photo</label>
                                <div class="inline-block-top avatar module text-center upload-file" @if(!empty($user_data) && !empty($user_data->avatar_url)) data-current-user-avatar="{{$user_data->avatar_url}}" @endif>
                                    <input type="file" class="visualise-image inputfile" id="custom-upload-avatar" name="image" accept=".jpg,.png,.jpeg,.svg,.bmp"/>
                                    <button type="button"></button>
                                </div>
                            </div>
                            <div class="form-row padding-bottom-15 fs-0">
                                <label class="inline-block fs-16" for="full-name">Your Wallet Address</label>
                                <input class="inline-block fs-16 custom-input" minlength="42" maxlength="42" name="dcn_address" type="text" @if(!empty($user_data) && !empty($user_data->dcn_address)) value="{{$user_data->dcn_address}}" @endif/>
                            </div>
                            <div class="btn-container text-center">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="submit" value="UPDATE PROFILE" class="white-blue-green-btn"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection