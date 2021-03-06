@extends("layout")
@section("content")
    <section class="padding-top-110 padding-top-xs-30 padding-bottom-40 padding-bottom-xs-20">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="text-center fs-45 fs-xs-30 lato-bold padding-bottom-50">Dentacoin Video Demo</h1>
                </div>
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <video controls height="350" class="block-with-full-width demo-video" poster="/assets/uploads/dentacoin-assurance-intro.png"> <source src="https://dentacoin.com/assets/uploads/assurance-hub-video.mp4" type="video/mp4"> Your browser does not support HTML5 video. </video>
                </div>
            </div>
        </div>
    </section>
@endsection