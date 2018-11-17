@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
	<section class="section">
		<nav class="breadcrumb" aria-label="breadcrumbs">
			<ul>
				<li><a href="#">Manage</a></li>
				<li><a href="{{ url('/admin/competitions') }}">Competition</a></li>
				<li><a href="{{ url('/admin/competitions/' . $competition->getKey()) }}" aria-current="page">
						{{$competition->name}} - {{$competition->city}}</a>
				</li>
				<li class="is-active"><a href="#" aria-current="page">{{ $company->company->name }}</a></li>
			</ul>
		</nav>
	</section>
	<div class="container">
		<div class="columns">
			<div class="column is-12">
				<h3 class="title is-3">Assigned Judges</h3>
				<table class="table is-fullwidth" id="scores-table">
					<thead>
					<tr>
						<th>Name</th>
						<th>Industries</th>
						<th>Total</th>
						<th>Due</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					@foreach($company->scores as $score)
						<tr id="score-{{ $score->getKey() }}">
							<th>{{ $score->judge->judge->user->first_name }} {{ $score->judge->judge->user->last_name }}</th>
							<td>
								@foreach($score->judge->judge->judging_industries as $industry)
									<span
										class="tag is-{{ $company->company->industry->getKey() === $industry->getKey() ? 'success' : 'info' }}"
										title="{{ $industry->name }}">{{ $industry->abbr }}</span>
								@endforeach
							</td>
							<td>{{ $score->total_score }}</td>
							<td>{{ $score->submit ? ' - ' : $score->due }}</td>
							<td>
								<a href=""><span class="icon has-text-{{$score->submit ? 'success' : 'warning'}}"><i class="fa fa-thumbs-up"></i></span></a>
							</td>
							<td>
								<a class="level-item" onclick="openDeleteModal({{$score->id}})" title="Delete">
									<span class="icon is-small has-text-danger"><i class="fa fa-remove"></i> </span>
								</a>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
		<div class="columns">
			<div class="column is-12">
				<h3 class="title is-3">Assign New Judge(s)</h3>
				<form method="POST" action="{{ url('/admin/competitions/' . $competition->getKey() . '/companies/' . $company->getKey())  }}">
					{{ method_field('PUT') }}
					{{ csrf_field() }}
					<table class="table is-fullwidth">
						<thead>
						<tr>
							<th></th>
							<th>Name</th>
							<th>Title</th>
							<th>Industries</th>
							<th>Scoring</th>
							<th>Scored</th>
						</tr>
						</thead>
						<tbody>
						@foreach($competition->judges as $judge)
							@if(!$company->judges->contains($judge))
							<tr>
								<td><input type="checkbox" name="judge_id[]" value="{{ $judge->getKey() }}"/></td>
								<th>{{ $judge->judge->user->first_name }} {{ $judge->judge->user->last_name }}</th>
								<th>{{ $judge->judge->title }}</th>
								<td>
									@foreach($judge->judge->judging_industries as $industry)
										<span
											class="tag is-{{ $company->company->industry->getKey() === $industry->getKey() ? 'success' : 'info' }}"
											title="{{ $industry->name }}">{{ $industry->abbr }}</span>
									@endforeach
								<td>{{$judge->scoring}}</td>
								<td>{{$judge->scored}}</td>
							</tr>
							@endif
						@endforeach
						</tbody>
					</table>
					<button class="button is-primary is-pulled-right">Assign Selected Judge(s)</button>
				</form>
			</div>
		</div>
	</div>
	@include('common.partial.delete-confirm')
	<script>

      function openDeleteModal(id) {
          $('#deleteConfirmModal .left-button').data('id', id);
          $("#deleteConfirmModal").addClass('is-active');
      }

      function closeDeleteModal() {
          $("#deleteConfirmModal").removeClass('is-active');
      }

      function doDelete(button) {
          var btn = $(button);
          btn.addClass("is-loading");

          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });

          $.ajax({
              url: '{{ url('/admin/scores') }}/' + btn.data('id'),
              type: 'DELETE',
              complete: function () {
                  closeDeleteModal();
                  $('#scores-table #score-' + btn.data('id')).remove();
                  btn.removeClass("is-loading");
              }
          });
          return false;
      }
	</script>
@endsection
