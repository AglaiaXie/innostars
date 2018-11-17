@include('common.partial.list-property', [
  'name' => 'What industry(s) would you like to judge?',
  'items' => $judge->judge_profile->judging_industries->pluck('name')])
@include('common.partial.list-industry', [
  'name' => 'What sub industry(s) would you like to judge?',
  'industries' => $judge->judge_profile->judging_sub_industries ])
@include('common.partial.competition-property', ['name' => 'Which stage(s) of the Competition are you interested in judging in?', 'items' => $judge->judge_profile->judging_competitions])
@include('common.partial.list-property', [
  'name' => 'Is your company looking for technologies in one of the areas? If yes, in which industry??',
  'items' => $judge->judge_profile->interested_industries->pluck('name')])
@include('common.partial.list-industry', [
  'name' => 'Which sub industries?',
  'industries' => $judge->judge_profile->interested_sub_industries ])
