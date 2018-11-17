<div class="field is-horizontal">
  <div class="field-label is-normal">
    <label class="label">Portrait</label>
  </div>
  <div class="field-body">
    <div class="field">
      @if($photo = object_get($user, 'investor_profile.photo'))
        <div class="column">
          <figure class="image" style="width:400px">
            <img src="{{ url('/file/' . $photo->disk_name) }}" style="display:block;width:100%;height:auto">
          </figure>
        </div>
      @endif
    </div>
  </div>
</div>

<div class="field is-horizontal">
  <div class="field-label">
    <label class="label is-normal">Resume</label>
  </div>
  <div class="field-body">
    <div class="field">
      @if($resume = object_get($user, 'investor_profile.resume'))
        <div class="column">
          <span class="fa fa-file"></span> {{ $resume->filename }}
          <a class="button is-link is-small" href="{{ url('/file/' . $resume->disk_name) }}">Download</a>
        </div>
      @endif
    </div>
  </div>
</div>

@include('common.partial.detail-property', ['name' => 'linkedin 领英', 'value' => $user->investor_profile->linkedin])