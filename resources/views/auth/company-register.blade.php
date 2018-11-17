@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
  <section class="section">
    <div class="columns">
      <div class="column is-full">
        <p class="title is-3 is-spaced">InnoSTARS Application Registration</p>
        <p class="subtitle is-5">Sign-up for the Competition. After registering, you will be asked to fill out the
          application form to participate in the Competition</p>
      </div>
    </div>
    <div class="columns">
      <div class="column is-full">
        <form class="control" role="form" method="POST" action="{{ url('/company-register') }}">
          {{ csrf_field() }}
          <h4 class="title is-4">
            Account Information
          </h4>
          <hr>
          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">E-mail</label>
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control has-icons-left">
                  <input name="email" class="input{{ $errors->has('email') ? ' is-danger' : '' }}" type="text"
                         value="{{ old('email') }}" required>
                  <span class="icon is-small is-left">
                  <i class="fa fa-envelope"></i>
                </span>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'email'])
              </div>
            </div>

          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Full Name</label>
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="first_name"
                         class="input{{ $errors->has('first_name') ? ' is-danger' : '' }}" type="text"
                         placeholder="First Name"
                         value="{{ old('first_name') }}" required autofocus>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'first_name'])
              </div>

              <div class="field">
                <p class="control">
                  <input name="last_name"
                         class="input{{ $errors->has('last_name') ? ' is-danger' : '' }}" type="text"
                         placeholder="Last Name"
                         value="{{ old('last_name') }}" required>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'last_name'])
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Password</label>
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="password"
                         class="input{{ $errors->has('password') ? ' is-danger' : '' }}" type="password"
                         value="{{ old('password') }}" required>
                </p>
                <p class="help">Enter Password</p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'password'])
              </div>

              <div class="field">
                <div class="control">
                  <input name="password_confirmation"
                         class="input{{ $errors->has('password-confirm') ? ' is-danger' : '' }}" type="password"
                         value="{{ old('password-confirm') }}" required>
                </div>
                <p class="help">Confirm Password</p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'password-confirm'])
              </div>
            </div>
          </div>
          <h4 class="title is-4">
            Company Information
          </h4>
          <hr>
          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Company Name</label>
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="name" class="input{{ $errors->has('name') ? ' is-danger' : '' }}"
                         type="text"
                         value="{{ old('name') }}" required>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'name'])
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Company Size</label>
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="size" class="input{{ $errors->has('size') ? ' is-danger' : '' }}"
                         type="text"
                         value="{{ old('size') }}" required>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'size'])
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Company Address</label>
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="address" class="input{{ $errors->has('address') ? ' is-danger' : '' }}" type="text"
                         value="{{ old('address') }}" required>
                </p>
                <p class="help">Address Line 1</p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'address'])
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="address2" class="input{{ $errors->has('address2') ? ' is-danger' : '' }}" type="text"
                         value="{{ old('address2') }}">
                </p>
                <p class="help">Address Line 2</p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'address2'])
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="city" class="input{{ $errors->has('city') ? ' is-danger' : '' }}" type="text"
                         value="{{ old('city') }}" required>
                </p>
                <p class="help">City</p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'city'])
              </div>
              <div class="field">
                <p class="control">
                  <input name="zip_code" class="input{{ $errors->has('zip_code') ? ' is-danger' : '' }}" type="text"
                         value="{{ old('zip_code') }}" required>
                </p>
                <p class="help">Zip Code</p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'zip_code'])
              </div>
              <div class="field">
                <div class="control">
                  <div class="select is-fullwidth">
                    <select name="state">
                      @foreach($states as $state)
                        <option value="{{ $state }}">{{ $state }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <p class="help">State</p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'state'])
              </div>
            </div>

          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <button class="button is-primary">Register</button>
                </p>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection