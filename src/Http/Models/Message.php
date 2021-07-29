<?php


namespace App\Http\Models;


use App\System\Database\DbConn;
use PDO;

class Message
{

    /**
     * Получить все диалоги пользователя
     *
     * @param $user_id
     * @return array
     */
    public static function getDialogsFromBd($user_id): array
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT users.id as user_id, users.name, users.lastname, users.image, dialogs.id as dialog_id 
                FROM users, dialogs WHERE
                    CASE
                        WHEN dialogs.user1_id = $user_id
                        THEN dialogs.user2_id = users.id
                        WHEN dialogs.user2_id = $user_id
                        THEN dialogs.user1_id = users.id
                    END
                    ORDER BY dialogs.id DESC";

        $statement = $db->prepare($sql);
        $statement->execute();
        $dialogs = $statement->fetchAll( PDO::FETCH_ASSOC);

        $dialogs_id = [];
        foreach ($dialogs as $dialog){
            $dialogs_id[] = $dialog['dialog_id'];
        }

        //вставить инфу о последнем сообщении
        $dialogs = self::getLastMessage($dialogs, $dialogs_id);
        //Добавить ячейку кол-во непрочитанных сообщения
        $dialogs = self::getNoReadMessage($dialogs, $dialogs_id, $user_id);

        return $dialogs;
    }


    /**
     * Добавить в диалоги ячейку не прочитанные сообщения
     *
     * @param $dialogs
     * @param $dialogs_id
     * @return array
     */
    public static function getNoReadMessage($dialogs, $dialogs_id, $user_id): array
    {
        $db = DbConn::$DB->connect();

        $d_id = implode(',', $dialogs_id);

        if ($dialogs_id) {
            $sql = "SELECT dialog_id FROM messages WHERE status = 0 
                                 AND messages.dialog_id IN ($d_id) 
                                 AND messages.user_id != $user_id";

            $statement = $db->prepare($sql);
            $statement->execute();
            $unread_mess = $statement->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($unread_mess)) {
                $messages = [];
                foreach ($unread_mess as $key => $message) {
                    $messages[$message['dialog_id']][] = $message;
                }

                $unread_message = [];

                foreach ($messages as $key => $message) {

                    $unread_message[$key] = count($message);
                }

                //Add cell unread если в диалоге существ непрочит сообщ добавл, иначе null
                foreach ($dialogs as $key => $dialog) {
                    if (isset($unread_message[$dialog['dialog_id']]))
                        $dialogs[$key]['unread'] = $unread_message[$dialog['dialog_id']];
                    else
                        $dialogs[$key]['unread'] = null;
                }
            }
            return $dialogs;
        }

        return [];
    }


    /**
     * Получить сообщения диаллога из бд
     *
     * @param $dialog_id
     * @return array
     */
    public static function getMessagesFromBd($dialog_id, $user_id): array
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT users.id as user_id, users.name, users.lastname, users.image, m.id as message_id, 
                m.dialog_id, m.user_id as author_id, m.text, m.created_at 
                FROM users 
                LEFT JOIN messages m ON users.id = m.user_id WHERE m.dialog_id = $dialog_id";

        $statement = $db->prepare($sql);
        $statement->execute();
        $messages = $statement->fetchAll( PDO::FETCH_ASSOC);

        $sql = "UPDATE messages SET status = 1 WHERE dialog_id = $dialog_id AND user_id != $user_id";

        $statement = $db->prepare($sql);
        $statement->execute();

        return $messages;
    }


    /**
     * Добавить сообщение и новый диалог если его нет
     *
     * @param $data
     * @param $user_id
     * @return bool
     */
    public static function addMessageAndDialog($data, $user_id): bool
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT id FROM dialogs 
                WHERE user1_id = {$data['user_id']} AND user2_id = {$data['target_user_id']}
                OR user1_id = {$data['target_user_id']} AND user2_id = {$data['user_id']}";
        $statement = $db->prepare($sql);
        $statement->execute();
        $dialog_exist = $statement->fetch( PDO::FETCH_ASSOC);

        if ($dialog_exist) {//если диалог уже существует

            $sql = "INSERT INTO messages 
                SET dialog_id = :dialog_id, user_id = :user_id, text = :message, created_at = NOW(), status = 0";
            $statement = $db->prepare($sql);
            $statement->bindValue(":dialog_id", $dialog_exist['id']);
            $statement->bindValue(":user_id", $data['user_id']);
            $statement->bindValue(":message", $data['message']);
            $statement->execute();

        } else {//если диалога нет
            $sql = "INSERT INTO dialogs SET user1_id = {$data['target_user_id']}, user2_id = {$data['user_id']}";
            $statement = $db->prepare($sql);
            $statement->execute();
            $last_dialog_id = $db->lastInsertId();

            $sql = "INSERT INTO messages 
                SET dialog_id = :dialog_id, user_id = :user_id, text = :message, created_at = NOW(), status = 0";
            $statement = $db->prepare($sql);
            $statement->bindValue(":dialog_id", $last_dialog_id);
            $statement->bindValue(":user_id", $data['user_id']);
            $statement->bindValue(":message", $data['message']);
            $statement->execute();
        }

        return true;
    }


    /**
     * Добавить сообщение в бд
     *
     * @param $data
     * @return mixed
     */
    public static function addMessageToBd($data)
    {
        $db = DbConn::$DB->connect();

        $sql = "INSERT INTO messages 
                SET dialog_id = :dialog_id, user_id = :user_id, text = :message, created_at = NOW(), status = 0";
        $statement = $db->prepare($sql);
        $statement->bindValue(":dialog_id", $data['dialog_id']);
        $statement->bindValue(":user_id", $data['user_id']);
        $statement->bindValue(":message", $data['message']);
        $statement->execute();

        $message_id = $db->lastInsertId();

        $message = self::getLastAddMessage($message_id);

        return $message;
    }

    /**
     * Получить последнее добавленное сообщение
     *
     * @param $id
     * @return mixed
     */
    public static function getLastAddMessage($id)
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT users.id as user_id, users.name, users.lastname, users.image, m.id as message_id, 
                m.dialog_id, m.user_id as author_id, m.text, m.created_at 
                FROM users 
                LEFT JOIN messages m ON users.id = m.user_id WHERE m.id = $id";
        $statement = $db->prepare($sql);
        $statement->execute();
        $message = $statement->fetch(PDO::FETCH_ASSOC);

        return $message;
    }


    /**
     * Удалить сообщение
     *
     * @param $message_id
     */
    public static function deleteMessage($message_id): bool
    {
        $db = DbConn::$DB->connect();

        $sql = "DELETE FROM messages WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $message_id);

        $statement->execute();

        return true;
    }


    /**
     * Получить последнее сообщение во всех диалогах
     *
     * @param $dialogs
     * @return array
     */
    public static function getLastMessage($dialogs, $d_id): array
    {
        $db = DbConn::$DB->connect();

        $d_id = implode(',', $d_id);

        if ($d_id) {

            $sql = "SELECT messages.id, messages.dialog_id, messages.text, messages.created_at
                    FROM messages WHERE messages.dialog_id IN ($d_id) ORDER BY messages.created_at DESC";
            $statement = $db->prepare($sql);
            $statement->execute();
            $last_messages = $statement->fetchAll(PDO::FETCH_ASSOC);


            $messages = [];
            foreach ($last_messages as $key => $message) {
                $messages[$message['dialog_id']][] = $message;
            }

            //епосредственное добавление ячеки последнее сообщение
            foreach ($dialogs as $key => $dialog) {

                if (array_key_exists($dialog['dialog_id'], $messages))
                    $dialogs[$key]['last_message']  = $messages[$dialog['dialog_id']][0];
                else
                    $dialogs[$key]['last_message']  = ['' => ''];
            }
            return $dialogs;
        }

        return [];
    }


}