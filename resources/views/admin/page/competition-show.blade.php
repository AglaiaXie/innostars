@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
  <section class="section">
    <nav class="breadcrumb" aria-label="breadcrumbs">
      <ul>
        <li><a href="#">Manage</a></li>
        <li><a href="{{ url('/admin/competitions') }}">Competition</a></li>
        <li class="is-active"><a href="#" aria-current="page">{{$competition->name}} - {{$competition->city}}</a></li>
      </ul>
    </nav>
  </section>
  <div class="container">
    <div class="columns">
      <div class="column is-8">
        <h3 class="title is-3">Contestants</h3>
        <form method="get">
          <div class="field is-grouped is-grouped-right">
            <div class="control">
              <div class="select">
                <select name="industry">
                  <option value="">All Industries</option>
                  @foreach($industries as $industry)
                    <option
                      value="{{ $industry->getKey() }}"{{ Request::get('industry') == $industry->getKey() ? ' selected' : '' }}>
                      {{ $industry->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            @if($stageFilter)
              <div class="control">
                <div class="select">
                  <select name="city">
                    <option value="">All City</option>
                    @foreach($stageFilter as $stage)
                      <option
                        value="{{ $stage->getKey() }}"{{ Request::get('city') == $stage->getKey() ? ' selected' : '' }}>
                        {{ $stage->city }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            @endif
            <div class="control">
              <div class="select">
                <select name="sort">
                  <option value=""{{ empty(Request::get('sort')) ? ' selected' : '' }}>Sort By Avg
                  </option>
                  <option value="scores"{{ Request::get('sort') === 'scores' ? ' selected' : '' }}>Sort By Scores
                  </option>
                </select>
              </div>
            </div>
            <div class="control">
              <button class="button is-primary">Filter</button>
            </div>
            <div class="control">
              <a class="button" href="{{ url('admin/competitions/' . $competition->getKey()) }}">Reset</a>
            </div>
          </div>
        </form>
        <form method="POST" action="{{ url('/admin/competitions/' . $competition->getKey()) }}">
          {{ method_field('PUT') }}
          {{ csrf_field() }}
          <table class="table is-fullwidth">
            <thead>
            <tr>
              <th>#</th>
              <th>Company</th>
              <th>Area</th>
              <th>Scores</th>
              <th>Avg</th>
              @role('admin')
              <th>Action</th>
              @endrole
            </tr>
            </thead>
            <tbody>
            @foreach($companies->values() as $index => $company)
              <tr>
                <td>
                  {{ $index + 1 }}
                  @if(!$company->promoted)
                    <input type="checkbox" name="company_id[]" value="{{ $company->getKey() }}"/>
                  @endif
                </td>
                <th>
                  @if($company->promoted)
                    <span class="icon has-text-success"><i class="fa fa-trophy"></i> </span>
                  @endif
                  {{$company->company->name}}
                </th>
                <td>
                  @if($industry = object_get($company, 'company.industry.name'))
                    <span class="tag is-info">{{ $industry }}</span>
                  @endif
                </td>
                <td>{{$company->scored}}/{{$company->total_score}}</td>
                <td>{{$company->score_average}}</td>
                @role('admin')
                <td>
                  <a
                    href="{{ url('admin/competitions/' . $competition->getKey() . '/companies/'. $company->getKey()) . '/edit' }}">
                    <span class="icon"><i class="fa fa-wrench"></i></span></a>
                </td>
                @endrole
              </tr>
            @endforeach
            </tbody>
          </table>
          @if($promoteStages)
            @role('admin')
            <div class="field is-grouped">
            <div class="control">
                <button class="button is-primary">Promote contestant(s) to next stage</button>
            </div>
              <div class="control">
                @if($promoteStages->count())
                  <div class="select">
                    <select name="nextStage" id="">
                      @foreach($promoteStages as $stage)
                        <option value="{{$stage->id}}">{{$stage->name}}: {{$stage->city}}</option>
                      @endforeach
                    </select>
                  </div>
                @endif
              </div>
            </div>
            @endrole
          @endif
          <a href="{{ url('admin/competitions/'. $competition->getKey() . '/downloadParticipants') }}?{{ Request::getQueryString() }}" class="button is-info">Export to CSV</a>
        </form>
      </div>
      <div class="column is-4">
        <h3 class="title is-3">Judges</h3>
        <table class="table is-fullwidth">
          <thead>
          <tr>
            <th>Name</th>
            <th>Area</th>
            <th>Scoring</th>
            <th>Scored</th>
          </tr>
          </thead>
          <tbody>
          @foreach($competition->judges->sortByDesc('scored')->values() as $judge)
            <tr>
              <th>{{$judge->judge->user->first_name}} {{$judge->judge->user->last_name}}</th>
              <td>
                @foreach($judge->judge->judging_industries as $industry)
                  <span class="tag is-info">{{ $industry->abbr }}</span>
                @endforeach
              </td>
              <td>{{$judge->scoring}}</td>
              <td>{{$judge->scored}}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
        <a href="{{ url('admin/competitions/'. $competition->getKey() . '/downloadJudges') }}" class="button is-info">Export to CSV</a>
      </div>
    </div>
  </div>

@endsection
