@extends("admin.layout")
@section("content")
    <section class="content media-container">
        <h1>Adding media</h1>
        @include('admin.partials.media-tile', ['media' => $media])
    </section>
@endsection