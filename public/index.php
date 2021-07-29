<?php

use App\System\Database\Db;
use App\System\Database\DbConn;

require "../vendor/autoload.php";
/*require  "../config/config.php";*/



$app = require_once __DIR__ .  '/../bootstrap/App.php';


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, X-Requested-With, Access-Control-Allow-Headers, Content-Type, Accept, Origin, User-Agent, DNT, Cache-Control, X-Mx-ReqToken');
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");

header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: x-requested-with, Content-Type, origin, authorization, accept, x-access-token");


/*if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
    header('Access-Control-Allow-Headers: X-Requested-With');
    header("HTTP/1.1 200 OK");
    die();
}*/

$app->run();