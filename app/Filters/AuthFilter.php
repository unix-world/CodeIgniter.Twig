<?php

// added by unixman r.20230406

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface {

	public function before(RequestInterface $request, $arguments=null) {

		/* sample:
		$auth = service('auth');
		if(!$auth->isLoggedIn()) {
			return redirect()->to(site_url('login')); // or can return 403 response
		}
		*/

	}

}

// #end
