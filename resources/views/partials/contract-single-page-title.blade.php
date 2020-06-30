<div class="col-xs-12 col-lg-10 col-lg-offset-1 contract-title module fs-0">
    <a @if ((new \App\Http\Controllers\UserController())->checkDentistSession()) href="{{route('home')}}" @elseif ((new \App\Http\Controllers\UserController())->checkPatientSession()) href="{{route('patient-access')}}" @endif class="fs-16 fs-xs-18 inline-block go-back-btn"><i class="fa fa-long-arrow-left padding-right-5" aria-hidden="true"></i> BACK</a>
    <a href="https://account.dentacoin.com/assurance?platform=assurance" target="_blank" class="fs-16 fs-xs-18 inline-block text-right view-all-btn">VIEW ALL <i class="fa fa-bars" aria-hidden="true"></i></a>
    <h1 class="lato-bold text-center fs-45 fs-xs-30 inline-block">Dentacoin Assurance Contract</h1>
</div>
