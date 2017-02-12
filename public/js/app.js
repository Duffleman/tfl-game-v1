new Vue({

	el: '#app',

	data: {
		question: 'TFL Game',
		playerName: null,
		userAnswer: '',
		gameState: '',
		active: true,
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
			this.$http.get('/api/question/' + this.gameState).then(res => {
				var body = res.body;

				this.question = body.question;
			}).catch(console.warn);
		}
	}
});
