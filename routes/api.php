<?php

use Illuminate\Http\Request;

Route::post('gamestate', 'GameStateController@create');
Route::get('question/{state}', 'QuestionController@getName');
Route::post('answer/{state}', 'QuestionController@answer');
Route::get('result/{state}', 'GameStateController@result');
Route::get('uwotm8/{state}', 'QuestionController@uwotm8');
