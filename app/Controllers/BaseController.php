<?php

// modified by unixman r.20230406

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller {

	/**
	 * Instance of the main Request object.
	 *
	 * @var CLIRequest|IncomingRequest
	 */
	protected $request;

	/**
	 * Instance of the main response object.
	 *
	 * @var ResponseInterface
	 */
	protected $response;

	/**
	 * Instance of logger to use.
	 *
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * Should enforce HTTPS access for all methods in this controller.
	 *
	 * @var int Number of seconds to set HSTS header
	 */
	protected $forceHTTPS = 0;

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	//-- unixman: add Twig Support
	private $twig = null;
	//-- #unixman

	/**
	 * Be sure to declare properties for any property fetch you initialized.
	 * The creation of dynamic property is deprecated in PHP 8.2.
	 */
	// protected $session;

	/**
	 * Constructor.
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		// Preload any models, libraries, etc, here.

		//-- unixman: add Twig Support
		$cachePath = (string)\WRITEPATH.\DIRECTORY_SEPARATOR.'cache';
		if(!\is_dir((string)$cachePath)) { // it must exists otherwise realpath below will return root directory !!
			throw new \Exception('Cache Path is missing ...');
			return;
		}
		$appPaths = new \Config\Paths();
		$appViewPaths = (string) \realpath((string)$appPaths->viewDirectory);
		$cachePath    = (string) \realpath((string)$cachePath);
		if((string)\DIRECTORY_SEPARATOR == '\\') {
			$appViewPaths = (string) \strtr((string)$appViewPaths, [ '\\' => '/' ]);
			$cachePath    = (string) \strtr((string)$cachePath,    [ '\\' => '/' ]);
		} //end if
		$appViewPaths = (string) \rtrim((string)$appViewPaths, '/').'/';
		$cachePath    = (string) \rtrim((string)$cachePath,    '/').'/';
		//--
		$this->twig = new \Twig\Environment(
			new \Twig\Loader\FilesystemLoader([ (string)$appViewPaths ]),
			[
				'charset' 			=> (string) 'UTF-8',
				'autoescape' 		=> 'html', // default escaping strategy ; other escaping strategies: js
				'optimizations' 	=> -1,
				'strict_variables' 	=> false,
				'debug' 			=> false,
				'cache' 			=> (string) $cachePath.'twig',
				'auto_reload' 		=> true,
			]
		);
		//-- #unixman

		// E.g.: $this->session = \Config\Services::session();
	}

	//-- unixman: add Twig Support
	/**
	 * Usage: instead:
	 * return view('welcome_message');
	 * use:
	 * return $this->twigView('welcome_message', [ 'var1' => 'val1' ]);
	 */
	public function twigView(string $tpl, array $data) : string {
		$tpl .= '.twig.htm';

		if(CI_DEBUG) { // register file to debugbar/views
			$renderer = \CodeIgniter\Config\Services::renderer();
			$renderer->setData($data, 'raw')->render((string)$tpl, [], false);
			$renderer = null;
		}

		$template = $this->twig->load((string)$tpl);

		return (string) $template->render((array)$data);
	}
	//-- #unixman

}

// #end
