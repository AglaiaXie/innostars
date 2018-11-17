<form class="control" role="form" method="POST" enctype="multipart/form-data">
  {{ csrf_field() }}

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Portrait 照片</label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
        @include('common.partial.manage-photo', ['photo' => object_get($user, 'investor_profile.photo')])
        <div class="file has-name is-fullwidth">
          <label class="file-label">
            <input class="file-input" type="file" name="photo" id="photo">
            <span class="file-cta">
                      <span class="file-icon">
                        <i class="fa fa-upload"></i>
                      </span>
                      <span class="file-label">
                        Choose a file…
                      </span>
                    </span>
            <span class="file-name"
                  id="photo_filename">Accept 10MB maximum.</span>
          </label>
        </div>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'photo'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Resume 简历</label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
        @include('common.partial.manage-file', ['file' => object_get($user, 'investor_profile.resume')])
        <div class="file has-name is-fullwidth">
          <label class="file-label">
            <input class="file-input" type="file" name="resume" id="resume">
            <span class="file-cta">
                      <span class="file-icon">
                        <i class="fa fa-upload"></i>
                      </span>
                      <span class="file-label">
                        Choose a file…
                      </span>
                    </span>
            <span class="file-name"
                  id="resume_filename">Accept 10 MB maximum.</span>
          </label>
        </div>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'resume'])
      </div>
    </div>
  </div>

    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label class="label">Linkedin 领英</label>
        </div>
        <div class="field-body">
            <div class="field">
                <p class="control">
                    <input name="linkedin"
                           class="input{{ $errors->has('linkedin') ? ' is-danger' : '' }}"
                           type="text"
                           value="{{ old('linkedin') ?: object_get($user, 'investor_profile.linkedin') }}"
                           required>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'linkedin'])
            </div>
        </div>
    </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
          <p class="control">
              <a class="button is-info" href="preference" onclick="return checkSave(this)">
                  <span class="icon is-small"><i class="fa fa-arrow-left"></i></span><span>Previous Step 上一步</span>
              </a>
              <button class="button is-primary">
                  <span class="icon is-small"><i class="fa fa-save"></i></span><span>Save & Next Step 下一步</span>
              </button>
          </p>
      </div>
    </div>
  </div>
</form>
@section('scripts')
  <script>
      $('#photo').change(function (event) {
          if (event.target.files.length > 0) {
              $('#photo_filename').text(event.target.files[0].name + ' selected, click Save button to upload');
          }
      });
      $('#resume').change(function (event) {
          if (event.target.files.length > 0) {
              $('#resume_filename').text(event.target.files[0].name + ' selected, click Save button to upload');
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
