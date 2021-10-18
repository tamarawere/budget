<?php

namespace Budget;

use Auryn\Injector;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Narrowspark\HttpEmitter\SapiEmitter;

use function FastRoute\simpleDispatcher;

require __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/core/config.php';

error_reporting(E_ALL);

$environment = 'development';

/**
 * Get function where error is raised
 */
$err = 'Database Exception - ' . __FUNCTION__ . ' - ';

$request = ServerRequest::fromGlobals();

$injector = new Injector();
require __DIR__ . '/core/dependencies.php';

$emitter = new SapiEmitter();
/**
 * Register the error handler
 */
$whoops = new \Whoops\Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function ($e) {
        echo 'Todo: Friendly error page and send an email to the developer';
    });
}
$whoops->register();

$request_path = $request->getUri()->getPath();

$base_path = $config['base_path'];

if (strcmp($request_path, $base_path) !== 0) {
    $path = str_replace($base_path, '', $request_path);
} else {
    $path = '';
}

$allRoutes = require_once __DIR__ . '/core/routes.php';

$routeDefinitionCallback = function (RouteCollector $r) {
    $routesArray = $GLOBALS['allRoutes'];
    foreach ($routesArray as $route) {

        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

$dispatcher = simpleDispatcher($routeDefinitionCallback);

$routeInfo = $dispatcher->dispatch($request->getMethod(), $path);


switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:

        print_r(['Route Not Found' => $path]);
        die;
        $response = new Response();
        $response->getBody()->write('route not found');
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        $response = new Response();
        $response->getBody()->write('method not allowed');
        break;

    case Dispatcher::FOUND:

        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];
        

        $objSessionHandler = $injector->make('Budget\Core\SessionHandler');
        session_set_save_handler($objSessionHandler, true);
        session_start();

        if (isset($routeInfo[1][2])) {
            // Route requires authentication
            $objController = $injector->make('Budget\Core\AppController');

            if ($objController->checkLogin() !== false) {
                $response = $objController->setRedirect('login');
                $emitter->emit($response);
                exit;
            } else {
                $class = $injector->make($className);
                $response = $class->$method($vars);
            }
        }

        $class = $injector->make($className);
        $response = $class->$method($vars);
        break;

}

$emitter->emit($response);
