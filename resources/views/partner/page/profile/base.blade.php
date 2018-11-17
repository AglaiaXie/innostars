@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
    <section class="hero is-info">
        @include('partner.partial.hero')
    </section>
    @include('partner.partial.profile')
@endsection

@section('content')
    <div class="columns">
      <div class="column is-full">
        <p class="title is-3 is-spaced">{{ $title }}</p>
       @if($user->partner_profile->submit && !in_array($section, ['preference', 'submit']))
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
        @if($user->partner_profile->submit && in_array($section, ['preference', 'submit']))
          @include('partner.partial.profile.show.' . $section)
        @else
          @include('partner.partial.profile.edit.' . $section)
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
              console.log('changed');
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
