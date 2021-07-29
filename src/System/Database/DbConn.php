<?php


namespace App\System\Database;


class DbConn
{
    public $db;

    public static  $DB;
    public static $config;

    public function __construct(array $config)
    {
        self::$config = $config;

        self::$DB = $this;

        $this->db = new Db($config, $dir=null);

    }

    /**
     * Возвращаем объект PDO
     *
     * @return \PDO
     */
    public function connect(): \PDO
    {
        $dns = self::$config['dsn'] ?? '';
        $user = self::$config['user'] ?? '';
        $password = self::$config['password'] ?? '';

        $pdo = new \PDO($dns, $user, $password);

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }


}