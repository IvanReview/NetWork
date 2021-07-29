<?php


use App\System\Database\Db;
use App\System\Database\DbConn;

require_once __DIR__ . '/vendor/autoload.php';
//подключение dot env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$config  = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
];

//полный путь до директории например C:\OpenServer\domains\socialNetwork
new DbConn($config['db']);

$db = new Db($config['db'], __DIR__);


$db->applyMigrations();