@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
  <section class="hero is-info">
    @include('judge.partial.hero')
  </section>
  <section>
    <div class="container">
      <h4 class="title is-4">Status</h4>
    </div>
  </section>
@endsection