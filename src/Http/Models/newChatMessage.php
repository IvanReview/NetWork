<?php


namespace App\Http\Models;

use App\System\Database\DbConn;


use DbConnect;
use PDO;
//для соккетов
class newChatMessage
{
    protected $dbConn;

    public function __construct() {
        require_once(__DIR__ . "/../../../config/config.php");
        $db = new DbConnect();
        $this->dbConn = $db->connect();
    }

    /**
     * Добавить сообщение в бд
     *
     * @param $data
     * @return mixed
     */
    public  function addMessageToBd($data)
    {

        $sql = "INSERT INTO messages 
                SET dialog_id = :dialog_id, user_id = :user_id, text = :message, created_at = NOW(), status = 0";
        $statement = $this->dbConn->prepare($sql);
        $statement->bindValue(":dialog_id", $data['dialog_id']);
        $statement->bindValue(":user_id", $data['user_id']);
        $statement->bindValue(":message", $data['message']);
        $statement->execute();

        $message_id = $this->dbConn->lastInsertId();

        $message = $this->getLastAddMessage($message_id);

        return $message;
    }

    /**
     * Получить последнее добавленное сообщение
     *
     * @param $id
     * @return mixed
     */
    public  function getLastAddMessage($id)
    {

        $sql = "SELECT users.id as user_id, users.name, users.lastname, users.image, m.id as message_id, 
                m.dialog_id, m.user_id as author_id, m.text, m.created_at 
                FROM users 
                LEFT JOIN messages m ON users.id = m.user_id WHERE m.id = $id";
        $statement = $this->dbConn->prepare($sql);
        $statement->execute();
        $message = $statement->fetch(PDO::FETCH_ASSOC);

        return $message;
    }

}