@include('common.partial.detail-property', ['name' => 'Company Name', 'value' => $judge->judge_profile->company_name])
@include('common.partial.detail-property', ['name' => 'Company Description', 'value' => $judge->judge_profile->company_description])
@include('common.partial.detail-property', ['name' => 'Job Title', 'value' => $judge->judge_profile->title])
@include('common.partial.detail-property', ['name' => 'Phone', 'value' => $judge->judge_profile->phone])
@include('common.partial.detail-property', ['name' => 'Education', 'value' => $judge->judge_profile->education])