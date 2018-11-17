@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
    <div class="hero is-info">
        @include('participant.partial.hero')
    </div>
@endsection

@section('content')
  <section class="section">
    <div class="columns">
      <div class="column is-2">
        <h4 class="title is-4">
          Filter
        </h4>
        <div class="field">
          <label class="label">Industry</label>
          <div class="select">
            <select id="industrySelect" onclick="filter()">
              <option value="">All</option>
              @foreach($industries as $industry)
                <option value="{{$industry->id}}"{{ $industry->id == $industry_f ? ' selected' : '' }}>
                  {{$industry->name}}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="column is-10">
        <div class="columns is-multiline">
          @foreach($judges as $judge)
            <div class="column is-4">
              @include('common.partial.judge-badge', [$judge, 'prefix' => 'participant/judge-profiles/'])
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
  <div class="modal" id="message_modal">
    <div class="modal-background" onclick="closeMessageModal()"></div>
    <div class="modal-content">
      <form method="POST" action="{{ url('/participant/threads') }}">
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
      function filter() {
          var industry = $('#industrySelect').val();
          var area = $('#areaSelect').val();
          window.location.href = window.location.protocol
              + '//' + window.location.hostname
              + '/participant/judge-profiles?'
              + 'industry=' + industry;
      }

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
