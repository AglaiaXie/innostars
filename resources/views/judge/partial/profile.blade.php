<div class="navbar-tabs">
  <a class="navbar-item is-tab{{Request::segment(3) === 'information' ? ' is-active' : ''}}" href="{{url('/judge/profile/information')}}">
    <span class="tag is-rounded is-info">1</span>&nbsp; Judge Information 
    @if($judge->judge_profile->submit)
      <span class="icon"><i class="fa fa-edit"></i></span>
    @endif
  </a>
  @if($judge->judge_profile->current_step >= 2)
    <a class="navbar-item is-tab{{Request::segment(3) === 'preference' ? ' is-active' : ''}}" href="{{url('/judge/profile/preference')}}">
      <span class="tag is-rounded is-info">2</span>&nbsp; Judge Preference
    </a>
  @endif
  @if($judge->judge_profile->current_step >= 3)
  <a class="navbar-item is-tab{{Request::segment(3) === 'addition' ? ' is-active' : ''}}" href="{{url('/judge/profile/addition')}}">
    <span class="tag is-rounded is-info">3</span>&nbsp; Additional Information
    @if($judge->judge_profile->submit)
      <span class="icon"><i class="fa fa-edit"></i></span>
    @endif
  </a>
  @endif
  @if($judge->judge_profile->current_step >= 4)
    <a class="navbar-item is-tab{{Request::segment(3) === 'file' ? ' is-active' : ''}}" href="{{url('/judge/profile/file')}}">
      <span class="tag is-rounded is-info">4</span>&nbsp; File Upload
      @if($judge->judge_profile->submit)
        <span class="icon"><i class="fa fa-edit"></i></span>
      @endif
    </a>
  @endif
  @if($judge->judge_profile->current_step >= 5)
    <a class="navbar-item is-tab{{Request::segment(3) === 'submit' ? ' is-active' : ''}}" href="{{url('/judge/profile/submit')}}">
      <span class="tag is-rounded is-info">5</span>&nbsp; Review and Submit
    </a>
  @endif
</div>
