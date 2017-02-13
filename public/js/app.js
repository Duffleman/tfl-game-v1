new Vue({

	el: '#app',

	data: {
		question: 'TFL Game',
		playerName: null,
		userAnswer: '',
		gameState: '',
		active: true,
		end: false,
		answered: false,
		previous: [],
		total: 20,
		questionCount: 0,
		score: 0,
		uwotm8: [],
	},

	created: function() {
		this.startGame();
	},

	methods: {
		startGame: function() {
			this.$http.post('/api/gamestate', { player: this.playerName }).then(res => {
				var body = res.body;

				this.gameState = body.code;
				this.active = true;

				this.getQuestion();
			}).catch(console.warn);
		},

		getQuestion: function() {
			this.uwotm8 = [];

			this.$http.get('/api/question/' + this.gameState).then(res => {
				var body = res.body;

				this.question = body.question;
			}).catch(console.warn);
		},

		submitAnswer: function() {
			var ans = this.userAnswer;

			this.uwotm8 = [];

			if (this.previous.length >= this.total) {
				this.getScore();

				return;
			}

			if (ans === '' || ans === ' ')
				return;

			if (ans === 'uwotm8') {
				this.$http.get('/api/uwotm8/' + this.gameState).then(res => {
					var body = res.body;

					this.uwotm8 = body;
				}).catch(console.warn);

				return;
			}

			if (ans === 'result') {
				this.getScore();

				return;
			}

			this.$http.post('/api/answer/' + this.gameState, {
				answer: ans,
			}).then(res => {
				var body = res.body;

				this.answered = true;
				this.previous.unshift({
					answer: body.answer,
					given: ans,
					correct: body.correct,
				});
				this.userAnswer = '';
				this.getQuestion();
			}).catch(console.warn);
		},

		getScore: function() {
			this.$http.get('/api/result/' + this.gameState).then(res => {
				var body = res.body;

				this.score = body.score;
				this.questionCount = body.questions.length;
				this.end = true;
				this.active = false;
				this.question = 'TFL Game';
			}).catch(console.warn);
		}
	}
});
