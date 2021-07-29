<?php


use App\System\Database\Db;
use App\System\Database\DbConn;

define("ROOT", dirname(__DIR__)); //корень проекта

const DS = DIRECTORY_SEPARATOR;
const UPLOAD_DIR =  'images' . DS . 'user_gallery' ; //корень проекта


//подключение dot env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
];

//подключение к БД
new DbConn($config['db']);


$app = App\System\App::getInstance();

$config = new \App\System\Config\Config('config');
$config->addConfig('database.yaml');
$config->addConfig('app.yaml');
$app->add('config', $config);


return $app;