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
				<div class="title m-b-md">@{{ question }}</div>

				<p v-show="!active">Guess the name of the TFL Station</p>
				<div v-show="active">
					<input type="text" v-model="userAnswer">
				</div>
			</div>
		</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.2.0/vue-resource.min.js"></script>
		<script src="/js/app.js"></script>
	</body>
</html>
