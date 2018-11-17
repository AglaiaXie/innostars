@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
  <div class="hero is-info">
    @include('participant.partial.hero')
  </div>
@endsection

@section('content')
  <section class="section">
    <table class="table is-fullwidth">
      <thead>
      <tr>
        <th>Name</th>
        <th>Location</th>
        <th>Date</th>
        <th>Placed</th>
      </tr>
      </thead>
      <tbody>
      @foreach($participant->company->joined_competitions as $joined)
        <tr>
          <th>
            @if($joined->competition->in_session)
              <a href="{{ url('participant/competitions/' . $joined->competition->id) }}">
                {{$joined->competition->name}}
              </a>
            @else
              {{$joined->competition->name}}
            @endif
          </th>
          <td>{{$joined->competition->area->name}}</td>
          <td>{{$joined->competition->date_start->format('d-m-Y')}}</td>
          <td>{{$joined->approval ? 'Yes' : 'No'}}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </section>
@endsection