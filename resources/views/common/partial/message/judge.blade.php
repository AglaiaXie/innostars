  <section class="section">
    <form method="get">
      <div class="field is-grouped is-grouped-right">
        <div class="control">
          <input class="input" type="text" name="keyword" placeholder="Search Company/Contact Name"
                 value="{{ Request::get('keyword') }}">
        </div>
        <div class="control">
          <div class="select">
            <select name="industry">
              <option value="">All Industries</option>
              @foreach($industries as $industry)
                <option value="{{ $industry->getKey() }}"{{ Request::get('industry') == $industry->getKey() ? ' selected' : '' }}>{{ $industry->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="control">
          <button class="button is-primary">Search</button>
        </div>
        <div class="control">
          <a class="button" href="{{ url('common/messages/participants') }}">Reset</a>
        </div>
      </div>
    </form>
    <hr>
        <div class="columns is-multiline">
          @foreach($judges as $judge)
            <div class="column is-4">
              @include('common.partial.judge-badge', [$judge, 'prefix' => 'participant/judge-profiles/'])
            </div>
          @endforeach
        </div>
  </section>
  @include('common.partial.message-new')
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
