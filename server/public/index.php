<?php
require "../bootstrap.php";
use Src\Controller\TrackerController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// all of our endpoints start with /tracker
// everything else results in a 404 Not Found
if ($uri[1] !== 'tracker') {
    header("HTTP/1.1 404 Not Found");
    echo "Entry URL is '/tracker'";
    exit();
}

// Get the query parameters
$queryParams = $_GET;

// Get the JSON data sent to the controller
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method, data and queryParams (If any) to the TrackerController and process the HTTP request:
$controller = new TrackerController($dbConnection, $requestMethod, $data, $queryParams);
$controller->processRequest();
