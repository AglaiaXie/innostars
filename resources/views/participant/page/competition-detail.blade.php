@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
  <div class="hero is-info">
    @include('participant.partial.hero')
  </div>
@endsection

@section('content')
  <section class="section">
    <h4 class="title is-4">{{$competition->name}}</h4>
    <p>From: {{$competition->date_start->format('d-m-Y')}} to: {{$competition->date_end->format('d-m-Y')}}</p>
    <hr>
    <h4 class="title is-4">参赛公司 Companies</h4>
    <table class="table is-fullwidth">
      <thead>
      <tr>
        <th>Company</th>
        <th>Size</th>
        <th>Location</th>
        <th>Average Score</th>
        <th>Progress</th>
      </tr>
      </thead>
      <tbody>
      @foreach($competition->companies()->get()->sortByDesc(function ($company) { return $company->score_average;}) as $company)
        <tr>
          <th>
            <a href="{{ url('participant/competitions/' . $competition->id . '/companies/' . $company->id) }}">
              {{$company->company->name}}
            </a>
          </th>
          <td>{{$company->company->size}}</td>
          <td>{{$company->company->city}}, {{$company->company->state}}</td>
          <td>{{$company->score_average}}</td>
          <td>{{$company->score_progress}}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </section>
@endsection