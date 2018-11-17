@include('common.partial.detail-property', ['name' => 'Email', 'value' => $judge->email])
@include('common.partial.detail-property', ['name' => 'First Name', 'value' => $judge->first_name])
@include('common.partial.detail-property', ['name' => 'Last Name', 'value' => $judge->last_name])
@include('common.partial.detail-property', ['name' => 'SXSW', 'value' => $judge->sxsw ? 'Yes' : 'No'])
@include('common.partial.detail-property', ['name' => 'Submitted', 'value' => $judge->judge_profile->submit ? 'Yes' : 'No'])
<form class="control" role="form" method="POST" action="{{ url('/admin/judges/' . $judge->id) }}">
  {{ csrf_field() }}
  {{ method_field('PUT') }}
  <input type="hidden" name="submit" value="{{ $judge->judge_profile->submit ? 0 : 1}}">
  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <button class="button is-primary">Change Registration Submission Status</button>
        </p>
      </div>
    </div>
  </div>
</form>
@include('common.partial.detail-property', ['name' => 'Approved', 'value' => $judge->judge_profile->approval ? 'Yes' : 'No'])
<form class="control" role="form" method="POST"  action="{{ url('/admin/judges/' . $judge->id) }}">
  {{ csrf_field() }}
  {{ method_field('PUT') }}
  <input type="hidden" name="approval" value="{{ $judge->judge_profile->approval ? 0 : 1 }}">
  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <button class="button is-primary">Change Approval Status</button>
        </p>
      </div>
    </div>
  </div>
</form>
@if ($message = Session::get('message'))
  @include('common.partial.flash-message')
@endif