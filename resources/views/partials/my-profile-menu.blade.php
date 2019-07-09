<div class="my-profile-menu inline-block-top">
    <div class="wrapper">
        <div class="avatar-and-name padding-bottom-15 fs-0">
            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                @if(!empty($user_data->thumbnail_url))
                    <img alt="" itemprop="contentUrl" src="{{$user_data->thumbnail_url}}"/>
                @else
                    <img alt="" itemprop="contentUrl" src="/assets/images/avatar-icon.svg"/>
                @endif
            </figure>
            <div class="welcome-name inline-block fs-16 lato-bold">Welcome, {{(new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id'])->name}}</div>
        </div>
        <nav class="profile-menu module">
            <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
                <li>
                    <a href="{{ route('home') }}" itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Home icon" src="/assets/uploads/home.svg"/>
                        </figure>
                        <span itemprop="name">Home</span>
                    </a>
                </li>
                <li>
                    <a href="//account.dentacoin.com/my-profile" itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Wallet icon" src="/assets/uploads/wallet-icon.svg"/>
                        </figure>
                        <span itemprop="name">My Wallet</span>
                    </a>
                </li>
                <li>
                    <a href="//account.dentacoin.com/edit-account" itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Edit account icon" src="/assets/uploads/edit-account-icon.svg"/>
                        </figure>
                        <span itemprop="name">Edit Account</span>
                    </a>
                </li>
                <li>
                    <a href="//account.dentacoin.com/manage-privacy" itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Privacy icon" src="/assets/uploads/privacy-icon.svg"/>
                        </figure>
                        <span itemprop="name">Manage Privacy</span>
                    </a>
                </li>
                <li>
                    <a href="//account.dentacoin.com/my-contracts" itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Contracts list" src="/assets/uploads/contracts-list.svg"/>
                        </figure>
                        <span itemprop="name">My contracts</span>
                    </a>
                </li>
                @if(session('logged_user')['type'] == 'patient')
                    <li>
                        <a href="{{ route('invite-dentists') }}" @if(!empty(Route::current()) && Route::current()->getName() == 'invite-dentists') class="active" @endif itemprop="url">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Add dentist" src="/assets/uploads/add-dentist.svg"/>
                            </figure>
                            <span itemprop="name">Invite Dentists</span>
                        </a>
                    </li>
                @elseif(session('logged_user')['type'] == 'dentist')
                    <li>
                        <a href="{{ route('create-contract') }}" @if(!empty(Route::current()) && Route::current()->getName() == 'create-contract') class="active" @endif itemprop="url">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Create new contract" src="/assets/uploads/create-new-contract.svg"/>
                            </figure>
                            <span itemprop="name">Create contract</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="//dentavox.dentacoin.com" target="_blank" itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Dentavox logo" src="//dentacoin.com/assets/uploads/dentavox--surveys.svg"/>
                        </figure>
                        <span itemprop="name">DentaVox Surveys</span>
                    </a>
                </li>
                <li>
                    <a href="//reviews.dentacoin.com" target="_blank" itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Trusted reviews logo" src="//dentacoin.com/assets/uploads/trusted-reviews-icon.svg"/>
                        </figure>
                        <span itemprop="name">Trusted Reviews</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user-logout') }}" itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Logout icon" src="/assets/uploads/logout-icon.svg"/>
                        </figure>
                        <span itemprop="name">Log out</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>