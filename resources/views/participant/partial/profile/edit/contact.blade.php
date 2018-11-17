<form class="control" role="form" method="POST" action="{{ url('/participant/profile/contact') }}"
      enctype="multipart/form-data">
  {{ csrf_field() }}
  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Contact Person <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="contact_name" class="input{{ $errors->has('contact_name') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('contact_name') ?: object_get($participant, 'company.contact_name') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_name'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Photo <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
        @include('common.partial.manage-photo', ['photo' => object_get($participant, 'company.contact_photo')])
        <div class="file has-name is-fullwidth">
          <label class="file-label">
            <input class="file-input" type="file" name="contact_photo" id="contact_photo">
            <span class="file-cta">
                      <span class="file-icon">
                        <i class="fa fa-upload"></i>
                      </span>
                      <span class="file-label">
                        Choose a file…
                      </span>
                    </span>
            <span class="file-name"
                  id="contact_photo_filename">Accepted file types: jpg (400 x 400). 1MB maximum.</span>
          </label>
        </div>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_photo'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Title <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="contact_title"
                 class="input{{ $errors->has('contact_title') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('contact_title') ?: object_get($participant, 'company.contact_title') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_title'])
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
          <input name="contact_phone"
                 class="input{{ $errors->has('contact_phone') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('contact_phone') ?: object_get($participant, 'company.contact_phone') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_phone'])
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
          <input name="contact_email"
                 class="input{{ $errors->has('contact_email') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('contact_email') ?: object_get($participant, 'company.contact_email') }}"
                 required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_email'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <a class="button is-info" href="{{ url('/participant/profile/company') }}" onclick="return checkSave(this)">
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
@section('scripts')
  <script>
      $('#contact_photo').change(function (event) {
          if (event.target.files.length > 0) {
              $('#contact_photo_filename').text(event.target.files[0].name + ' selected, click Save button to upload');
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