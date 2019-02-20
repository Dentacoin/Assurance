@extends("layout")
@section("content")
    <section class="padding-top-100 padding-bottom-50">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="lato-bold fs-45 text-center black-color padding-bottom-50">Create Assurance Contract</h1>
                </div>
            </div>
        </div>
        @include('partials.contract-creation-steps')
        <div class="container steps-body">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                    <form method="POST" enctype="multipart/form-data" action="{{route('store-and-submit-temporally-contract')}}" id="dentist-create-contract" class="wrapper padding-top-40 padding-bottom-60" data-param-gd-cd-id="{{$calculator_proposals['param_gd_cd_id']}}" data-param-gd-cd="{{$calculator_proposals['param_gd_cd']}}" data-param-gd-id="{{$calculator_proposals['param_gd_id']}}" data-param-cd-id="{{$calculator_proposals['param_cd_id']}}" data-param-gd="{{$calculator_proposals['param_gd']}}" data-param-cd="{{$calculator_proposals['param_cd']}}" data-param-id="{{$calculator_proposals['param_id']}}">
                        @include('pages.logged-user.dentist.contract-creation-step-one')
                        @include('pages.logged-user.dentist.contract-creation-step-two')
                        @include('pages.logged-user.dentist.contract-creation-step-three')
                        @include('pages.logged-user.dentist.contract-creation-step-four')
                        <div class="text-center form-btn-container padding-top-40">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="dentist_signature" value="">
                            <a href="javascript:void(0);" data-current-step="one" class="white-blue-green-btn min-width-250 next">NEXT</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection