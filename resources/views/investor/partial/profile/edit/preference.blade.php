<form class="control" role="form" method="POST">
  {{ csrf_field() }}

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Which stage(s) of the Competition are you participating in? 选择参与的比赛环节<span class="has-text-danger">*</span></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          @foreach($competitions as $competition)
            <label class="label">
              <input type="checkbox"
                     name="participating_competitions[]"
                     {{in_array($competition->id, $competitions_selected) ? 'checked' : ''}}
                     value="{{ $competition->id }}"/>
              {{ $competition->name }}{{ ' ' . $competition->name_cn }} - {{ $competition->city }}{{ ' ' . $competition->city_cn }} {{ $competition->date ? $competition->date->format('m/d/Y') : 'TBD' }}
            </label>
            <ul id="sub_competition_of_{{$competition->id}}" style="margin-left: 20px">
              @foreach($competition->sub_competitions as $sub_competition)
                <li>
                  <label class="checkbox">
                    <input
                        type="checkbox"
                        name="sub_competition_of_{{$competition->id}}[]"
                        value="{{ $sub_competition->id }}"
                        {{ $user->investor_profile->sub_competitions && $user->investor_profile->sub_competitions->contains($sub_competition) ? 'checked' : ''}}>
                    {{$sub_competition->city}}{{ ' ' . $sub_competition->city_cn }}  {{ $sub_competition->start ? $sub_competition->start->format('m/d/Y') : 'TBD' }}
                  </label>
                </li>
              @endforeach
            </ul>
            @endforeach
        <p class="help">Please select at least 1 competition</p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'participating_competitions'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">Is your company looking for technologies in one of the areas? If yes, in which
        industry? 您公司是否在寻找一下领域的技术？<span class="has-text-danger">*</span></label>
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
              {{ $industry->name }} {{ $industry->name_cn }}
            </label>
              <ul id="interested_sub_industry_of_{{$industry->id}}" style="margin-left: 20px">
                @foreach($industry->sub_industries as $sub_industry)
                  <li>
                    <label class="checkbox">
                      <input
                        type="checkbox"
                        name="interested_sub_industry_of_{{$industry->id}}[]"
                        value="{{ $sub_industry->id }}"
                        {{ $user->investor_profile->interested_sub_industries && $user->investor_profile->interested_sub_industries->contains($sub_industry) ? 'checked' : ''}}>
                      {{$sub_industry->name}} {{$sub_industry->name_cn}}
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
          <a class="button is-info" href="./information" onclick="return checkSave(this)">
            <span class="icon is-small"><i class="fa fa-arrow-left"></i></span><span>Previous Step 上一步</span>
          </a>
          <button class="button is-primary">
            <span class="icon is-small"><i class="fa fa-save"></i></span><span>Save & Next Step 下一步</span>
          </button>
        </p>
      </div>
    </div>
  </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        function showSubIndustries() {
            var industryCheckers = $('input[name="interested_industries\[\]"]');
            industryCheckers.each(function (index, industry) {
                industry = $(industry);
                var id = industry.val();
                industry.is(':checked') ? $('#sub_industry_of_' + id).show() : $('#sub_industry_of_' + id).hide();
            });
        }

        showSubIndustries();

        $('input[name="interested_industries\[\]"]').change(showSubIndustries);

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
            var competitionCheckers = $('input[name="participating_competitions\[\]"]');
            competitionCheckers.each(function (index, competition) {
                competition = $(competition);
                var id = competition.val();
                competition.is(':checked') ? $('#sub_competition_of_' + id).show() : $('#sub_competition_of_' + id).hide();
            });
        }

        showSubCompetitions();

        $('input[name="participating_competitions\[\]"]').change(showSubCompetitions);
    });
</script>
