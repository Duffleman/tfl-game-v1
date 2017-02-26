<?php

namespace TFLGame\Http\Controllers;

use Illuminate\Http\Request;
use TFLGame\GameState;

class LeaderboardController extends Controller
{
    public function show()
    {
    	$states = GameState::all();

    	$states = $states->map(function ($state) {
    		$result = (new GameStateController)->result($state);
    		$state->result = $result;

    		return $state;
    	});

    	$finishedGames = $states->filter(function ($state) {
    		return $state->result['state'] === 'complete';
    	});

    	$sorted = $finishedGames->sort(function ($a, $b) {
    		return $a->result['score'] <=> $b->result['score'];
    	})->reverse();

    	return view('leaderboard', ['games' => $sorted]);
    }
}
