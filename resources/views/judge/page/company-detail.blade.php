@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
  <section class="hero is-info">
    @include('judge.partial.hero')
  </section>
  <section class="section">
    @include('common.partial.company-detail', [$company])
  </section>
@endsection