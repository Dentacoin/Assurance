@extends("layout")
@section("content")
    <section class="forgotten-and-recover-password-section padding-top-150 padding-bottom-100 padding-top-xs-50 padding-bottom-xs-50">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <form method="POST" id="recover-password" class="padding-top-80 padding-bottom-80 padding-top-xs-40 padding-bottom-xs-40" action="{{route('password-recover-submit')}}">
                        <h1 class="lato-bold fs-40 fs-xs-30 dark-color">Password recover</h1>
                        <div class="fs-20 fs-xs-18 line-height-xs-22 dark-color padding-bottom-25 padding-top-15">Please enter your new password.</div>
                        <div class="padding-bottom-45 padding-bottom-xs-20"><input type="password" name="password" maxlength="100" class="custom-input max-width-400" placeholder="Password" required/></div>
                        <div>
                            <input type="submit" value="RESET PASSWORD" class="white-blue-green-btn"/>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="token" value="{{urldecode(\Illuminate\Support\Facades\Input::get('token'))}}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection