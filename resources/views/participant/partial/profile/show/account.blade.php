@include('common.partial.detail-property', ['name' => 'Email', 'value' => object_get($participant, 'email')])
@include('common.partial.detail-property', ['name' => 'First Name', 'value' => object_get($participant, 'first_name')])
@include('common.partial.detail-property', ['name' => 'Last Name', 'value' => object_get($participant, 'last_name')])
@include('common.partial.detail-property', ['name' => 'SXSW', 'value' => $participant->sxsw ? 'Yes' : 'No'])
@include('common.partial.detail-property', ['name' => 'Submitted', 'value' => object_get($participant, 'company.submit') ? 'Yes' : 'No'])
<form class="control" role="form" method="POST" action="{{ url('/admin/participants/' . object_get($participant, 'id')) }}">
  {{ csrf_field() }}
  {{ method_field('PUT') }}
  <input type="hidden" name="submit" value="{{ object_get($participant, 'company.submit') ? 0 : 1}}">
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
@include('common.partial.detail-property', ['name' => 'Approved', 'value' => object_get($participant, 'company.approval') ? 'Yes' : 'No'])
<form class="control" role="form" method="POST"  action="{{ url('/admin/participants/' . object_get($participant, 'id')) }}">
  {{ csrf_field() }}
  {{ method_field('PUT') }}
  <input type="hidden" name="approval" value="{{ object_get($participant, 'company.approval') ? 0 : 1 }}">
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