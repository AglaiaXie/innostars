<h3 class="title is-3"></h3>
<div class="columns">
  <div class="column is-10 is-offset-1">
    <h4 class="title is-4">Company Information</h4>
    <hr>
    @include('participant.partial.profile.show.company')

    <br>
    <h4 class="title is-4">Contact Information</h4>
    <hr>
    @include('participant.partial.profile.show.contact')

    <br>
    <h4 class="title is-4">Project Information</h4>
    <hr>
    @include('participant.partial.profile.show.project')

    <br>
    <h4 class="title is-4">Additional Information</h4>
    <hr>
    @include('participant.partial.profile.show.addition')

    <br>
    <h4 class="title is-4">Documents</h4>
    <hr>
    @include('participant.partial.profile.show.file')

  </div>
</div>
<hr>
<form class="control" role="form" method="POST" action="{{ url('/participant/profile/submit') }}">
  {{ csrf_field() }}
  <h4 class="title is-4">
    Submit My Application:
    <small>Please click the following checkbox before submission.</small>
  </h4>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label"></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <label class="label">
            <input type="checkbox" name="confirm_1"{{ old('confirm_1') ? ' checked' :'' }}>
            Note: You can't update your project information once the application is submitted.
          </label>
          <strong>I have reviewed my application above and all the information is correct.</strong>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'confirm_1'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <a class="button is-info" href="{{ url('/participant/profile/file') }}">
          <span class="icon is-small"><i class="fa fa-arrow-left"></i></span><span>Previous Step</span>
        </a>
        <button class="button is-primary">
          <span class="icon is-small"><i class="fa fa-save"></i></span><span>Submit</span>
        </button>
      </div>
    </div>
  </div>
</form>
