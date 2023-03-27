<?php

namespace App\Controllers;

class SampleBenchmark extends BaseController {

	public function index() {

		return $this->twigView('sample_benchmark', [
			'date_time' => (string) \date('Y-m-d H:i:s O'),
		]);

	}

}

// #end
