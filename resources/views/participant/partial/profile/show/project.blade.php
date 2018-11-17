@include('common.partial.detail-property', ['name' => 'Industries of Focus', 'value' => object_get($participant, 'company.industry.name')])
@include('common.partial.detail-property', ['name' => 'Sub Industries', 'value' => object_get($participant, 'company.sub_industry.name')])
@include('common.partial.detail-property', ['name' => 'Technology/Project Name', 'value' => object_get($participant, 'company.project_name')])
@include('common.partial.detail-property', ['name' => 'Technology/Project Description', 'value' => object_get($participant, 'company.project_description')])
@include('common.partial.detail-property', ['name' => 'Technology/Project Financing Stage', 'value' => object_get($participant, 'company.project_stage')])
@include('common.partial.detail-property', ['name' => 'Patent(s)', 'value' => object_get($participant, 'company.patents')])
@include('common.partial.list-property', ['name' => 'Preferred Way of Cooperation', 'items' => array_filter(explode(
',', object_get($participant, 'company.cooperation') . (object_get($participant, 'company.cooperation_alt') ? ',' . object_get($participant, 'company.cooperation_alt') : '')
))])
