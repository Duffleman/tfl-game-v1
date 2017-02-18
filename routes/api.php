<?php

use Illuminate\Http\Request;

Route::get('help/{state}', 'QuestionController@help');
Route::get('lines', 'LinesController@lines');
Route::get('question/{state}', 'QuestionController@getQuestion');
Route::get('result/{state}', 'GameStateController@result');
Route::get('uwotm8/{state}', 'QuestionController@uwotm8');
Route::post('answer/{state}', 'QuestionController@answer');
Route::post('gamestate', 'GameStateController@create');
