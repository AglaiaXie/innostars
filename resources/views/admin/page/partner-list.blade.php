@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
  <section class="hero is-dark">
    <div class="hero-body">
      <div class="container">
        <h2 class="title">Partners</h2>
      </div>
    </div>
  </section>
@endsection

@section('content')
   <partner-admin :permission="permission"></partner-admin>
@endsection

@section('scripts')
<script>
  new Vue({
      el: '#app',
      components: {PartnerAdmin},
      data: {
          permission: {
              role: '{{ Auth::user()->roles()->first()->name }}',
              update: {{ Auth::user()->can('update-partner') ? 'true' : 'false' }},
              delete: {{ Auth::user()->can('delete-partner') ? 'true' : 'false' }},
              login: {{ Auth::user()->can('login-partner') ? 'true' : 'false' }},
              private: {{ Auth::user()->can('show-private') ? 'true' : 'false' }},
              export: {{ Auth::user()->can('export-participant') ? 'true' : 'false' }}
          }
      }
  });
</script>
@endsection