@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
  <section class="hero is-info">
    @include('judge.partial.hero')
  </section>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column is-10 is-centered">
          <h4 class="title is-4">Status</h4>
        </div>
      </div>
    </div>
  </section>
@endsection