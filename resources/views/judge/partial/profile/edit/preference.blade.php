<form class="control" role="form" method="POST" action="{{ url('/judge/profile/preference') }}">
  {{ csrf_field() }}

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">What industry(s) would you like to judge? <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="control">
          @foreach($industries as $industry)
            <label class="label">
              <input type="checkbox"
                     name="judging_industries[]"
                     {{in_array($industry->id, $judging_industries_selected) ? 'checked' : ''}}
                     value="{{ $industry->id }}"/>
              {{ $industry->name }}
            </label>
            <ul id="sub_industry_of_{{$industry->id}}" style="margin-left: 20px">
              @foreach($industry->sub_industries as $sub_industry)
                <li>
                  <label class="checkbox">
                    <input
                      type="checkbox"
                      name="sub_industry_of_{{$industry->id}}[]"
                      value="{{ $sub_industry->id }}"
                     {{ $judge->judge_profile->judging_sub_industries && $judge->judge_profile->judging_sub_industries->contains($sub_industry) ? 'checked' : ''}}> {{$sub_industry->name}}
                  </label>
                </li>
              @endforeach
            </ul>
          @endforeach
        </div>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'judging_industries'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Which stage(s) of the Competition are you interested in judging in? <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          @foreach($competitions as $competition)
            <label class="label">
              <input type="checkbox"
                     name="judging_competitions[]"
                     {{in_array($competition->id, $competitions_selected) ? 'checked' : ''}}
                     value="{{ $competition->id }}"/>
              {{ $competition->name }} - {{ $competition->city }} {{ $competition->date ? $competition->date->format('m/d/Y') : 'TBD' }}
            </label>
            <ul id="sub_competition_of_{{$competition->id}}" style="margin-left: 20px">
              @foreach($competition->sub_competitions as $sub_competition)
                <li>
                  <label class="checkbox">
                    <input
                        type="checkbox"
                        name="sub_competition_of_{{$competition->id}}[]"
                        value="{{ $sub_competition->id }}"
                        {{ $judge->judge_profile->judging_sub_competitions && $judge->judge_profile->judging_sub_competitions->contains($sub_competition) ? 'checked' : ''}}>
                        {{$sub_competition->city}}  {{ $sub_competition->start ? $sub_competition->start->format('m/d/Y') : 'TBD' }}
                  </label>
                </li>
              @endforeach
            </ul>
        @endforeach
        <p class="help">Please select at least 1 competition</p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'judging_competitions'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Is your company looking for technologies in one of the areas? If yes, in which
        industry? <span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="control">
          @foreach($industries as $industry)
            <label class="label">
              <input type="checkbox"
                     name="interested_industries[]"
                     {{in_array($industry->id, $interested_industries_selected) ? 'checked' : ''}}
                     value="{{ $industry->id }}"/>
              {{ $industry->name }}
            </label>
              <ul id="interested_sub_industry_of_{{$industry->id}}" style="margin-left: 20px">
                @foreach($industry->sub_industries as $sub_industry)
                  <li>
                    <label class="checkbox">
                      <input
                        type="checkbox"
                        name="interested_sub_industry_of_{{$industry->id}}[]"
                        value="{{ $sub_industry->id }}"
                        {{ $judge->judge_profile->interested_sub_industries && $judge->judge_profile->interested_sub_industries->contains($sub_industry) ? 'checked' : ''}}> {{$sub_industry->name}}
                    </label>
                  </li>
                @endforeach
              </ul>
          @endforeach
            @include('laravel-bulma-starter::components.forms-errors', ['field' => 'interested_industries'])
        </div>
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <a class="button is-info" href="{{ url('/judge/profile/information') }}" onclick="return checkSave(this)">
            <span class="icon is-small"><i class="fa fa-arrow-left"></i></span><span>Previous Step</span>
          </a>
          <button class="button is-primary">
            <span class="icon is-small"><i class="fa fa-save"></i></span><span>Save & Next Step</span>
          </button>
        </p>
      </div>
    </div>
  </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        function showSubIndustries() {
            var industryCheckers = $('input[name="judging_industries\[\]"]');
            industryCheckers.each(function (index, industry) {
                industry = $(industry);
                var id = industry.val();
                industry.is(':checked') ? $('#sub_industry_of_' + id).show() : $('#sub_industry_of_' + id).hide();
            });
        }

        showSubIndustries();

        $('input[name="judging_industries\[\]"]').change(showSubIndustries);

        function showInterestedSubIndustries() {
            var industryCheckers = $('input[name="interested_industries\[\]"]');
            industryCheckers.each(function (index, industry) {
                industry = $(industry);
                var id = industry.val();
                industry.is(':checked') ? $('#interested_sub_industry_of_' + id).show() : $('#interested_sub_industry_of_' + id).hide();
            });
        }

        showInterestedSubIndustries();

        $('input[name="interested_industries\[\]"]').change(showInterestedSubIndustries);

        function showSubCompetitions() {
            var competitionCheckers = $('input[name="judging_competitions\[\]"]');
            competitionCheckers.each(function (index, competition) {
                competition = $(competition);
                var id = competition.val();
                competition.is(':checked') ? $('#sub_competition_of_' + id).show() : $('#sub_competition_of_' + id).hide();
            });
        }

        showSubCompetitions();

        $('input[name="judging_competitions\[\]"]').change(showSubCompetitions);

    });
</script>
