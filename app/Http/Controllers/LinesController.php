<?php

namespace TFLGame\Http\Controllers;

use Illuminate\Http\Request;
use TFLGame\Line;

class LinesController extends Controller {

	public function lines() {
		return Line::all()->map(function($line) {
			return [
				'id' => $line->id,
				'name' => $line->name,
			];
		});
	}
}
