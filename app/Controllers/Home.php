<?php

namespace App\Controllers;

class Home extends BaseController {

	public function index() {

		return $this->twigView('welcome_message', [
			'ci_version' 		=> (string) \CodeIgniter\CodeIgniter::CI_VERSION,
			'ci_environment' 	=> (string) \ENVIRONMENT,
			'twig_version' 		=> (string) \Twig\Environment::VERSION,
			'date_year' 		=> (string) date('Y'),
		]);

	}

}

// #end
