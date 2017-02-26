<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('leaderboard', 'LeaderboardController@show');
