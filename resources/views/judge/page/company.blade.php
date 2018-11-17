@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
    <div class="hero is-info">
        @include('judge.partial.hero')
    </div>
@endsection

@section('content')
    <div class="columns is-multiline">
      @foreach($companies as $company)
        <div class="column is-4">
          @include('common.partial.company-badge', [$company, 'prefix' => 'judge/companies/'])
        </div>
      @endforeach
    </div>
  <div class="modal" id="message_modal">
    <div class="modal-background" onclick="closeMessageModal()"></div>
    <div class="modal-content">
      <form method="POST" action="{{ url('/judge/threads') }}">
        {{ csrf_field() }}
        <div class="box">
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
                  <input class="input" type="text" id="subject" name="subject" placeholder="Your subject" required>
                </p>
              </div>
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
        </div>
      </form>
    </div>
    <button class="modal-close is-large" aria-label="close" onclick="closeMessageModal()"></button>
  </div>
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