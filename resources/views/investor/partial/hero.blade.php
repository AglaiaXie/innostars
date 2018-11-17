<div class="hero-body">
  <div class="container">
    <h1 class="title">
      Hello {{ $user->first_name }} {{ $user->last_name}}
    </h1>
  </div>
</div>
<div class="hero-foot">
  <div class="container">
    @include('investor.partial.nav')
  </div>
</div>