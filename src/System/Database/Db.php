<?php


namespace App\System\Database;


use Dotenv\Dotenv;

class Db
{

    public \PDO $pdo;
    public $dir;


    public function __construct(array $config, $dir)
    {
        $this->dir = $dir;

        $dns = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new \PDO($dns, $user, $password);

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }



    /**
     * Применение миграций
     */
    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $applyMigrations = $this->getApplyMigrations();

        $newMigrations = [];
        $files = scandir($this->dir . '/migrations');

        //сравниваем содерж папки migrations и того что есть в таблице migrations
        $toApplyMigrations =  array_diff($files, $applyMigrations);

        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..'){
                continue;
            }

            require_once $this->dir . '/migrations/' . $migration;
            $className =  pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className;

            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Success migration $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else{
            $this->log("All migrations applied");
        }
    }



    /**
     * Создать таблицу миграций
     */
    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR (255),
            created_at TIMESTAMP  DEFAULT  CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");

    }

    /**
     * Вернуть уже выполненые миграции
     *
     * @return array
     */
    public function getApplyMigrations(): array
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }


    /**
     * Сохранить миграции в таблицу migrations
     *
     * @param $migrations
     */
    public function saveMigrations($migrations)
    {
        //приводим все к строке вида ('m0001_initial'), ('m0002_something')
        $res_str = implode(",", array_map(fn($m) =>"('$m')", $migrations));

        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $res_str ");
        $statement->execute();
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    /**
     * Красиво отобразить сообщения о выполнении миграций
     *
     * @param $message
     */
    protected function log($message)
    {
        echo '[' .date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }

}