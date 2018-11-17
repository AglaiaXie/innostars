<nav class="tabs is-boxed">
  <ul>
    <li class="{{Request::segment(2) === 'profile' ? 'is-active' : ''}}" >
      <a href="{{ url('participant/profile/company') }}">Application</a>
    </li>
    @if($participant->company->submit)
      <li class="{{Request::segment(2) === null ? 'is-active' : ''}}" >
        <a href="{{ url('participant') }}">Status</a>
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