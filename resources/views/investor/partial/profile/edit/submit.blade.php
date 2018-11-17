<h3 class="title is-3"></h3>
<div class="columns">
  <div class="column is-10 is-offset-1">
    <h4 class="title is-4">Investor Information 投资人信息</h4>
    <hr>
    @include('investor.partial.profile.show.information')

    <br>
    <h4 class="title is-4">Industry Preference 活动选项</h4>
    <hr>
    @include('investor.partial.profile.show.preference')

    <br>
    <h4 class="title is-4">File Uploaded 文件上传</h4>
    <hr>
    @include('investor.partial.profile.show.file')

  </div>
</div>
<hr>
<form class="control" role="form" method="POST">
  {{ csrf_field() }}
  <h4 class="title is-4">
    Submit My Registration 提交注册:
    <small>Please click the following checkbox before submission. 请勾选以下选项</small>
  </h4>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label"></label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <label class="label">
            <input type="checkbox" name="confirm_1"{{ old('confirm_1') ? ' checked' :'' }}>
            Note: You can't update your industry preference once the application is submitted.
            备注：信息一旦提交，您将无法修改活动选项
          </label>
          <strong>I have reviewed my application above and all the information is correct. 我确认所有信息准确无误</strong>
        </p>
        @include('laravel-bulma-starter::components.forms-errors', ['field' => 'confirm_1'])
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <a class="button is-info" href="file">
            <span class="icon is-small"><i class="fa fa-arrow-left"></i></span><span>Previous Step 上一步</span>
          </a>
          <button class="button is-primary">
            <span class="icon is-small"><i class="fa fa-save"></i></span><span>Submit 提交</span>
          </button>
        </p>
      </div>
    </div>
  </div>
</form>
