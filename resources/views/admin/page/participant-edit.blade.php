@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
  <section class="section">
    <nav class="breadcrumb" aria-label="breadcrumbs">
      <ul>
        <li><a href="#">Manage</a></li>
        <li><a href="{{ url('/admin/participants') }}">Contestant</a></li>
        <li class="is-active"><a href="#" aria-current="page">{{$participant->first_name}} {{$participant->last_name}}</a></li>
      </ul>
    </nav>
    <div class="columns">
      <div class="column is-10 is-offset-1">
        @role(['admin','competition_admin','sxsw_admin'])
        <h4 class="title is-4">Account Information</h4>
        <hr>
        @include('participant.partial.profile.show.account')

	<br>
        @endrole
        <h4 class="title is-4">Company Information</h4>
        <hr>
        @include('participant.partial.profile.show.company')

        <br>
        <h4 class="title is-4">Contact Information</h4>
        <hr>
        @include('participant.partial.profile.show.contact')

        <br>
        <h4 class="title is-4">Project Information</h4>
        <hr>
        @include('participant.partial.profile.show.project')

        <br>
        <h4 class="title is-4">Additional Information</h4>
        <hr>
        @include('participant.partial.profile.show.addition')

        <br>
        <h4 class="title is-4">Documents</h4>
        <hr>
        @include('participant.partial.profile.show.file')

      </div>
    </div>
  </section>
@endsection
