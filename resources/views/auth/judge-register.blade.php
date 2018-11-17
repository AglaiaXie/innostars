@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
  <section class="section">
    <div class="columns">
      <div class="column is-full">
        <p class="title is-3 is-spaced">InnoSTARS Judge Registration</p>
        <p class="subtitle is-5">Thank you for your interest in becoming a judge. Please complete the application
          form</p>
      </div>
    </div>
    <div class="columns">
      <div class="column is-full">
        <form class="control" role="form" method="POST" enctype="multipart/form-data"
              action="{{ url('/judge-register') }}">
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
              </div>
            </div>
            @include('laravel-bulma-starter::components.forms-errors', ['field' => 'email'])
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
            Survey
          </h4>
          <hr>
          <div class="field">
            <div class="field-body">
              <div class="field">
                <label class="label">Company/Organization 公司/机构 :*</label>
                <div class="field">
                  <p class="control">
                    <input name="company_name" class="input{{ $errors->has('company_name') ? ' is-danger' : '' }}"
                           type="text"
                           value="{{ old('company_name') }}" required>
                  </p>
                  @include('laravel-bulma-starter::components.forms-errors', ['field' => 'company_name'])
                </div>
              </div>

              <div class="field">
                <label class="label">Position/职位:*</label>
                <div class="field">
                  <p class="control">
                    <input name="position" class="input{{ $errors->has('position') ? ' is-danger' : '' }}"
                           type="text"
                           value="{{ old('position') }}" required>
                  </p>
                  @include('laravel-bulma-starter::components.forms-errors', ['field' => 'position'])
                </div>
              </div>
            </div>
          </div>
          <div class="field">
            <div class="field-body">
              <div class="field">
                <label class="label">Phone/电话:*</label>
                <div class="field">
                  <p class="control">
                    <input name="phone" class="input{{ $errors->has('phone') ? ' is-danger' : '' }}"
                           type="text"
                           value="{{ old('phone') }}" required>
                  </p>
                  @include('laravel-bulma-starter::components.forms-errors', ['field' => 'phone'])
                </div>
              </div>

              <div class="field">
                <label class="label">Highest Degree Attained/最高学历:*</label>
                <div class="field">
                  <p class="control">
                    <input name="education" class="input{{ $errors->has('education') ? ' is-danger' : '' }}"
                           type="text"
                           value="{{ old('education') }}" required>
                  </p>
                  @include('laravel-bulma-starter::components.forms-errors', ['field' => 'education'])
                </div>
              </div>
            </div>
          </div>
          <div class="field">
            <label class="label">Relevant Experiences (Please provide CV) 相关经验(请附上简历) 包括投资、兼/并购经历；相关领域项目经历*</label>
            <div class="field">
              <p class="control">
              <div class="file has-name is-fullwidth">
                <label class="file-label">
                  <input class="file-input" type="file" name="cv">
                  <span class="file-cta">
                  <span class="file-icon">
                    <i class="fa fa-upload"></i>
                  </span>
                  <span class="file-label">
                    Choose a file…
                  </span>
                </span>
                  <span class="file-name">
                  Accepted file types: pdf, doc, docx.
                </span>
                </label>
              </div>
              </p>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'cv'])
            </div>
          </div>
          <div class="field">
            <label class="label">What industry(s) would you like to participate in? (Multiple Choices)
              您想参与哪个领域的评选？（多选）*</label>
            <div class="field">
              <p class="control">
                @foreach($industries as $industry)
                  <label class="checkbox">
                    <input type="checkbox"
                           name="industries[]"
                           {{ in_array($industry->id, old('industries', [])) ? ' checked' : '' }}
                           value="{{$industry->id}}">
                    {{$industry->name}}
                  </label>
                  <br>
                @endforeach
              </p>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'industries'])
            </div>
          </div>
          <div class="field">
            <label class="label">Which stage(s) of the Competition are you interested in? (Multiple Choices)
              您可以参加哪一轮的评审?（多选）*</label>
            <div class="field">
              <p class="control">
                @foreach($competitions as $competition)
                  <label class="checkbox">
                    <input type="checkbox"
                           name="competitions[]"
                           {{ in_array($competition->id, old('competitions', [])) ? ' checked' : '' }}
                           value="{{$competition->id}}">
                    {{$competition->name}}:{{$competition->area->name}}
                    {{$competition->date_start->format('d-m-Y')}}
                  </label>
                  <br>
                @endforeach
              </p>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'competitions'])
            </div>
          </div>
          <div class="field">
            <label class="label">Is your company looking for technologies in one of the areas? If yes, in which
              industry?
              您的公司正在寻找相关的先进技术么？如果是，在哪个领域？</label>
            <div class="field">
              <p class="control">
                @foreach($industries as $industry)
                  <label class="checkbox">
                    <input type="checkbox"
                           name="interested[]"
                           {{ in_array($industry->id, old('interested', [])) ? ' checked' : '' }}
                           value="{{$industry->id}}">
                    {{$industry->name}}
                  </label>
                  <br>
                @endforeach
              </p>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'interested'])
            </div>
          </div>
          <div class="field">
            <label class="label">How did you hear about the InnoSTARS Competition? If you heard about us through another
              organization or website, please list it here? 您是如何听说这个机会的？请列出该组织或者网站名称。*</label>
            <div class="field">
              <p class="control">
                <input name="refer" class="input{{ $errors->has('refer') ? ' is-danger' : '' }}"
                       type="text"
                       value="{{ old('refer') }}" required>
              </p>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'refer'])
            </div>
          </div>
          <div class="field">
            <label class="label">Have you been a judge for other competitions? If so, which one(s)?
              您有做其它大赛评委的经历吗？请标明大赛名称。</label>
            <div class="field">
              <p class="control">
                <input name="experience" class="input{{ $errors->has('control') ? ' is-danger' : '' }}"
                       type="text"
                       value="{{ old('experience') }}" required>
              </p>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'experience'])
            </div>
          </div>
          <hr>
          <div class="field">
            <div class="field">
              <p class="control">
                <button class="button is-primary">Register</button>
              </p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection