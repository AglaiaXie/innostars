@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
  <div class="hero is-info">
    @include('judge.partial.hero')
  </div>
@endsection

@section('content')
    <table class="table is-fullwidth">
      <tbody>
      @foreach($competitions as $competition)
        <tr>
          <th>
            {{$competition->competition->city}} - {{$competition->competition->name}}
          </th>
        </tr>
				<tr>
          <td>
            <table class="table is-fullwidth">
              <thead>
              <tr>
                <th>Company</th>
                <th>Size</th>
                <th>Established</th>
                <th>Industry of Focus</th>
                <th>Your Score</th>
                <th>
                  <span>Due</span>
                  <span class="icon has-text-info">
                    <i class="fa fa-info-circle" title="The days you have to review the project and give it a score"></i>
                  </span>
                </th>
                <th>Submitted?</th>
              </tr>
              </thead>
              <tbody>
              @foreach($competition->scores->sortBy('judge_order') as $score)
                @if ($score->judge->judge->is($judge->judge_profile))
                  @if (!$score->submit && $score->is_due)
                    @continue
                  @endif
                <tr>
                  <th><a href="{{ url('/judge/competitions/' . $competition->competition->getKey() . '/companies/' . $score->company->getKey()) }}">{{$score->company->company->name}}</a></th>
                  <td>{{$score->company->company->size}}</td>
                  <td>{{$score->company->company->established}}</td>
                  <td>
                    @if($industry = object_get($score, 'company.company.industry.name'))
                      <span class="tag is-info">{{ $industry }}</span>
                    @endif
                  </td>
                  <td>{{$score->total_score}}</td>
                  <td>{{ $score->submit ? ' - ' : $score->due }}</td>
                  <td>
                    <span class="icon has-text-{{$score->submit ? 'success' : 'warning'}}" title="Submit Status"><i class="fa fa-save"></i></span>
                  </td>
                </tr>
                @else
                  @continue
                @endif
              @endforeach

              </tbody>
            </table>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
@endsection
