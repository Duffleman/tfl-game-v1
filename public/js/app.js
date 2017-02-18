var difficulties = {
	easy: [2, 3, 4, 6, 7, 10, 14],
	medium: [1, 2, 3, 4, 6, 7, 9, 10, 11, 13, 14],
};

var v = new Vue({
	el: '#app',

	data: {
		selectedLines: [],
		allLines: [],
		player: '',
		preGame: true,
		maxQuestions: 20,
		gameOver: false,
		code: '',
		myAnswer: '',
		answers: [],
		help: [],
		question: '...Loading...',
	},

	created() {
		this.$http.get('/api/lines').then(resp => {
			var body = resp.body;

			this.allLines = body;
			this.selectedLines = body.map(function(l) { return l.id });
		}).catch(console.warn);
	},

	computed: {
		progress() {
			return (this.answers.length / this.maxQuestions) * 100;
		},

		correct() {
			var correct = 0;

			this.answers.map(function(ans) {
				if (ans.correct)
					correct++;
			});

			return correct;
		},

		correctPercent() {
			if (this.answers.length === 0) return 0;

			return ((this.correct / this.answers.length) * 100).toFixed(0);
		},
	},

	methods: {
		setDifficulty(s) {
			if (s === 'hard') {
				this.selectedLines = this.allLines.map(function(l) { return l.id });

				return;
			}

			var diff = difficulties[s];

			if (!diff)
				return;

			this.selectedLines = diff;
		},

		newGameState() {
			this.answers = [];
			this.preGame = false;
			this.gameOver = false;
			this.$http.post('/api/gamestate', { player: this.player, lines: this.selectedLines }).then(resp => {
				var body = resp.body;

				console.info('Gamestate created: ' + body.code);

				this.code = body.code;
				this.getQuestion();
			}).catch(console.warn);
		},

		getQuestion() {
			var url = '/api/question/' + this.code;

			this.$http.get(url).then(resp => {
				var body = resp.body;

				this.question = body.question;
			}).catch(console.warn);
		},

		getHelp() {
			var url = '/api/help/' + this.code;

			this.$http.get(url).then(resp => {
				var body = resp.body;

				this.help = body.lines;
			}).catch(console.warn);
		},

		submitAnswer() {
			var url = '/api/answer/' + this.code;
			var answer = this.myAnswer;
			this.myAnswer = '';
			this.help = [];

			if (answer === 'help') {
				this.getHelp();

				return;
			}

			if (answer === 'restart') {
				this.newGameState();

				return;
			}

			this.$http.post(url, { answer: answer }).then(resp => {
				var body = resp.body;

				console.info('Answer submitted: ' + answer);

				this.answers.unshift(body);

				if (this.answers.length < 20) {
					this.getQuestion();
				} else {
					this.gameOver = true;
					this.question = '...';
				}
			}).catch(console.warn);
		},
	},
});
