<form class="control" role="form" method="POST" action="{{ url('/judge/profile/information') }}">
  {{ csrf_field() }}

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Company Name <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="company_name" class="input{{ $errors->has('company_name') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('company_name') ?: object_get($judge, 'judge_profile.company_name') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_name'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Company Description <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
      <p class="control">
        <textarea name="company_description"
                  class="textarea input{{ $errors->has('company_description') ? ' is-danger' : '' }}"
                  rows="3"
                  required>{{ old('company_description') ?: object_get($judge, 'judge_profile.company_description') }}</textarea>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'company_description'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Job Title <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="title"
                 class="input{{ $errors->has('title') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('title') ?: object_get($judge, 'judge_profile.title') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'title'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Phone <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="phone"
                 class="input{{ $errors->has('phone') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('phone') ?: object_get($judge, 'judge_profile.phone') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'phone'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Highest Degree Attained <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="education"
                 class="input{{ $errors->has('education') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('education') ?: object_get($judge, 'judge_profile.education') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'education'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <button class="button is-primary">
            <span class="icon is-small"><i class="fa fa-save"></i></span><span>Save & Next Step</span>
          </button>
        </p>
      </div>
    </div>
  </div>
</form>
