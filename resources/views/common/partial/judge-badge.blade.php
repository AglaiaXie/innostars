<div class="card">
  <div class="card-content">
    <div class="media">
      @if($judge->photo)
      <div class="media-left">
        <figure class="image is-128x128">
          <img src="{{ url('/file/' . $judge->photo->disk_name) }}" alt="Judge Photo">
        </figure>
      </div>
      @endif
      <div class="media-content">
        <p class="title is-5">
          {{ $judge->user->first_name }} {{ $judge->user->last_name }}
        </p>
          {{ $judge->title }}
      </div>
    </div>
    <div class="content">
      <p>{{ $judge->company_name }}</p>
      <p>
      <a class="button is-info" onclick="openMessageModal({{$judge->user->id}})">
        <span class="icon is-small"><i class="fa fa-comments"></i> </span> <span>Send Message</span>
      </a>

      </p>
    </div>
  </div>
</div>
