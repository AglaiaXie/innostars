@include('common.partial.detail-property', ['name' => 'Company Name', 'value' => $user->partner_profile->company_name])
@include('common.partial.detail-property', ['name' => 'Company Description', 'value' => $user->partner_profile->company_description])
<div class="field is-horizontal">
    <div class="field-label">
        <label class="label is-normal">Organization Logo</label>
    </div>
    <div class="field-body">
        <div class="field">
            @if($resume = object_get($user, 'partner_profile.real_logo'))
                <div class="column">
                    <span class="fa fa-file"></span> {{ $real_logo->filename }}
                    <a class="button is-link is-small" href="{{ url('/file/' . $real_logo->disk_name) }}">Download</a>
                </div>
            @endif
        </div>
    </div>
</div>
@include('common.partial.detail-property', ['name' => 'Contact Person', 'value' => $user->partner_profile->contact_person])
@include('common.partial.detail-property', ['name' => 'Job Title', 'value' => $user->partner_profile->title])
@include('common.partial.detail-property', ['name' => 'Phone', 'value' => $user->partner_profile->phone])
@include('common.partial.detail-property', ['name' => 'Email', 'value' => $user->partner_profile->email])