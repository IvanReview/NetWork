<?php


use App\System\Database\DbConn;

class m0001_initial
{
    public function up()
    {
        $db = DbConn::$DB->connect();
        $SQL = "CREATE TABLE users3(
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        firstname VARCHAR(255) NOT NULL,
        lastname VARCHAR(255) NOT NULL,
        status TINYINT DEFAULT 1,
        created_at TIMESTAMP DEFAULT  CURRENT_TIMESTAMP 
        )ENGINE=INNODB;";
        $db->exec($SQL);

    }

    public function down()
    {
        $db = DbConn::$DB->connect();
        $SQL = "DROP TABLE users3";
        $db->exec($SQL);
    }

}