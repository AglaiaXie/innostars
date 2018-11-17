@if($file)
    <div class="column">
        <span class="fa fa-file"></span> {{ $file->filename }}
        <a class="button is-link is-small" href="{{ url('/file/' . $file->disk_name) }}">Download</a>
    </div>
@endif
