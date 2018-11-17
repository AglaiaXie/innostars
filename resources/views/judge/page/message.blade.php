@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
  <section class="hero is-info">
    @include('judge.partial.hero')
  </section>
  <section class="section">
    <button class="button is-primary is-pulled-right" id="newMessageButton" onclick="openMessageModal()">
      <span class="icon is-small"><i class="fa fa-comments"></i></span><span>New Ticket</span></button>
    <table class="table is-fullwidth">
      <thead>
      <tr>
        <th>Subject</th>
        <th>User</th>
        <th>Date</th>
        <th>Replies</th>
      </tr>
      </thead>
      <tbody>
      @foreach($threads as $thread)
        <tr>
          <td>
            <a onclick="openThreadModal({{$thread->id}})">
              @if($thread->isUnread(Auth::id()))
                <b>{{$thread->subject}}</b>
              @else
                {{$thread->subject}}
              @endif
            </a>
          </td>
          <td>{{$thread->participantsString(null, ['first_name', 'last_name'])}}</td>
          <td>
            {{$thread->latest_message->created_at}}
          </td>
          <td>
            {{$thread->messages->count() - 1}}
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </section>
  @include('common.partial.message-new')
  @include('common.partial.message-reply')
  <script>
      function openThreadModal(tid) {
          var postUrl = '{{ url('/judge/threads') }}/';
          $('#messages').empty();
          $.ajax('/judge/threads/' + tid)
              .always(function (data) {
                  data.messages.forEach(function (message) {
                      var template = $('#template').clone();
                      template.find('[data-name]').text(message.user.first_name + ' ' + message.user.last_name);
                      template.find('[data-date]').text(message.created_at);
                      template.find('[data-message]').text(message.body);
                      $('#messages').append(template);
                  });
                  $('#replyForm').attr('action', postUrl + tid);
                  $("#thread_modal").addClass('is-active');
              });
      }

      function closeThreadModal() {
          $("#thread_modal").removeClass('is-active');
      }

      function openMessageModal() {
          $("#message_modal").addClass('is-active');
      }

      function closeMessageModal() {
          $("#message_modal").removeClass('is-active');
      }
  </script>
@endsection