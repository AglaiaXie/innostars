<form class="control" role="form" method="POST" action="{{ url('/participant/profile/company') }}"
      enctype="multipart/form-data">
  {{ csrf_field() }}
  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Company Name <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="name" class="input{{ $errors->has('name') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('name') ?: object_get($participant, 'company.name') }}" required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'name'])
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
        <textarea name="description"
                  class="textarea input{{ $errors->has('description') ? ' is-danger' : '' }}"
                  rows="3"
                  required>{{ old('description') ?: object_get($participant, 'company.description') }}</textarea>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'description'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Company Logo <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
        @include('common.partial.manage-photo', ['photo' => object_get($participant, 'company.logo')])
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
      <label class="label">Company Size (Employees)<span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="size" class="input{{ $errors->has('size') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('size') ?: object_get($participant, 'company.size')}}" required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'size'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Year Established <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="established" class="input{{ $errors->has('established') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('established') ?: object_get($participant, 'company.established') }}" required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'established'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Type <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="select">
          <select name="type">
            @foreach($types as $type)
              <option value="{{$type}}"{{($type === old('type') ?: $type === object_get($participant, 'company.type')) ? ' selected':''}}>{{$type}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Website <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="website" class="input{{ $errors->has('website') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('website') ?: object_get($participant, 'company.website') }}" required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'website'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Company Address <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="address" class="input{{ $errors->has('address') ? ' is-danger' : '' }}" type="text"
                 value="{{ old('address') ?: object_get($participant, 'company.address')}}" required>
        </p>
        <p class="help">Address Line 1</p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'address'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="address2" class="input{{ $errors->has('address2') ? ' is-danger' : '' }}" type="text"
                 value="{{ old('address2') ?: object_get($participant, 'company.address2') }}">
        </p>
        <p class="help">Address Line 2</p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'address2'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="city" class="input{{ $errors->has('city') ? ' is-danger' : '' }}" type="text"
                 value="{{ old('city') ?: object_get($participant, 'company.city') }}" required>
        </p>
        <p class="help">City</p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'city'])
      </div>
      <div class="field">
        <div class="control">
          <div class="select is-fullwidth">
            <select name="state">
              @foreach($states as $state)
                <option value="{{ $state }}"{{($state === old('state') ?: $state === object_get($participant, 'company.state')) ? ' selected':''}}>{{ $state }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <p class="help">State</p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'state'])
      </div>
      <div class="field">
        <p class="control">
          <input name="zip_code" class="input{{ $errors->has('zip_code') ? ' is-danger' : '' }}" type="text"
                 value="{{ old('zip_code') ?: object_get($participant, 'company.zip_code')}}" required>
        </p>
        <p class="help">Zip Code</p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'zip_code'])
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

@section('scripts')
  <script>
      $('#logo').change(function (event) {
          if (event.target.files.length > 0) {
              $('#logo_filename').text(event.target.files[0].name + ' selected, click Save button to upload');
          }
      });

      function deleteFile(file)
      {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });

          var btn = $(file);
          btn.addClass("is-loading");
          console.log($(file).data('id'));
          $.ajax({
              url: '{{ url('/file') }}/' + btn.data('id'),
              type: 'DELETE',
              complete: function() {
                  btn.parent().remove();
              }
          });
          return false;
      }
  </script>
@endsection