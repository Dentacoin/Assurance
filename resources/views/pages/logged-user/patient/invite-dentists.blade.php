@extends("layout")
@section("content")
    <section class="invite-dentists-container padding-top-100 padding-top-sm-30 padding-top-xs-30">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 fs-0">
                    @include('partials.my-profile-menu')
                    <div class="my-profile-page-content inline-block-top">
                        <div class="dcn-background">
                            <div class="profile-page-title padding-bottom-50 padding-bottom-xs-30">
                                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                    <img alt="Add dentist" src="/assets/uploads/add-dentist.svg"/>
                                </figure>
                                <h2 class="fs-24 lato-bold inline-block">Invite Dentists</h2>
                            </div>
                            <div class="fs-18 padding-bottom-30">Help us change dentistry to the better by inviting dentists you believe could be interested. For each accepted invitation, you will receive 20,000 Dentacoin.</div>
                            <div class="text-center-xs text-center-sm">
                                @include('partials.invite-dentists-form', ['redirect' => 'invite-dentists'])
                            </div>
                        </div>
                        @if(!empty($invited_dentists_list))
                            <div class="invited-dentists-list">
                                <h3 class="line-crossed-title margin-top-50 margin-bottom-30 fs-20 lato-bold black-color"><span>My Invitations</span></h3>
                                <table>
                                    <thead>
                                    <tr>
                                        <th class="lato-bold">Date</th>
                                        <th class="lato-bold">Name</th>
                                        <th class="lato-bold">Email address</th>
                                        <th class="lato-bold">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invited_dentists_list as $dentist)
                                        <tr>
                                            <td>{{$dentist->created_at->format('Y-m-d')}}</td>
                                            <td>{{$dentist->title}} {{$dentist->name}}</td>
                                            <td><a href="mailto:{{$dentist->dentist_email}}">{{$dentist->dentist_email}}</a></td>
                                            <td>API METHOD</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection