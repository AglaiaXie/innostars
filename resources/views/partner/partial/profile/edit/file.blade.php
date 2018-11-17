<form class="control" role="form" method="POST" enctype="multipart/form-data">
  {{ csrf_field() }}

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Portrait <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
        @include('common.partial.manage-photo', ['photo' => object_get($user, 'partner_profile.logo')])
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
            <span class="file-name" id="logo_filename">Accepted file types: jpg (400 x 400). 1MB maximum.</span>
          </label>
        </div>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'logo'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Resume <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
        @include('common.partial.manage-file', ['file' => object_get($user, 'partner_profile.document')])
        <div class="file has-name is-fullwidth">
          <label class="file-label">
            <input class="file-input" type="file" name="document" id="document">
            <span class="file-cta">
                      <span class="file-icon">
                        <i class="fa fa-upload"></i>
                      </span>
                      <span class="file-label">
                        Choose a file…
                      </span>
                    </span>
            <span class="file-name"
                  id="document_filename">Accepted file types: pdf, doc, docx. 10MB maximum.</span>
          </label>
        </div>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'document'])
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
      $('#logo').change(function (event) {
          if (event.target.files.length > 0) {
              $('#logo_filename').text(event.target.files[0].name + ' selected, click Save button to upload');
          }
      });
      $('#document').change(function (event) {
          if (event.target.files.length > 0) {
              $('#document_filename').text(event.target.files[0].name + ' selected, click Save button to upload');
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
