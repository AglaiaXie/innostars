<form class="control" role="form" method="POST" action="{{ url('/participant/profile/file') }}"
      enctype="multipart/form-data">
  {{ csrf_field() }}

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Executive Summary <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
        @include('common.partial.manage-file', ['file' => object_get($participant, 'company.executive_summary')])
        <div class="file has-name is-fullwidth">
          <label class="file-label">
            <input class="file-input" type="file" name="executive_summary" id="executive_summary">
            <span class="file-cta">
                      <span class="file-icon">
                        <i class="fa fa-upload"></i>
                      </span>
                      <span class="file-label">
                        Choose a file…
                      </span>
                    </span>
            <span class="file-name"
                  id="executive_summary_filename">Accepted file types: pdf, doc, docx, ppt, pptx. 10MB maximum.</span>
          </label>
        </div>
        </p>
        <p>Click <a href="{{ url('files/InnoSTARS_Business_Plan_Template.docx') }}" target="_blank">here</a> to download
          the Executive Summary Template</p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'executive_summary'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Pitch Deck <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
        @include('common.partial.manage-file', ['file' => object_get($participant, 'company.pitch_deck')])
        <div class="file has-name is-fullwidth">
          <label class="file-label">
            <input class="file-input" type="file" name="pitch_deck" id="pitch_deck">
            <span class="file-cta">
                      <span class="file-icon">
                        <i class="fa fa-upload"></i>
                      </span>
                      <span class="file-label">
                        Choose a file…
                      </span>
                    </span>
            <span class="file-name"
                  id="pitch_deck_filename">Accepted file types: pdf, doc, docx, ppt, pptx. 10MB maximum.</span>
          </label>
        </div>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'pitch_deck'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Additional Information (Optional)</label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
        @include('common.partial.manage-file', ['file' => object_get($participant, 'company.other_file_1')])
        <div class="file has-name is-fullwidth">
          <label class="file-label">
            <input class="file-input" type="file" name="other_file_1" id="other_file_1">
            <span class="file-cta">
                      <span class="file-icon">
                        <i class="fa fa-upload"></i>
                      </span>
                      <span class="file-label">
                        Choose a file…
                      </span>
                    </span>
            <span class="file-name"
                  id="other_file_1_filename">Accepted file types: pdf, doc, docx, ppt, pptx. 10MB maximum.</span>
          </label>
        </div>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'other_file_1'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Additional Information (Optional)</label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
        @include('common.partial.manage-file', ['file' => object_get($participant, 'company.other_file_2')])
        <div class="file has-name is-fullwidth">
          <label class="file-label">
            <input class="file-input" type="file" name="other_file_2" id="other_file_2">
            <span class="file-cta">
                      <span class="file-icon">
                        <i class="fa fa-upload"></i>
                      </span>
                      <span class="file-label">
                        Choose a file…
                      </span>
                    </span>
            <span class="file-name"
                  id="other_file_2_filename">Accepted file types: pdf, doc, docx, ppt, pptx. 10MB maximum.</span>
          </label>
        </div>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'other_file_2'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <a class="button is-info" href="{{ url('/participant/profile/addition') }}" onclick="return checkSave(this)">
          <span class="icon is-small"><i class="fa fa-arrow-left"></i></span><span>Previous Step</span>
        </a>
        <button class="button is-primary">
          <span class="icon is-small"><i class="fa fa-save"></i></span><span>Save & Next Step</span>
        </button>
      </div>
    </div>
  </div>
</form>
@section('scripts')
  <script>
      $('#executive_summary').change(function (event) {
          if (event.target.files.length > 0) {
              $('#executive_summary_filename').text(event.target.files[0].name + ' selected, click Save button to upload');
          }
      });
      $('#pitch_deck').change(function (event) {
          if (event.target.files.length > 0) {
              $('#pitch_deck_filename').text(event.target.files[0].name + ' selected, click Save button to upload');
          }
      });
      $('#other_file_1').change(function (event) {
          if (event.target.files.length > 0) {
              $('#other_file_1_filename').text(event.target.files[0].name + ' selected, click Save button to upload');
          }
      });
      $('#other_file_2').change(function (event) {
          if (event.target.files.length > 0) {
              $('#other_file_2_filename').text(event.target.files[0].name + ' selected, click Save button to upload');
          }
      });

      function deleteFile(file) {
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
              complete: function () {
                  btn.parent().remove();
              }
          });
          return false;
      }
  </script>
@endsection
