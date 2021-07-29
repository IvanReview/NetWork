<?php


use App\System\Database\DbConn;

class m0002_test
{
    public function up()
    {
        $db =  DbConn::$DB->connect();
        $SQL = "ALTER TABLE users3 ADD COLUMN password VARCHAR(512) NOT NULL";
        $db->exec($SQL);

    }

    public function down()
    {
        $db =  DbConn::$DB->connect();
        $SQL = "ALTER TABLE users3 DROP COLUMN password";
        $db->exec($SQL);
    }

}