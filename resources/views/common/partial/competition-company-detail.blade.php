<h4 class="title is-4">{{$competition->name}}</h4>
<p>From: {{$competition->date_start->format('d-m-Y')}} to: {{$competition->date_end->format('d-m-Y')}}</p>
<a href="{{ url($prefix . $competition->id) }}">
  <i class="fa fa-backward"></i> Back to Companies List
</a>
<hr>
@include('common.partial.company-detail', ['company' => $company->company])
<h3 class="title is-3">Comments</h3>
@foreach($company->scores as $score)
  <article class="media">
    <figure class="media-left">
      <p class="image is-64x64">
        <img src="https://bulma.io/images/placeholders/128x128.png">
      </p>
    </figure>
    <div class="media-content">
      <div class="content">
        <p>
          <span class="tag is-rounded is-primary is-medium">{{ $score->score }}</span>
          <strong>{{ $score->judge->user->first_name }} {{ $score->judge->user->last_name }}</strong>
          <small>{{ $score->judge->position }} @ {{ $score->judge->company_name }}</small>
          <small>{{ $score->created_at }}</small>
          <br>
          {{ $score->comment }}
        </p>
      </div>
    </div>
    <div class="media-right">
      <button class="delete"></button>
    </div>
  </article>
@endforeach