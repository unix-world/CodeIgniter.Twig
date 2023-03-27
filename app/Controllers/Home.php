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
			'base_url' 			=> (string) rtrim(\BASESEURL, '/'),
		]);

	}


	public function json() {

		return (object) $this->response->setJSON([
			'ci_version' 		=> (string) \CodeIgniter\CodeIgniter::CI_VERSION,
			'ci_environment' 	=> (string) \ENVIRONMENT,
			'twig_version' 		=> (string) \Twig\Environment::VERSION,
			'date_year' 		=> (string) date('Y'),
			'base_url' 			=> (string) \BASESEURL,
		]);

	}


	public function xml() {

		return (object) $this->response->setXML([
			'ci_version' 		=> (string) \CodeIgniter\CodeIgniter::CI_VERSION,
			'ci_environment' 	=> (string) \ENVIRONMENT,
			'twig_version' 		=> (string) \Twig\Environment::VERSION,
			'date_year' 		=> (string) date('Y'),
			'base_url' 			=> (string) \BASESEURL,
		]);

	}


	public function dbtest() {

		$db = db_connect();

		$db->query('BEGIN');

		$sql = 'CREATE TABLE table_main_sample (id int NOT NULL, name character varying(100) NOT NULL, description text NOT NULL)';
		$query = $db->query($sql);
		if($query !== true) {
			$this->response->setStatusCode(500);
			$this->response->setContentType('text/plain');
			return 'ERR: Failed to Create Table Structure';
		}

		$sql = "INSERT INTO table_main_sample (id, name, description) VALUES (1, 'One', 'Number (One)')";
		$query = $db->query($sql);
		if(($query !== true) || ($db->affectedRows() !== 1)) {
			$this->response->setStatusCode(500);
			$this->response->setContentType('text/plain');
			return 'ERR: Failed to Insert to Table - Row #1';
		}

		$sql = "INSERT INTO table_main_sample (id, name, description) VALUES (2, 'Two', 'Number (Two)')";
		$query = $db->query($sql);
		if(($query !== true) || ($db->affectedRows() !== 1)) {
			$this->response->setStatusCode(500);
			$this->response->setContentType('text/plain');
			return 'ERR: Failed to Insert to Table - Row #2';
		}

		$db->query('COMMIT');

		$sql = 'UPDATE table_main_sample SET description = :description: WHERE (id = :id:)';
		$arr = [
			'description' => 'Number One',
			'id' => 1,
		];
		$query = $db->query($sql, $arr);
		if($query !== true) {
			$this->response->setStatusCode(500);
			$this->response->setContentType('text/plain');
			return 'ERR: Failed to Update in Table - Row #1';
		}

		$sql = 'UPDATE table_main_sample SET description = ? WHERE (id = ?)';
		$arr = [
			'Number Two',
			2,
		];
		$query = $db->query($sql, $arr);
		if($query !== true) {
			$this->response->setStatusCode(500);
			$this->response->setContentType('text/plain');
			return 'ERR: Failed to Update in Table - Row #2';
		}

		$sql = 'SELECT COUNT(1) AS cnt FROM table_main_sample';
		$query = $db->query($sql);
		$result = $query->getResultArray();
		$count = (int) isset($result[0]) ? ($result[0]['cnt'] ?? 0) : 0;

		$sql = 'SELECT * FROM table_main_sample LIMIT 10 OFFSET 0';
		$query = $db->query($sql);
		$results = (array) $query->getResultArray();

		$sql = 'DELETE FROM table_main_sample';
		$query = $db->query($sql);
		if(($query !== true) || ($db->affectedRows() !== 2)) {
			$this->response->setStatusCode(500);
			$this->response->setContentType('text/plain');
			return 'ERR: Failed to Delete from Table - All Rows';
		}

		$this->response->setContentType('text/html');
		return (string) '<pre>'.esc((string)\json_encode([
				'status' => 'OK',
				'rows' => (int) $count,
				'results' => (array) $results,
			],
			\JSON_PRETTY_PRINT
		)).'</pre>';

	}


}

// #end
