@extends("layout")
@section("content")
    <section class="my-contracts padding-top-100">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 fs-0">
                    @include('partials.my-profile-menu')
                    <div class="my-profile-page-content inline-block-top">
                        <div class="profile-page-title">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Contracts list" src="/assets/uploads/contracts-list.svg"/>
                            </figure>
                            <h2 class="fs-24 lato-bold inline-block">My contracts</h2>
                            <div class="table-container fs-16">
                                @include('partials.table-my-contracts')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form target="_blank" method="POST" action="{{route('render-pdf')}}" id="render-pdf">
            <input type="hidden" name="pdf_data"/>
            <input type="hidden" name="_token" value="{{csrf_token()}}">
        </form>
    </section>
@endsection