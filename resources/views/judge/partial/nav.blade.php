<nav class="tabs is-boxed">
  <ul>
    <li class="{{Request::segment(2) === 'profile' ? 'is-active' : ''}}" >
      <a href="{{ url('judge/profile/information') }}">Profile</a>
    </li>
    @if($judge->judge_profile->submit)
      <li class="{{Request::segment(2) === null ? 'is-active' : ''}}" >
        <a href="{{ url('judge') }}">Status</a>
      </li>
      <li class="{{Request::segment(2) === 'competitions' ? 'is-active' : ''}}" >
        <a href="{{ url('judge/competitions') }}">Competitions</a>
      </li>
      <li class="{{Request::segment(2) === 'messages' ? 'is-active' : ''}}" >
        <a href="{{ url('common/messages') }}">
          <span>Messages</span> &nbsp;
          @if ($count = Auth::user()->newThreadsCount())
            <span class="tag is-rounded is-danger">{{ $count }}</span>
          @endif
        </a>
      </li>
    @endif
  </ul>
</nav>