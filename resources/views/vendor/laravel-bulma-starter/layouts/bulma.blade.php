<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>innoSTARS</title>

  <!-- Styles -->
  @stack('styles-before')
  <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
  @stack('styles-after')

<!-- Scripts -->
  <script>
      window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
  </script>
  @if(!App::isLocal())
  <!-- Global site tag (gtag.js) - Google Analytics -->
    {{--<script async src="https://www.googletagmanager.com/gtag/js?id=UA-114297965-1"></script>--}}
    {{--<script>--}}
        {{--window.dataLayer = window.dataLayer || [];--}}
        {{--function gtag(){dataLayer.push(arguments);}--}}
        {{--gtag('js', new Date());--}}

        {{--gtag('config', 'UA-114297965-1');--}}
        {{--gtag('config', 'UA-114512328-6');--}}
    {{--</script>--}}
  @endif

</head>
<body>
<div id="app">
  <!-- navigation -->
  <nav class="navbar">
    <div class="navbar-brand">
      @if(App::isLocal())
        <h2 class="title is-2">TESTING</h2>
      @else
        <a class="navbar-item" href="http://uschinainnovation.org/innostars2018" title="InnoSTARS">
          <img src="{{ url('/images/innoSTARS-logo.png') }}">
        </a>
      @endif
      <div class="navbar-burger burger" data-target="navMenu">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
    <div id="navMenu" class="navbar-menu">
      <div class="navbar-start">
        @if(!Auth::guest())
          <div class="navbar-item has-dropdown is-hoverable">
            @permission('list-participant')
            <a class="navbar-item" href="/admin/participants">
              <span class="icon"><i class="fa fa-users"></i></span><span>Contestant参赛公司</span>
            </a>
            @endpermission
            @permission('list-participant')
            <a class="navbar-item" href="/admin/judges">
              <span class="icon"><i class="fa fa-check-circle"></i></span>
              <span>Judge评委</span>
            </a>
            @endpermission
            @permission('list-competition')
            <a class="navbar-item" href="/admin/competitions">
              <span class="icon"><i class="fa fa-trophy"></i></span>
              <span>Competitions比赛</span>
            </a>
            @endpermission
            @permission('list-investor')
            <a class="navbar-item" href="/admin/investors">
              <span class="icon"><i class="fa fa-line-chart"></i></span>
              <span>Investor投资人</span>
            </a>
            @endpermission
            @permission('list-partner')
            <a class="navbar-item" href="/admin/partners">
              <span class="icon"><i class="fa fa-handshake-o"></i></span>
              <span>Partner合伙人</span>
            </a>
            @endpermission
            <a class="navbar-item" href="/admin/events">
              <span class="icon"><i class="fa fa-globe"></i></span>
              <span>Events对接会议</span> &nbsp;
            </a>
            @permission('make-schedule')
            <a class="navbar-item" href="/admin/schedules">
              <span class="icon"><i class="fa fa-calendar"></i></span>
              <span>Schedules日程</span> &nbsp;
            </a>
            @endpermission
            @permission('list-schedule')
            <a class="navbar-item" href="/admin/schedules/list">
              <span class="icon"><i class="fa fa-calendar"></i></span>
              <span>Schedules日程</span> &nbsp;
            </a>
            @endpermission
            @permission('list-message')
            <a class="navbar-item" href="/admin/messages">
              <span class="icon"><i class="fa fa-comment-o"></i></span>
              <span>Messages信息</span> &nbsp;
              @if ($count = Auth::user()->newThreadsCount())
                <span class="tag is-rounded is-danger">{{ $count }}</span>
              @endif
            </a>
            @endpermission
          </div>
        @endif
      </div>

      <div class="navbar-end">
        @stack('right-navbar-menu')
        @if (Auth::guest())
          <a class="navbar-item" href="{{ url('/login') }}">
            <span class="icon"><i class="fa fa-sign-in"></i></span>
            <span>Login 登录</span>
          </a>
          <a class="navbar-item" href="{{ url('/register') }}">
            <span class="icon"><i class="fa fa-pencil"></i></span>
            <span>Register 注册</span>
          </a>
        @else
          <div class="navbar-item has-dropdown is-hoverable">
            <div class="navbar-item">
              <span class="icon"><i class="fa fa-user"></i></span><span>{{ ucfirst(Auth::user()->roles()->first()->display_name) }}</span>
              @permission('access-profile')
              <a href="/{{ Auth::user()->type }}/profile/information">
                <span> (Profile修改用户信息)</span>
              </a>
              @endpermission
            </div>
            <a class="navbar-item" href="{{ url('/logout') }}"
               onclick="event.preventDefault();
               document.getElementById('logout-form').submit();">
              <span class="icon"><i class="fa fa-sign-out"></i></span><span>Logout退出</span>
            </a>
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
            @endif
          </div>
      </div>
    </div>
  </nav>

  <!-- main content -->
  @yield('hero')
  <section class="section">
    <div class="container is-fluid">
      @yield('content')
    </div>
  </section>
  @yield('modal')
</div>

<footer class="footer">
  <div class="container">
    <div class="content has-text-centered">
      <p>
        © 2018 UCIA All Rights Reserved.
      </p>
    </div>
  </div>
</footer>

<!-- Scripts -->
@stack('scripts-before')
<script src="{{ mix('js/app.js') }}"></script>
@stack('scripts-after')
@yield('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // Get all "navbar-burger" elements
        const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

        // Check if there are any navbar burgers
        if ($navbarBurgers.length > 0) {

            // Add a click event on each of them
            $navbarBurgers.forEach( el => {
                el.addEventListener('click', () => {

                    // Get the target from the "data-target" attribute
                    const target = el.dataset.target;
                    const $target = document.getElementById(target);

                    // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
                    el.classList.toggle('is-active');
                    $target.classList.toggle('is-active');

                });
            });
        }

    });
</script>
</body>
</html>
