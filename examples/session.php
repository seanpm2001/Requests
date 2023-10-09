<?php
/**
 * Requests for PHP, an HTTP library.
 *
 * @package   Requests\Examples
 * @copyright 2012-2023 Requests Contributors
 * @license   https://github.com/WordPress/Requests/blob/stable/LICENSE ISC
 * @link      https://github.com/WordPress/Requests
 */

// First, include the Requests Autoloader.
require_once dirname(__DIR__) . '/src/Autoload.php';

// Next, make sure Requests can load internal classes.
WpOrg\Requests\Autoload::register();

// Set up our session
$session                    = new WpOrg\Requests\Session('http://httpbin.org/');
$session->headers['Accept'] = 'application/json';
$session->useragent         = 'Awesomesauce';

// Now let's make a request!
$request = $session->get('/get');

// Check what we received
var_dump($request);

// Let's check our user agent!
$request = $session->get('/user-agent');

// And check again
var_dump($request);
