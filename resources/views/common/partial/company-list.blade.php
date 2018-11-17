<table class="table is-fullwidth">
  <thead>
  <tr>
    <th>Company</th>
    <th>Size</th>
    <th>Location</th>
    <th>Average Score</th>
    <th>Progress</th>
  </tr>
  </thead>
  <tbody>
  @foreach($companies as $company)
    <tr>
      <th>
        {{$company->company->name}}
      </th>
      <td>{{$company->company->size}}</td>
      <td>{{$company->company->city}}, {{$company->company->state}}</td>
      <td>{{$company->score_average}}</td>
      <td>{{$company->score_progress}}</td>
    </tr>
  @endforeach
  </tbody>
</table>
