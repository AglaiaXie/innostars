<div class="field is-horizontal">
  <div class="field-label">
    <label class="label">{{ $name }}</label>
  </div>
  <div class="field-body">
    <div class="field">
      @foreach($items as $item)
        <div class="column">
          <span class="tag is-info is-medium">{{$item->competition->name}} - {{$item->competition->city}}</span>
        </div>
      @endforeach
    </div>
  </div>
</div>
