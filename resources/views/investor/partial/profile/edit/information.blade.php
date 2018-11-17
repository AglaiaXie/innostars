<form class="control" role="form" method="POST">
  {{ csrf_field() }}

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Company Name 公司名<span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="company_name" class="input{{ $errors->has('company_name') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('company_name') ?: object_get($user, 'investor_profile.company_name') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_name'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Company Description 公司介绍</label>
    </div>
    <div class="field-body">
      <div class="field">
      <p class="control">
        <textarea name="company_description"
                  class="textarea input{{ $errors->has('company_description') ? ' is-danger' : '' }}"
                  rows="3"
                  >{{ old('company_description') ?: object_get($user, 'investor_profile.company_description') }}</textarea>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'company_description'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Job Title 职位<span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="title"
                 class="input{{ $errors->has('title') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('title') ?: object_get($user, 'investor_profile.title') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'title'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Phone 常用电话<span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="phone"
                 class="input{{ $errors->has('phone') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('phone') ?: object_get($user, 'investor_profile.phone') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'phone'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Highest Degree Attained 最高学历</label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="education"
                 class="input{{ $errors->has('education') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('education') ?: object_get($user, 'investor_profile.education') }}"
                 >
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'education'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">How did you hear about us? 您是从哪里了解到此活动？<span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="refer"
                 class="input{{ $errors->has('refer') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('refer') ?: object_get($user, 'investor_profile.refer') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'refer'])
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
            <span class="icon is-small"><i class="fa fa-save"></i></span><span>Save & Next Step 下一步</span>
          </button>
        </p>
      </div>
    </div>
  </div>
</form>
