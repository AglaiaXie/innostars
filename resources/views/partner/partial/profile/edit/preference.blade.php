<form class="control" role="form" method="POST">
  {{ csrf_field() }}

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Which stage(s) of the Competition are you participating in? <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          @foreach($competitions as $competition)
            <label class="label">
              <input type="checkbox"
                     name="participating_competitions[]"
                     {{in_array($competition->id, $competitions_selected) ? 'checked' : ''}}
                     value="{{ $competition->id }}"/>
              {{ $competition->name }} - {{ $competition->city }} {{ $competition->date ? $competition->date->format('m/d/Y') : 'TBD' }}
            </label>
        @endforeach
        <p class="help">Please select at least 1 competition</p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'participating_competitions'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Why you would like to host the competition? <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
        <textarea name="reason"
                  class="textarea input{{ $errors->has('reason') ? ' is-danger' : '' }}"
                  rows="3"
                  required>{{ old('reason') ?: object_get($user, 'partner_profile.reason') }}</textarea>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'reason'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <a class="button is-info" href="./information" onclick="return checkSave(this)">
            <span class="icon is-small"><i class="fa fa-arrow-left"></i></span><span>Previous Step</span>
          </a>
          <button class="button is-primary">
            <span class="icon is-small"><i class="fa fa-save"></i></span><span>Save & Next Step</span>
          </button>
        </p>
      </div>
    </div>
  </div>
</form>
