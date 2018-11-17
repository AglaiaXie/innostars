<div class="hero-body">
  <div class="container">
    <h1 class="title">
      Hello {{ $judge->first_name }} {{ $judge->last_name}}
    </h1>
  </div>
</div>
<div class="hero-foot">
  <div class="container">
    @include('judge.partial.nav')
  </div>
</div>