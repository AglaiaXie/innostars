<form class="control" role="form" method="POST" action="{{ url('/judge/profile/addition') }}">
  {{ csrf_field() }}

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">How did you hear about us? <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="refer"
                 class="input{{ $errors->has('refer') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('refer') ?: object_get($judge, 'judge_profile.refer') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'refer'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Have you been a judge for other competitions? If so, could you provide a name? <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="experience"
                 class="input{{ $errors->has('experience') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('experience') ?: object_get($judge, 'judge_profile.experience') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'experience'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <a class="button is-info" href="{{ url('/judge/profile/preference') }}" onclick="return checkSave(this)">
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
