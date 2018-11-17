@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
  <section class="hero is-dark">
    <div class="hero-body">
      <div class="container">
        <h2 class="title">Investors</h2>
      </div>
    </div>
  </section>
@endsection

@section('content')
  <investor-admin :permission="permission"></investor-admin>
@endsection

@section('scripts')
<script>
  new Vue({
      el: '#app',
      components: {InvestorAdmin},
      data: {
          permission: {
              role: '{{ Auth::user()->roles()->first()->name }}',
              update: {{ Auth::user()->can('update-investor') ? 'true' : 'false' }},
              delete: {{ Auth::user()->can('delete-investor') ? 'true' : 'false' }},
              login: {{ Auth::user()->can('login-investor') ? 'true' : 'false' }},
              private: {{ Auth::user()->can('show-private') ? 'true' : 'false' }},
              export: {{ Auth::user()->can('export-participant') ? 'true' : 'false' }}
          }
      }
  });
</script>
@endsection
