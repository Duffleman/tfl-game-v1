var baseLines = [
	"bakerloo",
	"central",
	"circle",
	"district",
	"dlr",
	"hammersmith-city",
	"jubilee",
	"metropolitan",
	"northern",
	"piccadilly",
	"victoria",
	"waterloo-city",
];

var difficulties = {
	'easy': ['1', '2', '2/3'],
	'medium': ['1', '2', '2/3', '3', '4', '5'],
	'hard': ['1', '2', '2/3', '3', '4', '5', '6', '7', '8', '9'],
};

var v = new Vue({
	el: '#app',

	data: {
		alert: false,
		alertMessage: '',
		selectedLines: [],
		selectedZones: [],
		chunkedLines: [],
		allLines: [],
		allZones: [],
		player: '',
		preGame: true,
		maxQuestions: 20,
		gameOver: false,
		code: '',
		myAnswer: '',
		answers: [],
		help: { lines: [], zones: [] },
		question: '...Loading...',
		pool: 0,
	},

	created() {
		this.$http.get('/api/lines').then(resp => {
			var body = resp.body;
			var allLines = [];

			body.forEach(function (chunk) {
				chunk.forEach(function (line) {
					allLines.push(line);
				});
			});

			this.chunkedLines = body;
			this.allLines = allLines;
			this.selectedLines = baseLines;
		}).catch(resp => {
			alert(this, resp.body.code);
		});

		this.$http.get('/api/zones').then(resp => {
			var body = resp.body;

			this.allZones = body;
			this.selectedZones = difficulties['medium'];
		}).catch(resp => {
			alert(this, resp.body.code);
		});
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
		setDifficulty(difficulty) {
			var diff = difficulties[difficulty];

			if (!diff) {
				alert('missing_difficulty');
			}

			this.selectedZones = diff;
		},

		newGameState() {
			this.answers = [];
			this.preGame = false;
			this.gameOver = false;

			const body = {
				player: this.player,
				config: {
					lines: this.selectedLines,
					zones: this.selectedZones,
				},
			};

			this.$http.post('/api/gamestate', body).then(resp => {
				var body = resp.body;

				this.code = body.code;
				this.pool = body.pool;

				this.getQuestion();
			}).catch(resp => {
				alert(this, resp.body.code);
			});
		},

		getQuestion() {
			var url = '/api/question/' + this.code;

			this.$http.get(url).then(resp => {
				var body = resp.body;

				this.question = body.question;
			}).catch(resp => {
				alert(this, resp.body.code)
			});
		},

		getHelp() {
			var url = '/api/help/' + this.code;

			this.$http.get(url).then(resp => {
				var body = resp.body;

				this.help.lines = body.lines;
				this.help.zones = body.zones;
			}).catch(resp => {
				alert(this, resp.body.code);
			});
		},

		submitAnswer() {
			var url = '/api/answer/' + this.code;
			var answer = this.myAnswer;
			this.myAnswer = '';
			this.help = { lines: [], zones: [] };

			if (answer.toLowerCase() === 'help') {
				this.getHelp();

				return;
			}

			if (answer.toLowerCase() === 'restart') {
				this.newGameState();

				return;
			}

			this.$http.post(url, { answer: answer }).then(resp => {
				var body = resp.body;

				this.answers.unshift(body);

				if (this.answers.length < 20) {
					this.getQuestion();
				} else {
					this.gameOver = true;
					this.question = '...';
				}
			}).catch(resp => {
				alert(this, resp.body.code);
			});
		},
	},
});

function alert(v, code) {
	console.warn(code);

	v.alert = true;
	v.alertMessage = code;
}
