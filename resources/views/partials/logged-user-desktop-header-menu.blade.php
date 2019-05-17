<div class="col-xs-9 inline-block text-right logged-user-nav with-hub @if(!empty($class)) {{$class}} @endif">
    <a href="javascript:void(0)">
        <span>{{(new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id'])->name}}</span>
        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
            @php($avatar_url = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id'])->avatar_url)
            @if(!empty($avatar_url))
                <img alt="" itemprop="contentUrl" src="{{$avatar_url}}"/>
            @else
                <img alt="" itemprop="contentUrl" src="/assets/images/avatar-icon.svg"/>
            @endif
        </figure>
    </a>
    <span class="up-arrow">▲</span>
    <div class="hidden-box">
        <div class="hidden-box-hub container-fluid">
            <div class="row close-btn">
                <div class="col-xs-12"><a href="javascript:void(0)">Close <span>X</span></a></div>
            </div>
            <div class="row">
                @foreach((new \App\Http\Controllers\UserController())->getDentacoinHubApplications() as $application)
                    <a @if(!empty($application->link)) href="{{$application->link}}" target="_blank" @else href="javascript:alert('Coming soon!');" @endif class="col-md-3 col-xs-4 inline-block-top">
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
                    <a href="{{ route('my-profile') }}" class="white-blue-green-btn">My account</a>
                </div>
            </div>
        </div>
    </div>
</div>