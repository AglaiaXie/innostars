@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
    <div class="hero is-info">
        @role('participant')
        @include('participant.partial.hero', ['participant' => $user])
        @endrole
        @role('judge')
        @include('judge.partial.hero', ['judge' => $user])
        @endrole
    </div>
@endsection

@section('content')
    @include('common.partial.message')
    @include('common.partial.message.' . $partial)
  <script>
      function openMessageModal(id) {
          var uid = $("#uid");
          if (uid.val() != id) {
              uid.val(id);
              $("#subject").val('');
              $("#content").val('');
          }
          $("#message_modal").addClass('is-active');
      }

      function closeMessageModal() {
          $("#message_modal").removeClass('is-active');
      }
  </script>
@endsection