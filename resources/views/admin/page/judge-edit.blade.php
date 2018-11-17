@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
  <section class="section">
    <nav class="breadcrumb" aria-label="breadcrumbs">
      <ul>
        <li><a href="#">Manage</a></li>
        <li><a href="{{ url('/admin/judges') }}">Judge</a></li>
        <li class="is-active"><a href="#" aria-current="page">{{$judge->first_name}} {{$judge->last_name}}</a></li>
      </ul>
    </nav>
    <div class="columns">
      <div class="column is-10 is-offset-1">
        @role(['admin','competition_admin','sxsw_admin']))
        <h4 class="title is-4">Account Information</h4>
        <hr>
        @include('judge.partial.profile.show.account')
        @endrole
        <h4 class="title is-4">Judge Information</h4>
        <hr>
        @include('judge.partial.profile.show.information')

        <br>
        <h4 class="title is-4">Judge Preference</h4>
        <hr>
        @include('judge.partial.profile.show.preference')

        <br>
        <h4 class="title is-4">Additional Information</h4>
        <hr>
        @include('judge.partial.profile.show.addition')

        <br>
        <h4 class="title is-4">Documents</h4>
        <hr>
        @include('judge.partial.profile.show.file')

      </div>
    </div>
  </section>
@endsection
