@extends("admin.layout")
@section("content")
    <section class="content edit-page">
        <h1>Edit page</h1>
        <form method="post" enctype="multipart/form-data" class="text-center" action="{{route('edit-page', ['id' => $post->id])}}">
            <div class="form-row">
                <label>Meta title <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Name of the page, appears in the browser tab and in google search results. Maximum length 60 symbols."></i>:</label>
                <input type="text" name="title" value="{{$post->title}}" />
            </div>
            <div class="form-row">
                <label>Meta description <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Description of the page, appears in google search results. Maximum length 140 symbols."></i>:</label>
                <input type="text" name="description" value="{{$post->description}}" />
            </div>
            <div class="form-row">
                <label>Meta keywords <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="No maximum length, keywords are listed with comma separator between them."></i>:</label>
                <input type="text" name="keywords" value="{{$post->keywords}}" />
            </div>
            <div class="form-row">
                <label>Social title <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Social title that appears in facebook when sharing the page. Maximum length 40 symbols."></i>:</label>
                <input type="text" name="social_title" value="{{$post->social_title}}" />
            </div>
            <div class="form-row">
                <label>Social description <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Social description that appears in facebook when sharing the page. Maximum length 300 symbols."></i>:</label>
                <input type="text" name="social_description" value="{{$post->social_description}}" />
            </div>
            <div class="btn-container text-left media padding-bottom-20" data-id="1">
                <label>Social image <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Social image that appears in facebook when sharing the page. Recommended size is 1200x630px."></i>:</label>
                <figure class="image-visualization">
                    @if(!empty($post) && !empty($post->media))
                        <img class="small-image" src="{{URL::asset('assets/uploads/'.$post->media->name) }}"/>
                        <span class="inline-block-top remove-image"><i class="fa fa-times" aria-hidden="true"></i></span>
                    @endif
                </figure>
                <a href="javascript:openMedia(1, true, 'image')" class="btn">Select image</a>
                <input type="hidden" name="image" class="hidden-input-image" value="@if(!empty($post->media)) {{$post->media->id}} @endif">
            </div>
            @if(!empty($html_titles))
                <div class="title">Page section titles:</div>
                @foreach($html_titles as $editor)
                    <div class="form-row">
                        <label>Title:</label>
                        <textarea class="ckeditor-init" id="html-titles[{{$editor['id']}}]" name="html-titles[{{$editor['id']}}]">{{$editor['html']}}</textarea>
                    </div>
                @endforeach
            @endif
            @if(!empty($html_sections))
                <div class="title">Page sections:</div>
                @foreach($html_sections as $editor)
                    <div class="form-row">
                        <label>Section:</label>
                        <textarea class="ckeditor-init" id="html-sections[{{$editor['id']}}]" name="html-sections[{{$editor['id']}}]">{{$editor['html']}}</textarea>
                    </div>
                @endforeach
            @endif
            <div class="btn-container">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <button class="btn">Save</button>
            </div>
        </form>
    </section>
@endsection