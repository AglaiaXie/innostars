<div class="field is-horizontal">
    <div class="field-label">
        <label class="label is-normal">{{ $label }}</label>
    </div>
    <div class="field-body">
        <div class="field">
            @include('common.partial.download-file', ['file' => $file])
        </div>
    </div>
</div>