<?php

Route::get('lines', 'LinesController@lines');
Route::get('zones', 'ZonesController@zones');

Route::post('gamestate', 'GameStateController@create');
Route::get('question/{state}', 'QuestionController@getQuestion');
Route::get('help/{state}', 'QuestionController@help');
Route::post('answer/{state}', 'QuestionController@answer');
Route::get('result/{state}', 'GameStateController@result');
