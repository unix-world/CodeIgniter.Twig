<?php

// modified by unixman r.20230406

//-- unixman: add support for dynamic URL detection
function get_server_current_url() : string {
	//--
	$domain = (string) ($_SERVER['SERVER_NAME'] ?? 'localhost');
	//--
	$port = (int) ($_SERVER['SERVER_PORT'] ?? null);
	if(((int)$port <= 0) OR ((int)$port > 65535) OR ((int)$port == 80) OR ((int)$port == 443)) {
		$port = '';
	} else {
		$port = (string) ':'.$port;
	} //end if
	//--
	$protocol = 'http:';
	if(
		(isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https')
		OR
		(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
	) {
		$protocol = 'https:';
	} //end if
	//--
	$path = (string) ($_SERVER['SCRIPT_NAME'] ?? '');
	$dpath = (string) dirname((string)$path);
	if((string)DIRECTORY_SEPARATOR == '\\') {
		$dpath = (string) strtr((string)$dpath, [ '\\' => '/' ]);
	} //end if
	$path = (string) rtrim((string)$dpath, '/').'/';
	//--
	$url = $protocol.'//'.$domain.$port.$path;
	//--
	return (string) $url;
	//--
} //end function
define('BASESEURL', (string)get_server_current_url());
//die(BASESEURL);
//-- #unixman

// Check PHP version.
$minPhpVersion = '7.4'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
	$message = sprintf(
		'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
		$minPhpVersion,
		PHP_VERSION
	);

	exit($message);
}

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
chdir(FCPATH);

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// Load our paths config file
// This is the line that might need to be changed, depending on your folder structure.
require FCPATH . '../app/Config/Paths.php';
// ^^^ Change this line if you move your application folder

$paths = new Config\Paths();

// Location of the framework bootstrap file.
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

// Load environment settings from .env files into $_SERVER and $_ENV
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();

/*
 * ---------------------------------------------------------------
 * GRAB OUR CODEIGNITER INSTANCE
 * ---------------------------------------------------------------
 *
 * The CodeIgniter class contains the core functionality to make
 * the application run, and does all of the dirty work to get
 * the pieces all working together.
 */

$app = Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);

/*
 *---------------------------------------------------------------
 * LAUNCH THE APPLICATION
 *---------------------------------------------------------------
 * Now that everything is setup, it's time to actually fire
 * up the engines and make this app do its thang.
 */

$app->run();

// #end
