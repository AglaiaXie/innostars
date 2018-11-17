@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
  <br>
  <div class="columns">
    <div class="column is-half is-offset-one-quarter">
      <div class="card ">
        <header class="card-header">
          <p class="card-header-title">
            Login
          </p>
        </header>
        <div class="card-content">
          <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}

            <div class="content">
              <div class="field">
                  <label class="label">Email</label>
                <div class="control">
                  <input name="email" class="input{{ $errors->has('email') ? ' is-danger' : '' }}" type="email"
                         value="{{ old('email') }}" required autofocus>
                </div>
              </div>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'email', 'type' => 'horizontal'])

              <div class="field">
                  <label class="label">Password</label>
                <div class="control">
                  <input name="password" class="input{{ $errors->has('password') ? ' is-danger' : '' }}" type="password"
                         required>
                </div>
              </div>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'password', 'type' => 'horizontal'])

              <div class="field">
                <label class="label">
                  <!--spacer-->
                </label>
                <div class="control">
                  <label class="checkbox">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                  </label>
                </div>
              </div>

              <div class="field">
                <label class="label">
                  <!--spacer-->
                </label>
                <div class="control">
                  <div class="control is-grouped">
                    <p class="control">
                      <button class="button is-primary">Submit</button>
                    </p>

                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="columns">
            <div class="column is-12 is-expanded has-text-right">
              Don't have an account yet? Click <a href="{{ url('/register') }}">here</a> to register
            </div>
          </div>
          <div class="columns">
            <div class="column is-12 is-expanded has-text-right">
              <a href="{{ url('/password/reset') }}">
                Forgot password
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
