<div class="btn-container text-left media padding-bottom-20" data-id="1">
    <label>File:<span class="required-mark">*</span></label>
    <figure class="image-visualization">
        @if(!empty($post) && !empty($post->url))
            <a href="{{$post->url}}">{{$post->url}}</a>
        @endif
    </figure>
    <a href="javascript:openMedia(1, true, 'file')" class="btn">Select file</a>
    <input type="hidden" name="url" class="hidden-input-url" value="@if(!empty($post)) {{$post->url}} @endif">
    <input type="hidden" name="image" class="hidden-input-image" value="@if(!empty($post->media)) {{$post->media->id}} @endif">
</div>