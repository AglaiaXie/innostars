@include('common.partial.file-property', ['label' => 'Executive Summary', 'file' => object_get($participant, 'company.executive_summary')])
@include('common.partial.file-property', ['label' => 'Pitch Deck', 'file' => object_get($participant, 'company.pitch_deck')])
@include('common.partial.file-property', ['label' => 'Additional Information (Optional)', 'file' => object_get($participant, 'company.other_file_1')])
@include('common.partial.file-property', ['label' => 'Additional Information (Optional)', 'file' => object_get($participant, 'company.other_file_2')])