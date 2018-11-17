@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
  <div class="hero is-info">
    @include('participant.partial.hero')
  </div>
@endsection

@section('content')
  <section class="section">
    @include('common.partial.judge-detail', [$judge])
  </section>
@endsection