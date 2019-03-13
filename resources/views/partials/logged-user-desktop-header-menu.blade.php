<div class="col-xs-9 inline-block text-right logged-user @if(!empty($class)) {{$class}} @endif">
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
    <div class="hidden-box">
        <div class="container-fluid text-center">
            <div class="row">
                <div class="col-xs-6 inline-block">
                    <a href="{{ route('user-logout') }}" class="logout"><i class="fa fa-power-off" aria-hidden="true"></i> Log out</a>
                </div>
                <div class="col-xs-6 inline-block">
                    <a href="{{ route('my-profile') }}" class="white-blue-green-btn">My profile</a>
                </div>
            </div>
        </div>
    </div>
</div>