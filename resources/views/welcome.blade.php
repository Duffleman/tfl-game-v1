@extends('app')

@section('app')
<div id="app">
	<section class="hero">
		<div class="hero-body has-text-centered">
			<div class="container">
			<h1 class="title is-3">TFL Name Game</h1>
			<h2 class="subtitle">Guess the name of the TFL station</h2>
			</div>
		</div>
	</section>
	<section>
		<div id="app" class="container has-text-centered">
			<div v-show="alert" class="notification is-danger">
				<p>@{{ alertMessage }}</p>
			</div>
			<div v-show="preGame">
				<input type="text" placeholder="your name" autocomplete="false" v-model="player" class="input is-large" id="inputStation" v-on:keyup.enter="newGameState">

				<div class="columns">
					<div class="column">
						<div class="box" style="margin-top:2rem;">
							<h2 class="title is-4">Play with Lines</h2>
							<div class="columns">
								<div class="column" v-for="lines in chunkedLines">
									<ul>
										<li v-for="line in lines">
											<label>
												<input v-model="selectedLines" type="checkbox" v-bind:value="line.code" v-bind:class="line.mode">
												@{{ line.name }}
											</label>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>

					<div class="column">
						<div class="box" style="margin-top:2rem;">
							<h2 class="title is-4">Play with Zones</h2>
							<label v-for="zone in allZones">
								<input v-model="selectedZones" type="checkbox" v-bind:value="zone.label">
								@{{ zone.label }}
							</label>
							<div style="margin-top:1rem;">
								<button class="button" @click="setDifficulty('easy')">Easy</button>
								<button class="button" @click="setDifficulty('medium')">Medium</button>
								<button class="button" @click="setDifficulty('hard')">Hard</button>
							</div>
						</div>
					</div>
				</div>

				<button class="button is-success is-large is-outlined" @click="newGameState">Start Game</button>
			</div>
			<div v-show="!preGame">
				<progress class="progress is-primary" v-bind:value="progress" max="100">@{{ progress }}%</progress>
				<h5>1 of @{{ pool }} possible stations</h5>
				<h1 class="title" style="font-size:4.5rem;">@{{ question }}</h1>

				<div class="columns">
					<div class="column"></div>
					<div class="column">
						<input type="text" autocomplete="false" v-model="myAnswer" class="input is-large" id="inputStation" autofocus="autofocus" v-on:keyup.enter="submitAnswer">
					</div>
					<div class="column"></div>
				</div>

				<div v-show="gameOver" class="notification is-info">
					<button class="delete"></button>
					Game Over
				</div>

				<div class="box" v-show="help.lines.length && !gameOver">
					<strong>Lines:</strong> @{{ help.lines.join(', ') }}<br>
					<strong>Zone:</strong> @{{ help.zones.join(', ') }}
				</div>

				<div v-show="answers.length">
					<table class="table is-narrow centered">
						<thead>
							<tr>
								<th>Question</th>
								<th>Given</th>
								<th>Answer</th>
								<th>Zone</th>
								<th>Correct</th>
								<th>Lines</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="answer in answers">
								<td>@{{ answer.given }}</td>
								<td>@{{ answer.user_answer }}</td>
								<td>@{{ answer.answer }}</td>
								<td>@{{ answer.zones.join(', ') }}</td>
								<td>
									<span v-show="answer.correct" class="tag is-success">&nbsp;</span>
									<span v-show="!answer.correct" class="tag is-danger">&nbsp;</span>
								</td>
								<td>@{{ answer.lines.join(', ') }}</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4"></td>
								<td><strong>@{{ correct }} / @{{ answers.length }}<br>@{{ correctPercent }}%</strong></td>
								<td></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
