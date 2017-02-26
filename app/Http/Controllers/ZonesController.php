<?php

namespace TFLGame\Http\Controllers;

use Illuminate\Http\Request;
use TFLGame\Zone;

class ZonesController extends Controller {

	public function zones() {
		return Zone::orderBy('label')->get()->map(function($zone) {
			return [
				'id' => $zone->id,
				'label' => $zone->label,
			];
		});
	}
}
