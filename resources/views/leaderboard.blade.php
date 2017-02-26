@extends('app')

@section('app')
<div id="app">
	<section class="hero">
		<div class="hero-body has-text-centered">
			<div class="container">
			<h1 class="title is-3">TFL Name Game Leaderboard</h1>
			</div>
		</div>
	</section>

	<section>
		<div class="container has-text-centered">
			<table class="table">
				<thead>
					<tr>
						<th>Name</th>
						<th>Score</th>
						<th>Time Taken</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($games as $game)
						<tr>
							<td>{{ $game->player }}</td>
							<td>{{ $game->result['score'] }}</td>
							<td>{{ $game->result['time_taken'] }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</section>
</div>
@endsection
