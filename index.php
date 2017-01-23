<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

require __DIR__.'/vendor/autoload.php';

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
//define('WP_USE_THEMES', true);

$routes = require __DIR__.'/data/config/routes.php';
$dispatcher = FastRoute\simpleDispatcher($routes);

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
		/**
		 * Tells WordPress to load the WordPress theme and output it.
		 *
		 * @var bool
		 */
		define('WP_USE_THEMES', true);

		/** Loads the WordPress Environment and Template */
		require( dirname( __FILE__ ) . '/wp-blog-header.php' );
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        require __DIR__.'/html/require.php';
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        echo $handler($vars);
        break;
}
