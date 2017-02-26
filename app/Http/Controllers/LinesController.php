<?php

namespace TFLGame\Http\Controllers;

use Illuminate\Http\Request;
use TFLGame\Line;

class LinesController extends Controller {

	public function lines() {
		$lines = Line::all()->map(function($line) {
			return [
				'id' => $line->id,
				'name' => $line->name,
				'code' => $line->code,
				'mode' => $line->type,
			];
		});

		$chunks = $lines->chunk(5)->toArray();

		return array_map(function ($chunk) {
			return array_values($chunk);
		}, $chunks);
	}
}
