<div class="navbar-tabs">
  <a class="navbar-item is-tab{{Request::segment(3) === 'information' ? ' is-active' : ''}}" href="information">
    <span class="tag is-rounded is-info">1</span>&nbsp; Investor Information
    @if($user->investor_profile->submit)
      <span class="icon"><i class="fa fa-edit"></i></span>
    @endif
  </a>
  @if($user->investor_profile->current_step >= 2)
    <a class="navbar-item is-tab{{Request::segment(3) === 'preference' ? ' is-active' : ''}}" href="preference">
      <span class="tag is-rounded is-info">2</span>&nbsp; Investor Preference
    </a>
  @endif
  @if($user->investor_profile->current_step >= 3)
    <a class="navbar-item is-tab{{Request::segment(3) === 'file' ? ' is-active' : ''}}" href="file">
      <span class="tag is-rounded is-info">3</span>&nbsp; File Upload
      @if($user->investor_profile->submit)
        <span class="icon"><i class="fa fa-edit"></i></span>
      @endif
    </a>
  @endif
  @if($user->investor_profile->current_step >= 4)
    <a class="navbar-item is-tab{{Request::segment(3) === 'submit' ? ' is-active' : ''}}" href="submit">
      <span class="tag is-rounded is-info">4</span>&nbsp; Review and Submit
    </a>
  @endif
</div>
