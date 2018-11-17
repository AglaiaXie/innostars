<form class="control" role="form" method="POST" action="{{ url('/participant/profile/addition') }}">
    {{ csrf_field() }}

    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label class="label">Team Introduction <span class="has-text-danger">*</span></label>
        </div>
        <div class="field-body">
            <div class="field">
                <p class="control">
                  <textarea name="team_description"
                            class="textarea{{ $errors->has('team_description') ? ' is-danger' : '' }}"
                            rows="3" maxlength="300"
                            required>{{ old('team_description') ?: object_get($participant, 'company.team_description') }}</textarea>
                </p>
                <p class="help">Please include the total number of team members, a brief introduction of each member's
                    name, experience, skills, and qualifications (300 characters maximum)</p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'team_description'])
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label class="label">Description of Technical Requirements (Optional)</label>
        </div>
        <div class="field-body">
            <div class="field">
                <p class="control">
                  <textarea name="tech_requirement"
                            class="textarea input{{ $errors->has('tech_requirement') ? ' is-danger' : '' }}"
                            rows="3" maxlength="300"
                  >{{ old('tech_requirement') ?: object_get($participant, 'company.tech_requirement') }}</textarea>
                </p>
                <p class="help">performance-related issues, reliability issues, and availability issues, etc (300
                    characters maximum)</p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'tech_requirement'])
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label class="label" for="tech_advantage">Description of the Competitive Advantages and Sustainability of
                the Technology/Project (Optional)</label>
        </div>
        <div class="field-body">
            <div class="field">
                <p class="control">
                  <textarea name="tech_advantage" id="tech_advantage"
                            class="textarea{{ $errors->has('tech_advantage') ? ' is-danger' : '' }}"
                            rows="3" maxlength="300"
                  >{{ old('tech_advantage') ?: object_get($participant, 'company.tech_advantage') }}</textarea>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'tech_advantage'])
                <p class="help">300 characters maximum</p>
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label class="label">Risk of Technology (Optional)</label>
        </div>
        <div class="field-body">
            <div class="field">
                <p class="control">
                  <textarea name="tech_risk"
                            class="textarea{{ $errors->has('tech_risk') ? ' is-danger' : '' }}"
                            rows="3" maxlength="300"
                  >{{ old('tech_risk') ?: object_get($participant, 'company.tech_risk') }}</textarea>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'tech_risk'])
                <p class="help">Please provide a proactive risk control or risk response plan (300 characters
                    maximum)</p>
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label class="label">Prospective Chinese Market Value/Business Value <span class="has-text-danger">*</span></label>
        </div>
        <div class="field-body">
            <div class="field">
                <p class="control">
                  <textarea name="business_value"
                            class="textarea{{ $errors->has('business_value') ? ' is-danger' : '' }}"
                            rows="3" maxlength="300"
                            required>{{ old('business_value') ?: object_get($participant, 'company.business_value') }}</textarea>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'business_value'])
                <p class="help">300 characters maximum</p>
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label class="label">How did you hear about us? <span class="has-text-danger">*</span></label>
        </div>
        <div class="field-body">
            <div class="field">
                <p class="control">
                  <textarea name="refer"
                            class="textarea{{ $errors->has('refer') ? ' is-danger' : '' }}"
                            rows="2" maxlength="300"
                            required>{{ old('refer') ?: object_get($participant, 'company.refer') }}</textarea>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'refer'])
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label class="label">Have you been to China before? <span class="has-text-danger">*</span></label>
        </div>
        <div class="field-body">
            <div class="field">
                <p class="control">
                    <input name="been_to_china" class="input{{ $errors->has('been_to_china') ? ' is-danger' : '' }}"
                           type="text"
                           value="{{ old('been_to_china') ?: object_get($participant, 'company.been_to_china') }}" required>
                </p>
                @include('laravel-bulma-starter::components.forms-errors', ['field' => 'been_to_china'])
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label is-normal">
        </div>
        <div class="field-body">
            <div class="field">
                <a class="button is-info" href="{{ url('/participant/profile/project') }}"
                   onclick="return checkSave(this)">
                    <span class="icon is-small"><i class="fa fa-arrow-left"></i></span><span>Previous Step</span>
                </a>
                <button class="button is-primary">
                    <span class="icon is-small"><i class="fa fa-save"></i></span><span>Save & Next Step</span>
                </button>
            </div>
        </div>
    </div>
</form>
