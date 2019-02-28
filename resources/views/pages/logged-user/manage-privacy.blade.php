@extends("layout")
@section("content")
    <section class="manage-privacy-container padding-top-100">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 fs-0">
                    @include('partials.my-profile-menu')
                    <div class="my-profile-page-content inline-block-top">
                        <div class="profile-page-title">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Privacy icon" src="/assets/uploads/privacy-icon.svg"/>
                            </figure>
                            <h2 class="fs-24 lato-bold inline-block">Manage privacy</h2>
                        </div>
                        <div class="delete-local-storage fs-0"></div>
                        <div class="delete padding-bottom-50 padding-top-60 fs-0">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block-top">
                                <img alt="Cancel icon" src="/assets/uploads/cancel.svg"/>
                            </figure>
                            <div class="text inline-block-top">
                                <h3 class="fs-20 padding-bottom-20 lato-bold dark-color">Delete My Profile</h3>
                                <div class="fs-16 dark-color">We can delete your Profile along with all your personal data from our servers. Just keep in mind that this will terminate your account irreversibly. If you are sure about that, just click the button below.</div>
                            </div>
                            <div class="btn-container text-right padding-top-30">
                                <form method="POST" action="{{route('delete-my-profile')}}">
                                    <input type="submit" value="DELETE MY PROFILE & PERSONAL DATA" class="white-blue-green-btn"/>
                                </form>
                            </div>
                        </div>
                        <div class="download padding-top-60 padding-bottom-50 fs-0">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block-top">
                                <img alt="Download icon" src="/assets/uploads/download.svg"/>
                            </figure>
                            <div class="text inline-block-top">
                                <h3 class="fs-20 padding-bottom-20 lato-bold dark-color">Download My Personal Data</h3>
                                <div class="fs-16 dark-color">You can request to receive all your personal data stored on our servers. Just click on the button below to download it.</div>
                            </div>
                            <div class="btn-container text-right padding-top-30">
                                <a href="" class="white-blue-green-btn">DOWNLOAD PERSONAL DATA</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection