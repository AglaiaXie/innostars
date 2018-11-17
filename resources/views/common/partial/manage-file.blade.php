@if($file)
    <div class="column">
        <span class="fa fa-file"></span> {{ $file->filename }}
        <a class="button is-link is-small" href="{{ url('/file/' . $file->disk_name) }}">Download</a>
        <button class="button is-danger is-small" data-id="{{$file->id}}" onclick="return deleteFile(this)">Delete</button>
    </div>
@endif
