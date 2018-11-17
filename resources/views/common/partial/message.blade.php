<div class="navbar-tabs">
  <a class="navbar-item is-tab {{Request::segment(3) === 'participants' ? ' is-active' : ''}}" href="{{url('/common/messages/participants')}}">
    <icon class="icon"><i class="fa fa-rocket"></i> </icon><span>Contestants</span>
  </a>

  <a class="navbar-item is-tab{{Request::segment(3) === 'judges' ? ' is-active' : ''}}" href="{{url('/common/messages/judges')}}">
    <icon class="icon"><i class="fa fa-graduation-cap"></i> </icon><span>Judges</span>
  </a>

  <a class="navbar-item is-tab{{Request::segment(3) === 'admins' ? ' is-active' : ''}}" href="{{url('/common/messages/admins')}}">
    <icon class="icon"><i class="fa fa-users"></i></icon><span>InnoSTARS Team</span>
  </a>

  <a class="navbar-item is-tab{{!Request::segment(3) ? ' is-active' : ''}}" href="{{url('/common/messages')}}">
    <icon class="icon"><i class="fa fa-th-list"></i></icon><span>History</span>
  </a>
</div>