@extends("admin.layout")
@section("content")
    <section class="content">
        <h1>@if(!empty($post)) Edit @else Add @endif Support Guide</h1>
        <form method="post" enctype="multipart/form-data" class="text-center" action="@if(!empty($post)) {{route('edit-support-guide', ['id' => $post->id])}} @else {{route('add-support-guide')}} @endif">
            <div class="form-row">
                <label>Text:<span class="required-mark">*</span></label>
                <textarea id="text" class="ckeditor-init" name="text">@if(!empty($post)) {{$post->text}} @endif</textarea>
            </div>
            <div class="btn-container text-left media" data-id="1">
                <label>Image:<span class="required-mark">*</span></label>
                <figure class="image-visualization">
                    @if(!empty($post) && !empty($post->media))
                        <img class="small-image" src="{{URL::asset('assets/uploads/'.$post->media->name) }}"/>
                        <span class="inline-block-top remove-image"><i class="fa fa-times" aria-hidden="true"></i></span>
                    @endif
                </figure>
                <a href="javascript:openMedia(1, true, 'image')" class="btn">Select image</a>
                <input type="hidden" class="hidden-input-image" name="image" value="@if(!empty($post->media)) {{$post->media->id}} @endif">
            </div>
            <div class="btn-container">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <button class="btn">Save</button>
            </div>
        </form>
    </section>
@endsection