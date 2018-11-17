<form class="control" role="form" method="POST" enctype="multipart/form-data">
  {{ csrf_field() }}

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Organization Name <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="company_name" class="input{{ $errors->has('company_name') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('company_name') ?: object_get($user, 'partner_profile.company_name') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_name'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Organization Description <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
      <p class="control">
        <textarea name="company_description"
                  class="textarea input{{ $errors->has('company_description') ? ' is-danger' : '' }}"
                  rows="3"
                  required>{{ old('company_description') ?: object_get($user, 'partner_profile.company_description') }}</textarea>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'company_description'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Organization Logo <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
        @include('common.partial.manage-photo', ['photo' => object_get($user, 'partner_profile.real_logo')])
        <div class="file has-name is-fullwidth">
          <label class="file-label">
            <input class="file-input" type="file" name="logo" id="logo">
            <span class="file-cta">
                      <span class="file-icon">
                        <i class="fa fa-upload"></i>
                      </span>
                      <span class="file-label">
                        Choose a file…
                      </span>
                    </span>
            <span class="file-name"
                  id="logo_filename">Accepted file types: jpg (400 x 400). 1MB maximum.</span>
          </label>
        </div>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'logo'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Contact Person <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="contact_person"
                 class="input{{ $errors->has('contact_person') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('contact_person') ?: object_get($user, 'partner_profile.contact_person') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_person'])
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
                 value="{{ old('title') ?: object_get($user, 'partner_profile.title') }}"
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
                 value="{{ old('phone') ?: object_get($user, 'partner_profile.phone') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'phone'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Email <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="email"
                 class="input{{ $errors->has('email') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('email') ?: object_get($user, 'partner_profile.email') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'email'])
      </div>
    </div>
  </div>

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
                 value="{{ old('refer') ?: object_get($user, 'partner_profile.refer') }}"
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
            <span class="icon is-small"><i class="fa fa-save"></i></span><span>Save & Next Step</span>
          </button>
        </p>
      </div>
    </div>
  </div>
</form>
