@if($photo)
  <div class="column">
    <figure class="image" style="width:400px">
      <img src="{{ url('/file/' . $photo->disk_name) }}" style="display:block;width:100%;height:auto">
    </figure>
    <button class="button is-danger is-small" data-id="{{$photo->id}}" onclick="return deleteFile(this)">Delete</button>
  </div>
@endif
