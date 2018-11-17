<div class="field is-horizontal">
  <div class="field-label">
    <label class="label">{{ $name }}</label>
  </div>
  <div class="field-body">
    <div class="field">
      @foreach($industries as $industry)
        <div class="column">
          <span class="tag is-info is-medium">{{ $industry->industry->abbr }}</span>:
          <span class="tag is-warning is-medium">{{$industry->name}}</span>
        </div>
      @endforeach
    </div>
  </div>
</div>
