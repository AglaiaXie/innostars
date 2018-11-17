@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
  <div class="hero is-info">
    @include('participant.partial.hero')
  </div>
@endsection

@section('content')
  <section>
    @include('participant.partial.application')
  </section>
  <section class="section">
    <div class="columns">
      <div class="column is-full">
        <p class="title is-3 is-spaced">Application Registration Form</p>
      </div>
    </div>
    <hr>
    <div class="columns">
      <div class="column is-full">
        <form class="control" role="form" method="POST" action="{{ url('/participant/profile') }}">
          {{ csrf_field() }}
          <h4 class="title is-4">
            Company Information
          </h4>
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
              <label class="label">Year Established</label>
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="year" class="input{{ $errors->has('year') ? ' is-danger' : '' }}"
                         type="text"
                         value="{{ old('size') }}" required>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'year'])
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Type</label>
            </div>
            <div class="field-body">
              <div class="field">
                <div class="select">
                  <select name="type">
                    <option value="Public Company">Public Company</option>
                    <option value="Corporate Enterprise/Group">Corporate Enterprise/Group</option>
                    <option value="Limited Liability Company/Partnership">Limited Liability Company/Partnership</option>
                    <option value="University/College">University/College </option>
                    <option value="Academic Associations">Academic Associations</option>
                    <option value="Independent Research Organization">Independent Research Organization</option>
                    <option value="Government Department">Government Department</option>
                    <option value="Non-profit">Non-profit</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Website</label>
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="website" class="input{{ $errors->has('website') ? ' is-danger' : '' }}"
                         type="text"
                         value="{{ old('size') }}" required>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'website'])
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

          <hr>

          <h4 class="title is-4">
            Contact Information
          </h4>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Contact Person</label>
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="contact_person" class="input{{ $errors->has('contact_person') ? ' is-danger' : '' }}"
                         type="text"
                         value="{{ old('contact_person') }}" required>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_person'])
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Title</label>
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="contact_person_title" class="input{{ $errors->has('contact_person_title') ? ' is-danger' : '' }}"
                         type="text"
                         value="{{ old('contact_person_title') }}" required>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_person_title'])
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Phone</label>
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="contact_person_phone" class="input{{ $errors->has('contact_person_phone') ? ' is-danger' : '' }}"
                         type="text"
                         value="{{ old('contact_person_phone') }}" required>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_person_phone'])
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Email</label>
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input name="contact_person_email" class="input{{ $errors->has('contact_person_email') ? ' is-danger' : '' }}"
                         type="text"
                         value="{{ old('contact_person_email') }}" required>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'contact_person_email'])
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label is-normal">
            </div>
            <div class="field-body">
              <div class="field">
                <p class="control">
                  <button class="button is-primary">Submit</button>
                </p>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection