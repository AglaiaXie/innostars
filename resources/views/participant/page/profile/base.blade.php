@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
    <div class="hero is-info">
        @include('participant.partial.hero')
    </div>
    @include('participant.partial.application')
@endsection

@section('content')
    <div class="columns">
      <div class="column is-full">
        <p class="title is-3 is-spaced">{{ $title }}</p>
        @if((object_get($participant->company->joined_competitions()->competitionType(\App\Models\Competition::NAME_ONLINE)->first(), 'promoted') && $section !== 'submit') || ($participant->company->submit && in_array($section, ['company', 'information', 'contact', 'addition'])))
          <article class="message is-danger">
            <div class="message-body">
              <span class="icon"><i class="fa fa-edit"></i></span>
              Note: You can edit your information with "*" anytime, hit "Save & Next Step" to update your information.
            </div>
          </article>
        @endif
      </div>
    </div>
    <hr>
    <div class="columns">
      <div class="column is-full">
        @if((object_get($participant->company->joined_competitions()->competitionType(\App\Models\Competition::NAME_ONLINE)->first(), 'promoted') && $section !== 'submit') || ($participant->company->submit && in_array($section, ['company', 'information', 'contact', 'addition'])) || !$participant->company->submit)
          @include('participant.partial.profile.edit.' . $section)
        @else
          @include('participant.partial.profile.show.' . $section)
        @endif
      </div>
    </div>
@endsection

@section('modal')
  @include('common.partial.save-notify')
  @if ($message = Session::get('message'))
    @include('common.partial.flash-message')
  @endif
@endsection

@section('scripts')
  <script>
      var formChanged = false;
      $(document).ready(function () {
          $(':input').change(function () {
              formChanged = true;
          });
      });

      function checkSave(href) {
          if (formChanged) {
              $('#leftButton').attr('href', href);
              $('#saveNotifyModal').addClass('is-active');

              return false
          }

          return true;
      }
  </script>
@endsection