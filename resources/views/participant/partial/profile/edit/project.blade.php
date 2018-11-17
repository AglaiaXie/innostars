<form class="control" role="form" method="POST" action="{{ url('/participant/profile/project') }}">
  {{ csrf_field() }}

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Preliminary Stage Location <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="select">
          <select name="competition_id" id="competition_id">
            @foreach($competitions as $competition)
              <option value="{{$competition->id}}"{{($competition->id == old('competition')) ?: ($participant->company->joined_competitions()->isPreliminary()->exists() && $participant->company->joined_competitions()->isPreliminary()->first()->competition->getKey() == $competition->id ) ? ' selected':''}}>{{ $competition->city }}
                - {{ $competition->date ? $competition->date->format('m/d/Y') : 'TBD' }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Industries of Focus <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="select">
          @foreach($competitions as $competition)
            <select class="industry_select" name="industry_id_for_{{ $competition->id }}" id="industry_id_for_{{ $competition->id }}" style="display:none;">
              @foreach($competition->industries as $industry)
                <option
                  value="{{$industry->id}}"
                {{ object_get($participant, 'company.industry_id') === $industry->id ? 'selected' : ''}}>{{$industry->name}}</option>
              @endforeach
            </select>
          @endforeach
        </div>
        <p class="help">I would like to compete in the selected area</p>
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Sub Industry <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="select">
          @foreach(\App\Models\Industry::all() as $industry)
            <select class="sub_industry_select" name="sub_industry_of_{{$industry->id}}" id="sub_industry_of_{{$industry->id}}" style="display:none;">
              @foreach($industry->sub_industries as $sub_industry)
                <option
                  value="{{ $sub_industry->id }}"
                  {{ object_get($participant, 'company.sub_industry_id') === $sub_industry->id ? 'selected' : ''}}> {{$sub_industry->name}}</option>
              @endforeach
            </select>
          @endforeach
        </div>
        <p class="help">I would like to compete in the selected sub area</p>
      </div>
    </div>
  </div>

  <h4 class="title is-4">
    Technology/Project Information
  </h4>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Technology/Project Name <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="project_name"
                 class="input{{ $errors->has('project_name') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('project_name') ?: object_get($participant, 'company.project_name') }}" required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'project_name'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Technology/Project Description <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <textarea name="project_description"
                    class="textarea input{{ $errors->has('project_description') ? ' is-danger' : '' }}"
                    rows="3"
                    required>{{ old('project_description') ?: object_get($participant, 'company.project_description') }}</textarea>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'project_description'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Technology/Project Financing Stage <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="project_stage"
                 class="input{{ $errors->has('project_stage') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('project_stage') ?: object_get($participant, 'company.project_stage') }}" required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'project_stage'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Patent(s) <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <input name="patents"
                 class="input{{ $errors->has('patents') ? ' is-danger' : '' }}"
                 type="text"
                 value="{{ old('patents') ?: object_get($participant, 'company.patents') }}" required>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'patents'])
        <p class="help">Does your company have patents and what is the status?</p>
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Preferred Way of Cooperation <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          @foreach($cooperations as $cooperation)
            <label class="label">
              <input type="checkbox"
                     name="cooperation[]"
                     {{in_array($cooperation, $cooperations_selected) ? 'checked' : ''}}
                     value="{{ $cooperation }}"/>
              {{ $cooperation }}
            </label>
          @endforeach
            <label class="label">
              <input type="checkbox"
                     name="cooperation_alt_checked"
                     id="cooperation_alt_checked"
                     {{ old('cooperation_alt_checked') || object_get($participant, 'company.cooperation_alt') ? 'checked' : ''}}/>
              Other
            </label>
          <input class="input is-4" type="text" name="cooperation_alt" id="cooperation_alt"
                 value="{{ old('cooperation_alt') ?: object_get($participant, 'company.cooperation_alt') }}"
                 placeholder="Other way of cooperation">
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <a class="button is-info" href="{{ url('/participant/profile/contact') }}" onclick="return checkSave(this)">
          <span class="icon is-small"><i class="fa fa-arrow-left"></i></span><span>Previous Step</span>
        </a>
        <button class="button is-primary">
          <span class="icon is-small"><i class="fa fa-save"></i></span><span>Save & Next Step</span>
        </button>
      </div>
    </div>
  </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (!$('#cooperation_alt_checked').is(':checked')) {
            $('#cooperation_alt').hide();
        }

        $('#cooperation_alt_checked').change(function (event) {
            if (this.checked) {
                $('#cooperation_alt').slideDown();
            } else {
                $('#cooperation_alt').slideUp();
            }
        });

        function showIndustries() {
            var selector = $('#competition_id');
            var id = selector.val();
            $('.industry_select').hide();
            $('#industry_id_for_' + id).show();
        }

        showIndustries();

        function showSubIndustries() {
            var competitionSelector = $('#competition_id');
            var competitionId = competitionSelector.val();
            var selector = $('#industry_id_for_' + competitionId);
            var id = selector.val();
            $('.sub_industry_select').hide();
            $('#sub_industry_of_' + id).show();
        }

        showSubIndustries();

        $('#competition_id').change(function () {
            showIndustries();
            showSubIndustries();
        });

        $('.industry_select').change(showSubIndustries);
    });
</script>