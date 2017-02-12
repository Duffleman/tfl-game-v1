<?php

namespace TFLGame\Services;

use TFLGame\GameState;

class ScoreCalculator {

	public static function calculate(GameState $state, $questions) {
		$score = 0;

		foreach ($questions as $question) {
			$answer = $question->answer;
			$answer = str_replace('_', '', $answer);

			$user_ans = $question->user_answer;
			$user_ans = strtoupper(preg_replace("/[^\w\s]/", "", $user_ans));
			$user_ans = str_replace(' ', '', $user_ans);

			if ($answer === $user_ans)
				$score++;
		}

		return $score;
	}
}
