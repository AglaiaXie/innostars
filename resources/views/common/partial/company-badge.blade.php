<div class="card">
  <div class="card-content">
    <div class="media">
      @if($company->logo)
      <div class="media-left">
        <figure class="image is-128x128">
          <img src="{{ url('/file/' . $company->logo->disk_name) }}" alt="Company Logo">
        </figure>
      </div>
      @endif
      <div class="media-content">
        <p class="title is-5">
            {{ $company->name }}
        </p>
        <a href="http://{{ $company->website }}" target="_blank">{{ $company->website }}</a>
        <p class="subtitle is-6">{{ $company->city }}, {{ $company->state }}</p>
      </div>
    </div>

    <div class="content">
      <p>
        <strong>Industry</strong>: {{ $company->industry->name }}
      </p>
      <p>
        <strong>Project</strong>: {{ $company->project_name }}
      </p>
      <p>
        <strong>Contact</strong>: {{ $company->contact_name }} ({{ $company->contact_title }} )
      </p>
      <p>
      <a class="button is-info" onclick="openMessageModal({{$company->user->id}})">
        <span class="icon is-small"><i class="fa fa-comments"></i> </span> <span>Send Message</span>
      </a>
      </p>
    </div>
  </div>
</div>
