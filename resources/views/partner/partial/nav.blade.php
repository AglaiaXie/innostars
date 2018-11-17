<nav class="tabs is-boxed">
  <ul>
    <li class="{{Request::segment(2) === 'profile' ? 'is-active' : ''}}" >
      <a href="{{ url('partner/profile/information') }}">Profile</a>
    </li>
  </ul>
</nav>