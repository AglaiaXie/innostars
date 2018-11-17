@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
  <section class="section">
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
          <td>{{$thread->participantsString(Auth::id(), ['first_name', 'last_name'])}}</td>
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
  <div class="modal" id="thread_modal">
    <div class="modal-background"></div>
    <div class="modal-content">
      <div class="box">
        <div id="messages">
        </div>
        <hr>
        <form method="POST" action="" id="replyForm">
          {{ csrf_field() }}
          {{ method_field('PUT') }}
          <article class="media">
            <figure class="media-left">
              <p class="image is-64x64">
                <img src="https://bulma.io/images/placeholders/128x128.png">
              </p>
            </figure>
            <div class="media-content">
              <input type="hidden" id="uid" name="uid">
              <div class="field">
                <p class="control">
                  <textarea class="textarea" id="message" name="message" placeholder="Write your message..."
                            required></textarea>
                </p>
              </div>
              <nav class="level">
                <div class="level-left">
                  <div class="level-item">
                    <button type="submit" class="button is-info">Send</button>
                  </div>
                </div>
              </nav>
            </div>
          </article>
        </form>
      </div>
      <button class="modal-close is-large" aria-label="close" onclick="closeThreadModal()"></button>
    </div>
  </div>
  <div hidden>
    <article class="media" id="template">
      <figure class="media-left">
        <p class="image is-64x64">
          <img src="https://bulma.io/images/placeholders/128x128.png">
        </p>
      </figure>
      <div class="media-content">
        <div class="content">
          <p>
            <strong data-name></strong>
            <small data-date></small>
          </p>
          <p data-message>
          </p>
        </div>
      </div>
    </article>
  </div>
  <script>
      function openThreadModal(tid) {
          var postUrl = '{{ url('/admin/threads') }}/';
          $('#messages').empty();
          $.ajax('/admin/threads/' + tid)
              .always(function(data) {
                  data.messages.forEach(function(message) {
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
  </script>
@endsection