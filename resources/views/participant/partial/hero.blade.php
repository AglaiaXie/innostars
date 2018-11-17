<div class="hero-body">
  <div class="container">
    <h1 class="title">
    </h1>
    <h2 class="subtitle">
      Hello {{ $participant->first_name }} {{ $participant->last_name}}
    </h2>
  </div>
</div>
<div class="hero-foot">
  <div class="container">
    @include('participant.partial.nav')
  </div>
</div>