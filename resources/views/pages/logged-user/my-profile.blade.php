@extends("layout")
@section("content")
    <section class="my-profile padding-top-100 padding-bottom-50 padding-top-xs-30 padding-bottom-xs-0">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-lg-10 col-lg-offset-1 fs-0">
                    @include('partials.my-profile-menu')
                    <div class="my-profile-page-content inline-block-top">
                        <div class="profile-page-title padding-bottom-50">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Wallet icon" src="/assets/uploads/wallet-icon.svg"/>
                            </figure>
                            <h2 class="fs-24 lato-bold inline-block">My Wallet</h2>
                        </div>
                        <div class="remember-my-wallet-camp"></div>
                        @if((new \App\Http\Controllers\UserController())->checkPatientSession())
                            <h3 class="line-crossed-title margin-bottom-50 fs-20 lato-bold black-color"><span>Dentacoin balance</span></h3>
                            <div class="fs-38 lato-bold black-color"><span class="current-dcn-amount">{{$dcn_amount}}</span> DCN</div>
                            <div class="fs-28 lato-bold current-converted-price">
                                = <div class="amount inline-block-top">{{round($dcn_amount * $currency_arr['usd']['price_usd'], 6)}}</div>
                                <div class="symbol inline-block-top">
                                    <span>USD</span>
                                    <ul class="dropdown-hidden-menu">
                                        @foreach($currency_arr as $key => $value)
                                            <li><button data-multiple-with="{{$value['price_'.$key]}}">{{$key}}</button></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @php($current_logged_user = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
                            @if(empty($current_logged_user->dcn_address))
                                <div class="missing-dcn-address">
                                    <h3 class="line-crossed-title margin-bottom-20 fs-20 margin-top-50 lato-bold black-color"><span>Dentacoin address</span></h3>
                                    <div class="padding-top-15 padding-bottom-30">You can only use a unique Dentacoin (DCN) wallet address. <a href="https://wallet.dentacoin.com/" target="_blank">Here you can create one.</a></div>
                                    <form method="POST" id="add-dcn-address" action="{{route('add-dcn-address')}}">
                                        <div>
                                            <input type="text" class="custom-input address" name="address" maxlength="42" placeholder="Your Wallet Address"/>
                                        </div>
                                        <div class="padding-top-20">
                                            <input type="submit" value="SAVE" class="white-blue-green-btn"/>
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        </div>
                                    </form>
                                </div>
                            @else
                                @if(!$current_logged_user->civic_kyc)
                                    <h3 class="line-crossed-title margin-bottom-20 fs-20 margin-top-50 lato-bold black-color"><span>Withdraw Dentacoin</span></h3>
                                    <div class="fs-16">In order to withdraw your DCN you need to verify your account. We use Civic - a Blockchain-based identity platform that guarantees us that a person can have only one account on our platform. Please start by downloading the Civic app on your smartphone using the links below and add an email address or phone number to your Civic account. Then click the "Login with Civic" button below and use the app to scan the QR code.</div>
                                    <div class="fs-16 padding-top-30 padding-bottom-15">1. Download and install Civic</div>
                                    <div class="">
                                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block max-width-160 margin-right-15 padding-bottom-20">
                                            <a href="https://play.google.com/store/apps/details?id=com.civic.sip" target="_blank"><img alt="Google play button" src="/assets/uploads/google-play.png"/></a>
                                        </figure>
                                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block max-width-160 padding-bottom-20">
                                            <a href="https://itunes.apple.com/us/app/civic-secure-identity/id1141956958?mt=8" target="_blank">
                                                <img alt="App store button" src="/assets/uploads/app-store.png"/>
                                            </a>
                                        </figure>
                                    </div>
                                    <div class="padding-bottom-15 fs-16">2. Click the button below and scan the QR code. Please be patient, the validation procedure may take up to 3 minutes.</div>
                                    <div>
                                        <button class="civic-custom-btn kyc-approval social-login-btn">Login with Civic</button>
                                    </div>
                                @else
                                    <h3 class="line-crossed-title margin-bottom-40 fs-20 margin-top-50 lato-bold black-color"><span>Withdraw Dentacoin</span></h3>
                                    <div class="fs-16 padding-bottom-40">Please select the DCN amount you want to withdraw.</div>
                                    <form method="POST" id="withdraw" action="{{route('withdraw')}}">
                                        <div class="padding-bottom-10">
                                            <input type="text" class="custom-input address required" name="address" value="{{$current_logged_user->dcn_address}}" maxlength="42" placeholder="Your Wallet Address"/>
                                        </div>
                                        <div class="padding-bottom-25">
                                            <input type="number" placeholder="Enter Dentacoins amount" name="amount" class="custom-input required" max="{{$dcn_amount}}"/>
                                        </div>
                                        <div class="text-center padding-bottom-30">
                                            <input type="submit" value="WITHDRAW" class="white-blue-green-btn"/>
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        </div>
                                    </form>
                                    @if(!empty($transaction_history))
                                        <h3 class="line-crossed-title margin-bottom-40 fs-20 margin-top-50 lato-bold black-color"><span>Withdraw History</span></h3>
                                        <table class="withdraw-table">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Address</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($transaction_history as $transaction)
                                                    <tr>
                                                        <td>{{date('d/m/Y', strtotime($transaction->created_at))}}</td>
                                                        <td>{{$transaction->amount}}</td>
                                                        <td>{{$transaction->address}}</td>
                                                        <td>{{$transaction->status}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection