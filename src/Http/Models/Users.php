<?php


namespace App\Http\Models;


use App\System\Database\DbConn;
use PDO;

class Users
{

    /**
     * Получить все данные связанные с пользователем
     *
     * @param $id
     * @return array
     */
    public static function userData($id): array
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT id, name, lastname, date_birth, email, gender, about_me, image  
                FROM users WHERE users.id = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();
        $user = $statement->fetch( PDO::FETCH_ASSOC);

        return $user;
    }

    /**
     * Получить данные друга или простого пользователя
     *
     * @param $user_id
     * @param $friend_id
     * @return mixed
     */
    public static function userOrFriend($user_id, $friend_id)
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT users.*, friends.relation_type
                FROM users 
                LEFT JOIN friends ON 
                CASE
                    WHEN friends.friend_id = $user_id
                    THEN friends.user_id = users.id
                    WHEN friends.user_id = $user_id
                    THEN friends.friend_id = users.id 
                END 
                WHERE users.id = :id ";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $friend_id);
        $statement->execute();
        $user = $statement->fetch( PDO::FETCH_ASSOC);

        return $user;
    }


    /**
     * Редактировать юзера
     *
     * @param $id
     * @param $data
     * @return array
     */
    public static function editUserData($id, $data): array
    {
        $db = DbConn::$DB->connect();

        $sql = "UPDATE users SET name = :name, lastname = :lastname, date_birth = :date_birth, email = :email, 
                gender = :gender, about_me = :about_me, image = :image, updated_at =  :updated_at 
                WHERE users.id = :id ";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->bindValue(":name", $data['name']);
        $statement->bindValue(":lastname", $data['lastname']);
        $statement->bindValue(":email", $data['email']);
        $statement->bindValue(":date_birth", $data['date_birth']);
        $statement->bindValue(":about_me", $data['about_me']);
        $statement->bindValue(":gender", $data['gender']);
        $statement->bindValue(":image", $data['image']);
        $statement->bindValue(":updated_at", date("Y-m-d H:i:s"));
        $statement->execute();

        return self::userData($id);
    }

    /**
     * Получить всех пользователей
     *
     * @return array
     */
    public static function getAllUsers($user_id, $offset = 0): array
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT users.*, friends.relation_type FROM users
                LEFT JOIN friends ON 
                CASE
                    WHEN friends.friend_id = $user_id
                    THEN friends.user_id = users.id
                    WHEN friends.user_id = $user_id
                    THEN friends.friend_id = users.id 
                END 
                WHERE users.id != $user_id ORDER BY updated_at DESC  LIMIT 5 OFFSET $offset";

        $statement = $db->prepare($sql);
        $statement->execute();
        $users = $statement->fetchAll( PDO::FETCH_ASSOC);


        return $users;
    }


    /**
     * Получить список друзей
     *
     * @return array
     */
    public static function getAllFriends($user_id, $offset = 0, $limit = 3): array
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT count(id) as count FROM friends 
                WHERE (user_id = $user_id OR friend_id  = $user_id) AND relation_type = 1 ";
        $statement = $db->prepare($sql);
        $statement->execute();
        $count = $statement->fetch( PDO::FETCH_ASSOC);


        $sql = "SELECT users.*, friends.relation_type  FROM users, friends WHERE 
                CASE
                    WHEN friends.friend_id = $user_id
                    THEN friends.user_id = users.id
                    WHEN friends.user_id = $user_id
                    THEN friends.friend_id = users.id 
                END 
                AND friends.relation_type = 1 ORDER BY updated_at DESC";

        $sql .= " LIMIT {$offset}, {$limit}";

        $statement = $db->prepare($sql);
        $statement->execute();
        $friends = $statement->fetchAll( PDO::FETCH_ASSOC);

        $request_friend = self::getRequestForFriendship($user_id);


        return ['data' => $friends, 'accept_friends' => $request_friend  ,'count_all' => $count['count']];
    }


    /**
     * Получить запросы в друзья
     *
     * @param $user_id
     * @return array
     */
    public static function getRequestForFriendship($user_id): array
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT users.*, friends.relation_type  FROM users, friends WHERE 
                CASE
                    WHEN friends.friend_id = $user_id
                    THEN friends.user_id = users.id
                END 
                AND  friends.relation_type = 2";

        $statement = $db->prepare($sql);
        $statement->execute();
        $friends = $statement->fetchAll( PDO::FETCH_ASSOC);

        return $friends;

    }

    /**
     * Получить друзей онлайн
     *
     * @param $user_id
     * @param int $limit
     * @return array
     */
    public static function getOnlineFriends($user_id, $limit = 6): array
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT count(id) as count FROM friends WHERE user_id = $user_id OR friend_id  = $user_id";
        $statement = $db->prepare($sql);
        $statement->execute();
        $count = $statement->fetch( PDO::FETCH_ASSOC);


        $sql = "SELECT users.*, friends.relation_type  FROM users, friends WHERE 
                CASE
                    WHEN friends.friend_id = $user_id
                    THEN friends.user_id = users.id
                    WHEN friends.user_id = $user_id
                    THEN friends.friend_id = users.id 
                END 
                AND friends.relation_type = 1 AND status = 1";

        $sql .= " LIMIT  {$limit}";

        $statement = $db->prepare($sql);
        $statement->execute();
        $friends = $statement->fetchAll( PDO::FETCH_ASSOC);

        return ['data' => $friends, 'count_all' => $count['count']];
    }


    /**
     * Добавить в друзья
     *
     * @param $user_id
     * @param $friend_id
     * @return array|bool
     */
    public static function addToFriends($user_id, $friend_id)
    {
        $db = DbConn::$DB->connect();

        //если запись уже есть
        $count = self::chekExistFriendship($user_id, $friend_id);
        if ($count['count']) return [];

        $sql = "INSERT INTO friends SET user_id = :user_id, friend_id = :friend_id, relation_type = 2";
        $statement = $db->prepare($sql);
        $statement->bindValue(":user_id", $user_id);
        $statement->bindValue(":friend_id", $friend_id);
        $statement->execute();

        //вернуть юзера
        $user = self::userOrFriend($user_id, $friend_id);

        return $user;
    }

    /**
     * Принять дружбу
     *
     * @param $user_id
     * @param $friend_id
     * @return array
     */
    public static function acceptFriendship($user_id, $friend_id): array
    {
        $db = DbConn::$DB->connect();

        $sql = "UPDATE friends SET relation_type = 1 WHERE user_id = :friend_id AND friend_id = :user_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":user_id", $user_id);
        $statement->bindValue(":friend_id", $friend_id);
        $statement->execute();


        //Получить обновленного пользователя
        $sql = "SELECT users.*, friends.relation_type FROM friends 
                LEFT JOIN users ON users.id = :friend_id
                WHERE user_id = :friend_id AND friend_id = :user_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":user_id", $user_id);
        $statement->bindValue(":friend_id", $friend_id);
        $statement->execute();
        $users = $statement->fetch(PDO::FETCH_ASSOC);

        return $users;
    }

    /**
     * Удалить из друзей
     *
     * @param $user_id
     * @param $friend_id
     * @return array
     */
    public static function deleteFromFriends($user_id, $friend_id): array
    {
        $db = DbConn::$DB->connect();

        $sql = "DELETE FROM friends where user_id = $user_id AND friend_id = $friend_id 
                       OR friend_id = $user_id AND user_id = $friend_id";
        $statement = $db->prepare($sql);
        $statement->execute();

        $users = self::userOrFriend($user_id, $friend_id);

        return $users;
    }


    /**
     * Найти пользователя
     *
     * @param $search_data
     * @return array
     */
    public static function searchUsersData($search_data, $user_id): array
    {
        $db = DbConn::$DB->connect();

        $name_one = $search_data[0] ?? [];
        $name_two = $search_data[1] ?? [];

        if ($name_one && $name_two) {
            $sql = "SELECT * FROM users WHERE name = '$name_one' AND  lastname = '$name_two' 
                       OR name = '$name_two' AND  lastname = '$name_one' ";
            $statement = $db->prepare($sql);
            $statement->execute();

            $users  = $statement->fetchAll();
        }

        if ($name_one && !$name_two) {
            $sql = "SELECT * FROM users WHERE name = '$name_one'  OR  lastname = '$name_one' LIMIT 10";
            $statement = $db->prepare($sql);
            $statement->execute();

            $users  = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach ($users as $key => $user) {
            $check = self::chekExistFriendship($user_id, $user['id']);
            if ($check['count'])
                $users[$key]['friend'] = true;
            else
                $users[$key]['friend'] = false;
        }

        return $users ?? [];
    }


    /**
     * Статус онлайн не онлайн
     *
     * @param $user_id
     * @return bool
     */
    public static function status_online($user_id): bool
    {
        $db = DbConn::$DB->connect();

        $sql = "UPDATE users SET status = 1 WHERE id = $user_id";
        $statement = $db->prepare($sql);
        $users = $statement->execute();

        return $users;
    }

    public static function status_offline($user_id): bool
    {

        $db = DbConn::$DB->connect();

        $sql = "UPDATE users SET status = 0 WHERE id = $user_id";
        $statement = $db->prepare($sql);
        $users = $statement->execute();

        return $users;
    }

    /**
     * Проверить существование записи о дружбе
     *
     * @param $user_id
     * @param $friend_id
     * @return mixed
     */
    public static function chekExistFriendship($user_id, $friend_id)
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT COUNT(id) as count  FROM friends 
                WHERE  (user_id = :user_id AND friend_id = :friend_id) OR (user_id = :friend_id AND friend_id = :user_id)";
        $statement = $db->prepare($sql);
        $statement->bindValue(":user_id", $user_id);
        $statement->bindValue(":friend_id", $friend_id);
        $statement->execute();
        $res = $statement->fetch( PDO::FETCH_ASSOC);

        return $res;
    }



}