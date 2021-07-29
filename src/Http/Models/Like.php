<?php


namespace App\Http\Models;


use App\System\Database\DbConn;
use PDO;

class Like
{
    /**
     * Добавляем лайки или диз к посту
     *
     * @param $data
     * @param $post_id
     * @param $user_id
     * @return bool
     */
    public static function addLikeDisLike($data, $post_id, $user_id): bool
    {

        $db = DbConn::$DB->connect();

        //ищем запись в таблице лайков касательно текущего поста от данного юзера
        $sql = "SELECT * FROM likes WHERE post_id = $post_id AND user_id = $user_id";
        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        //если записи в табл likes нет создаем ее
        if (!$result) {
            $sql = "INSERT INTO likes SET user_id = $user_id, post_id = $post_id, status = {$data['status']}";
            $statement = $db->prepare($sql);
            $res = $statement->execute();

            if ($res) {
                //добавляем количество лайков
                if ($data['status']){
                    $sql = "UPDATE posts SET likes_count = likes_count + 1 WHERE posts.id = {$post_id}";
                    $statement = $db->prepare($sql);
                    $res = $statement->execute();
                } else {
                    $sql = "UPDATE posts SET dislikes_count = dislikes_count + 1 WHERE posts.id = {$post_id}";
                    $statement = $db->prepare($sql);
                    $res = $statement->execute();
                }
            }
        } else{
            //если запись есть и мы хотим поменять лайк на диз или наоборот
           if($result['status'] != $data['status']) {

               $sql = "UPDATE likes SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END WHERE post_id = $post_id";
               $statement = $db->prepare($sql);
               $res = $statement->execute();

               if ($data['status']){
                   $sql = "UPDATE posts SET likes_count = likes_count + 1, dislikes_count = dislikes_count - 1 
                           WHERE posts.id = $post_id";
                   $statement = $db->prepare($sql);
                   $res = $statement->execute();
               } else {
                   $sql = "UPDATE posts SET likes_count = likes_count - 1, dislikes_count = dislikes_count + 1 
                           WHERE posts.id = $post_id";
                   $statement = $db->prepare($sql);
                   $res = $statement->execute();
               }
           } else {

               return  false;
           }

        }

        return true;
    }


}