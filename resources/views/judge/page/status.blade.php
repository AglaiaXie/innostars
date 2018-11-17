@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
  <div class="hero is-info">
    @include('judge.partial.hero')
  </div>
@endsection

@section('content')
    <div class="columns">
      <div class="column is-10 is-offset-1">
        <ul class="steps is-horizontal has-content-centered has-gaps">
          <li class="steps-segment{{ object_get($judge, 'judge_profile.submit') ? '' : ' is-hollow' }}">
            <span href="#" class="steps-marker{{ object_get($judge, 'judge_profile.approval') ? '' : ' is-hollow' }}">1</span>
            <div class="steps-content column">
              <div class="card">
                <header class="card-header">
                  <p class="card-header-title">
                    Judge Registration
                  </p>
                </header>
                <div class="card-content">
                  <div class="content">
                    @if(object_get($judge, 'judge_profile.approval'))
                      <p>Registration Approved</p>
                    @else
                      @if(object_get($judge, 'judge_profile.submit'))
                        <p>Application Submitted</p>
                      @else
                        <p>Please complete & submit your application and wait for admin approval.</p>
                      @endif
                    @endif
                  </div>
                </div>
                <footer class="card-footer">
                  @if(!object_get($judge, 'judge_profile.approval') && !object_get($judge, 'judge_profile.submit'))
                    <a href="{{url('/judge/profile/information')}}" class="card-footer-item">Application Form</a>
                  @endif
                </footer>
              </div>
            </div>
          </li>
          <li class="steps-segment is-active">
            <span href="#" class="steps-marker{{ object_get($judge, 'judge_profile.submit') ? '' : ' is-hollow' }}">2</span>
            <div class="steps-content column">
              <div class="card">
                <header class="card-header">
                  <p class="card-header-title">
                    Preliminary Stage
                  </p>
                </header>
                <div class="card-content">
                  <div class="content">
                    Pitch (Presentation + Q&A) in the U.S. cities
                  </div>
                </div>
                <footer class="card-footer">
	                @if(object_get($judge, 'judge_profile.submit'))
                    @foreach($judge->judge_profile->preliminary_competitions as $competition)
                      <a href="{{url('/judge/competitions')}}" class="card-footer-item">
                        {{ $competition->competition->name }} - {{ $competition->competition->city }}
                      </a>
                    @endforeach
                  @endif
                </footer>
              </div>
            </div>
          </li>
          <li class="steps-segment is-active">
            <span href="#" class="steps-marker is-hollow">3</span>
            <div class="steps-content column">
              <div class="card">
                <header class="card-header">
                  <p class="card-header-title">
                    Semifinal
                  </p>
                </header>
                <div class="card-content">
                  <div class="content">
                    Pitch and roadshows in multiple cities in China
                  </div>
                </div>
                <footer class="card-footer">
                  @if(object_get($judge, 'judge_profile.submit'))
                    @foreach($judge->judge_profile->semifinal_competitions as $competition)
                      <a href="{{url('/judge/competitions')}}" class="card-footer-item">
                        {{ $competition->competition->name }} - {{ $competition->competition->city }}
                      </a>
                    @endforeach
                  @endif
                </footer>
              </div>
            </div>
          </li>
          <li class="steps-segment is-active">
            <span href="#" class="steps-marker is-hollow">4</span>
            <div class="steps-content column">
              <div class="card">
                <header class="card-header">
                  <p class="card-header-title">
                    Grand Final
                  </p>
                </header>
                <div class="card-content">
                  <div class="content">
                  </div>
                </div>
                <footer class="card-footer">
                  @if(object_get($judge, 'judge_profile.submit'))
                    @foreach($judge->judge_profile->final_competitions as $competition)
                      <a href="{{url('/judge/competitions')}}" class="card-footer-item">
                        {{ $competition->competition->name }} - {{ $competition->competition->city }}
                      </a>
                    @endforeach
                  @endif
                </footer>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
@endsection