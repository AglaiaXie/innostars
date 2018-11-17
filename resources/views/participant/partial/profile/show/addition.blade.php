@include('common.partial.detail-property', ['name' => 'Team Introduction', 'value' => object_get($participant, 'company.team_description')])
@include('common.partial.detail-property', ['name' => 'Description of Technical Requirements', 'value' => object_get($participant, 'company.tech_requirement')])
@include('common.partial.detail-property', ['name' => 'Description of the Competitive Advantages and Sustainability of the Technology/Project', 'value' => object_get($participant, 'company.tech_advantage')])
@include('common.partial.detail-property', ['name' => 'Risk of Technology', 'value' => object_get($participant, 'company.tech_risk')])
@include('common.partial.detail-property', ['name' => 'Prospective Chinese Market Value/Business Value', 'value' => object_get($participant, 'company.business_value')])
@include('common.partial.detail-property', ['name' => 'How did you hear about us?', 'value' => object_get($participant, 'company.refer')])
@include('common.partial.detail-property', ['name' => 'Have you been to China before?', 'value' => object_get($participant, 'company.been_to_china')])