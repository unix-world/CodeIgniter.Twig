<?php

namespace App\Controllers;

class Home extends BaseController {

	public function index() {

		$this->response->setStatusCode(202);

		return (string) $this->twigView('welcome_message', [
			'ci_version' 		=> (string) \CodeIgniter\CodeIgniter::CI_VERSION,
			'ci_environment' 	=> (string) \ENVIRONMENT,
			'twig_version' 		=> (string) \Twig\Environment::VERSION,
			'date_year' 		=> (string) date('Y'),
		]);

	}


	public function json() {

		$this->response->setContentType('application/json');
		return (string) \json_encode([
	//	return $this->response->setJSON([
			'ci_version' 		=> (string) \CodeIgniter\CodeIgniter::CI_VERSION,
			'ci_environment' 	=> (string) \ENVIRONMENT,
			'twig_version' 		=> (string) \Twig\Environment::VERSION,
			'date_year' 		=> (string) date('Y'),
		]);

	}


	public function xml() {

		return (object) $this->response->setXML([
			'ci_version' 		=> (string) \CodeIgniter\CodeIgniter::CI_VERSION,
			'ci_environment' 	=> (string) \ENVIRONMENT,
			'twig_version' 		=> (string) \Twig\Environment::VERSION,
			'date_year' 		=> (string) date('Y'),
		]);

	}


}

// #end
