@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
  <section class="hero is-dark">
    <div class="hero-body">
      <div class="container">
        <h2 class="title">Competitions</h2>
      </div>
    </div>
  </section>
@endsection

@section('content')
  <competition-admin :permission="permission"></competition-admin>
@endsection

@section('scripts')
  <script>
      new Vue({
          el: '#app',
          components: {CompetitionAdmin},
          data: {
              permission: {
                  role: '{{ Auth::user()->roles()->first()->name }}',
                  update: {{ Auth::user()->can('update-investor') ? 'true' : 'false' }},
                  delete: {{ Auth::user()->can('delete-investor') ? 'true' : 'false' }},
                  login: {{ Auth::user()->can('login-investor') ? 'true' : 'false' }},
                  private: {{ Auth::user()->can('show-private') ? 'true' : 'false' }}
              }
          }
      });
  </script>
@endsection
