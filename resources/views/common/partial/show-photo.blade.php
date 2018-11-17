@if($photo)
  <div class="column">
    <figure class="image" style="width:400px">
      <img src="{{ url('/file/' . $photo->disk_name) }}" style="display:block;width:100%;height:auto">
    </figure>
  </div>
@endif
