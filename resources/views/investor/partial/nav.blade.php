<nav class="tabs is-boxed">
  <ul>
    <li class="{{Request::segment(2) === 'profile' ? 'is-active' : ''}}" >
      <a href="{{ url('investor/profile/information') }}">Profile 用户信息</a>
    </li>
    @if($user->investor_profile->submit)
      <li class="{{Request::segment(2) === null ? 'is-active' : ''}}" >
        <a href="{{ url('investor') }}">Status 状态</a>
      </li>
    @endif
  </ul>
</nav>