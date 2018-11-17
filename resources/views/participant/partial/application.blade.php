<div class="navbar-tabs">
  <a class="navbar-item is-tab{{Request::segment(3) === 'company' ? ' is-active' : ''}}" href="{{url('/participant/profile/company')}}">
    <span class="tag is-rounded is-info">1</span>&nbsp; Company Information
    @if($participant->company->submit)
      <span class="icon"><i class="fa fa-edit"></i></span>
    @endif
  </a>
  @if($participant->company->current_step >= 2)
    <a class="navbar-item is-tab{{Request::segment(3) === 'contact' ? ' is-active' : ''}}" href="{{url('/participant/profile/contact')}}">
      <span class="tag is-rounded is-info">2</span>&nbsp; Contact Information
      @if($participant->company->submit)
        <span class="icon"><i class="fa fa-edit"></i></span>
      @endif
    </a>
  @endif
  @if($participant->company->current_step >= 3)
    <a class="navbar-item is-tab{{Request::segment(3) === 'project' ? ' is-active' : ''}}" href="{{url('/participant/profile/project')}}">
      <span class="tag is-rounded is-info">3</span>&nbsp; Project Information
      @if(object_get($participant->company->joined_competitions()->competitionType(\App\Models\Competition::NAME_ONLINE)->first(), 'promoted'))
        <span class="icon"><i class="fa fa-edit"></i></span>
      @endif
    </a>
  @endif
  @if($participant->company->current_step >= 4)
    <a class="navbar-item is-tab{{Request::segment(3) === 'addition' ? ' is-active' : ''}}" href="{{url('/participant/profile/addition')}}">
      <span class="tag is-rounded is-info">4</span>&nbsp; Additional Information
      @if($participant->company->submit)
        <span class="icon"><i class="fa fa-edit"></i></span>
      @endif
    </a>
  @endif
  @if($participant->company->current_step >= 5)
    <a class="navbar-item is-tab{{Request::segment(3) === 'file' ? ' is-active' : ''}}" href="{{url('/participant/profile/file')}}">
      <span class="tag is-rounded is-info">5</span>&nbsp; File Upload
      @if(object_get($participant->company->joined_competitions()->competitionType(\App\Models\Competition::NAME_ONLINE)->first(), 'promoted'))
        <span class="icon"><i class="fa fa-edit"></i></span>
      @endif
    </a>
  @endif
  @if($participant->company->current_step >= 6)
    <a class="navbar-item is-tab{{Request::segment(3) === 'submit' ? ' is-active' : ''}}" href="{{url('/participant/profile/submit')}}">
      <span class="tag is-rounded is-info">6</span>&nbsp; Review and Submit
    </a>
  @endif
</div>