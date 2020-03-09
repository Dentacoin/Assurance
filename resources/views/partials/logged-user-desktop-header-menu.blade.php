<div class="col-xs-9 inline-block text-right logged-user-nav with-hub @if(!empty($class)) {{$class}} @endif">
    <div class="hidden-box-parent">
        @php($user_data = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
        <div class="inline-block fs-14 padding-right-10 color-white-on-hub">
            <span>{{$user_data->name}}</span>
            @php($dcn_balance = (new \App\Http\Controllers\APIRequestsController())->getDCNBalance()->data)
            @php($dentacoin_data = (new \App\Http\Controllers\APIRequestsController())->getDentacoinDataByExternalProvider())
            @php($usd_balance = $dentacoin_data['USD'] * $dcn_balance)
            <div>{{$dcn_balance}} DCN | ${{number_format((float)$usd_balance, 2, '.', '')}}</div>
        </div>
        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block header-avatar">
            @if(!empty($user_data->thumbnail_url))
                <img alt="" itemprop="contentUrl" src="{{$user_data->thumbnail_url}}"/>
            @else
                <img alt="" itemprop="contentUrl" src="/assets/images/avatar-icon.svg"/>
            @endif
        </figure>
        <span class="up-arrow">▲</span>
        <div class="hidden-box">
            <div class="hidden-box-hub container-fluid">
                <div class="row close-btn">
                    <div class="col-xs-12"><a href="javascript:void(0)">Close <span>X</span></a></div>
                </div>
                <div class="row">
                    @foreach((new \App\Http\Controllers\Controller())->getDentacoinHubApplications() as $application)
                        <a @if(!empty($application->link)) href="{{$application->link}}" target="_blank" @else href="javascript:alert('Coming soon!');" @endif class="col-md-3 col-xs-4 inline-block-top application" data-platform="{{$application->title}}">
                            <figure class="text-center" itemtype="http://schema.org/ImageObject">
                                @if($application->media_name)
                                    <img src="{{$application->media_name}}" itemprop="contentUrl" @if(!empty($application->media_alt)) alt="{{$application->media_alt}}" @endif/>
                                @endif
                                <figcaption class="color-white fs-14 fs-xs-20 padding-bottom-15">{{$application->title}}</figcaption>
                            </figure>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="container-fluid text-center hidden-box-footer">
                <div class="row">
                    <div class="col-xs-6 inline-block">
                        <a href="{{ route('user-logout') }}" class="logout"><i class="fa fa-power-off" aria-hidden="true"></i> Log out</a>
                    </div>
                    <div class="col-xs-6 inline-block">
                        <a href="//account.dentacoin.com?platform=assurance" class="fs-16 white-blue-green-btn">My Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--@if(isset($mobile) && $mobile)
        <a href="javascript:void(0)" class="logged-user-hamburger inline-block padding-left-10"><i class="fa fa-bars fs-26 dark-color" aria-hidden="true"></i></a>
    @endif--}}
</div>