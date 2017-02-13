<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>TFL Game</title>
		<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="/css/app.css">
	</head>
	<body>
		<div id="app" class="flex-center position-ref full-height">
			<div class="content">
				<div v-cloak class="title m-b-md">@{{ question }}</div>

				<p v-show="!active">Guess the name of the TFL Station</p>
				<div v-show="active">
					<input type="text" autofocus v-model="userAnswer" v-on:keyup.enter="submitAnswer">
				</div>

				<h2 v-show="!active && end">Congratulations... You scored @{{ score }} of @{{ questionCount }}!</h2>

				<div v-show="answered">
					<ul class="previousAnswers">
						<li v-for="qs in previous" v-bind:class="{ 'correct' : qs.correct }">@{{ qs.answer }}</li>
					</ul>
				</div>

				<div v-show="uwotm8.length >= 1" id="uwot">
					<h3 style="margin-top:0;">u wot m8?</h3>
					<p>Haha, I guess you couldn't find that one and it shocked you that it existed?</p>
					<ul class="desc">
						<li v-for="stns in uwotm8">
							You were given: @{{ stns.question}}<br>
							You said: @{{ stns.user_answer }}<br>
							The full name of the station is: @{{ stns.name }}.<br>
							<span v-show="stns.correct">You got it right though!</span>
							<span v-show="!stns.correct">Not suprised you didn't get that one ;)</span>
							<br>
							Odd one? It's on the following lines:<br>
							<ul class="desc">
								<li v-for="line in stns.lines">@{{ line }}</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.2.0/vue-resource.min.js"></script>
		<script src="/js/app.js"></script>
	</body>
</html>
