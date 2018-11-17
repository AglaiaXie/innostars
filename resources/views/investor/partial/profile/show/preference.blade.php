@include('common.partial.competition-property', [
    'name' => 'Which stage(s) of the Competition are you interested participating in?',
     'items' => $user->investor_profile->participating_competitions
     ])
@include('common.partial.list-property', [
  'name' => 'Is your company looking for technologies in one of the areas? If yes, in which industry??',
  'items' => $user->investor_profile->interested_industries->pluck('name')])
@include('common.partial.list-industry', [
  'name' => 'Which sub industries?',
  'industries' => $user->investor_profile->interested_sub_industries ])
