<?php
require "../bootstrap.php";
use Src\Controller\PersonController;
use Src\Controller\TodoController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$routes = [
    'person' => 'PersonController',
    'todo' => 'TodoController',
];

if (array_key_exists($uri[1], $routes)) {
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $Id = null;
    if (isset($uri[2])) {
        $Id = (int) $uri[2];
    }

    switch ($uri[1]) {
        case 'todo':
            $controller = new TodoController($dbConnection, $requestMethod, $Id);
            $controller->processRequest();
            break;
        case 'person':
            $controller = new PersonController($dbConnection, $requestMethod, $Id);
            $controller->processRequest();
            break;
        default:
            echo "Route not found!";
            break;
    }
} else {
    header("HTTP/1.1 404 Not Found");
    exit();
}
