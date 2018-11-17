@include('common.partial.competition-property', [
    'name' => 'Which stage(s) of the Competition are you participating in?',
     'items' => $user->partner_profile->participating_competitions
     ])
@include('common.partial.detail-property', ['name' => 'Reason to be partner', 'value' => $user->partner_profile->reason])
