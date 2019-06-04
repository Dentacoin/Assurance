<div class="upload-media-container">
    <form method="post" enctype="multipart/form-data" class="text-center" id="upload-media">
        <div class="single-row">
            <div class="subtitle"><label>Select images:</label></div>
            <input type="file" name="images[]" accept=".jpg,.jpeg,.png,.svg,.gif,.pdf,.doc,.docx,.rtf,.zip,.rar" multiple/>
        </div>
        @if(Route::current()->getName() != 'media')
            <input type="hidden" name="ajax_media" value="true">
        @endif
        <div class="btn-container">
            <button class="btn">Add</button>
        </div>
    </form>
</div>
<table class="table table-without-reorder table-bordered table-striped text-left media-table">
    <thead>
        <tr>
            <th>Image <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="If the selected images you want to add are .svg format it doesn't matter the width or height, they're vectors."></i></th>
            <th>Name</th>
            <th>Link</th>
            <th>Alt <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Text describing the image. Used also for SEO and accessibility standard. When resource is not found this text will appear. Maximum length 125 symbols."></i></th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @if(count($media) > 0)

        @foreach($media as $single_media)
            @if(in_array($single_media->type, ['jpeg', 'jpg', 'png', 'svg', 'gif']))
                @php($type = 'image')
            @else
                @php($type = 'file')
            @endif
            <tr data-id="{{ $single_media->id }}" data-src="{{ $single_media->getLink() }}" data-alt="@if($type == 'image'){{ $single_media->alt }}@endif">
                <td>
                    @if($type == 'file')
                        <a href="{{ $single_media->getLink() }}" download><i class="fa fa-file-text-o fs-50" aria-hidden="true"></i></a>
                    @elseif($type == 'image')
                        <img src="{{ $single_media->getLink() }}" class="small-image"/>
                    @endif
                </td>
                <td>{{ $single_media->name }}</td>
                <td>
                    <input type="text" value="{{ $single_media->getLink() }}"/>
                </td>
                <td>
                    @if($type == 'file')
                        Document files don't need alt.
                    @elseif($type == 'image')
                        <input type="text" class="alt-attribute" value="{{ $single_media->alt }}"/>
                    @endif
                </td>
                <td>{{ $single_media->created_at }}</td>
                <td>
                    @if(!empty($popup))
                        <a href="javascript:void(0);" class="btn use-media" data-type="{{$type}}">Use</a>
                    @endif
                    <a href="{{ route('delete-media', ['id' => $single_media->id]) }}" onclick="return confirm('Are you sure you want to delete this resource?')" class="btn">Delete</a>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" class="text-center">
                <a href="javascript:void(0);" class="btn save-image-alts">Save image alts</a>
            </td>
        </tr>
    </tfoot>
</table>
