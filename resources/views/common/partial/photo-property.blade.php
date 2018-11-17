<div class="field is-horizontal">
  <div class="field-label is-normal">
    <label class="label">{{ $name }}</label>
  </div>
  <div class="field-body">
    <div class="field">
      @include('common.partial.show-photo', ['photo' => $photo])
    </div>
  </div>
</div>