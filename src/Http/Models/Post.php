<?php


namespace App\Http\Models;


use App\System\Database\DbConn;
use PDO;

class Post
{

    /**
     * Получить все посты вместе с комментариями
     *
     * @param $user_id
     * @return array
     */
    public static function getPostsForUser($user_id, $offset): array
    {

        $db = DbConn::$DB->connect();

        $sql = "SELECT posts.*, users.name, users.lastname, users.image FROM posts 
                LEFT JOIN users ON posts.author_id = users.id  
                WHERE posts.user_id = :id ORDER BY created_at DESC LIMIT 5 OFFSET $offset";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $user_id);
        $statement->execute();
        $posts = $statement->fetchAll( PDO::FETCH_ASSOC);

        //добавление комментариев к посту
        //id постов на странице юзера
        $posts_id = implode( ", ", array_map( fn($post) => $post['id'], $posts));

        if ($posts_id) {
            //получить комментарии и добавить их в ячейку comments

            $sql = "SELECT comments.*, users.name, users.lastname, users.image FROM comments 
                LEFT JOIN users ON comments.author_id = users.id  
                WHERE comments.post_id IN ($posts_id) ORDER BY created_at DESC";
            $statement = $db->prepare($sql);
            $statement->execute();
            $comments = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($posts as $post_key => $post) {
                foreach ($comments as $comment_key => $comment) {

                    if ($comment['post_id'] === $post['id']) {
                        $posts[$post_key]['comments'][] = $comment;
                    }
                }
            }
        }

        return $posts;
    }


    /**
     * Добавить пост
     *
     * @param $data
     * @param $user_id
     * @param $author_id
     * @return array
     */
    public static function addPostForUsers($data, $user_id, $author_id): array
    {
        $db = DbConn::$DB->connect();

        $sql = "INSERT INTO posts (title, text, user_id, author_id, likes_count, dislikes_count, created_at) 
              VALUES (:title, :text, :user_id, :author_id, 0, 0, CURRENT_TIME())";
        $statement = $db->prepare($sql);
        $statement->bindValue(":title", 'Some Title');
        $statement->bindValue(":text", $data['text']);
        $statement->bindValue(":user_id", $user_id);
        $statement->bindValue(":author_id", $author_id);
        $statement->execute();

        $id = $db->lastInsertId();
        $post = self::getPost($id);

        return $post;
    }

    /**
     * @param $data
     * @param $user_id
     * @param $author_id
     * @return mixed
     */
    public static function editPostForUsers($data, $post_id)
    {
        $db = DbConn::$DB->connect();

        $sql = "UPDATE posts SET text = :text, updated_at = CURRENT_TIME() WHERE id = :post_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":post_id", $post_id);
        $statement->bindValue(":text", $data['text']);

        $statement->execute();

        $post = self::getPost($post_id);
        $comments = self::getCommentsForPost($post_id);
        $post['comments'] = $comments;

        return $post;
    }


    /**
     * Удалить пост и комментарии к нему
     *
     * @param $post_id
     * @return bool
     */
    public static function deletePost($post_id): bool
    {
        $db = DbConn::$DB->connect();

        $sql = "DELETE FROM comments WHERE post_id = :post_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":post_id", $post_id);
        $statement->execute();

        $sql = "DELETE FROM posts WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $post_id);
        $statement->execute();

        return true;
    }

    /**
     * Удалить комментарий
     *
     * @param $comment_id
     * @return bool
     */
    public static function deleteComment($comment_id): bool
    {
        $db = DbConn::$DB->connect();

        $sql = "DELETE FROM comments WHERE id = :comment_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":comment_id", $comment_id);
        $statement->execute();

        return true;
    }

    /**
     * Извлеч пост
     *
     * @param $id
     * @return mixed
     */
    public static function getPost($id)
    {
        $db = DbConn::$DB->connect();
        $sql = "SELECT posts.*, users.name, users.lastname, users.image FROM posts
                LEFT JOIN users ON posts.author_id = users.id WHERE posts.id = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $id);

        $statement->execute();
        $post = $statement->fetch(PDO::FETCH_ASSOC);
        $post['comments']  = [];

        return $post;
    }


    /**
     * Получить комментарии поста
     *
     * @param $post_id
     * @return array
     */
    public static function getCommentsForPost($post_id): array
    {
        $db = DbConn::$DB->connect();
        $sql = "SELECT comments.*, users.name, users.lastname, users.image FROM comments 
                LEFT JOIN users ON comments.author_id = users.id  WHERE comments.post_id = :id ORDER BY created_at DESC";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $post_id);
        $statement->execute();
        $comments = $statement->fetchAll( PDO::FETCH_ASSOC);

        return $comments;

    }
    /**
     * Добавить комментарий к посту
     *
     * @param $data
     * @param $post_id
     * @param $user_id
     * @return mixed
     */
    public static function addCommentToPost($data, $post_id, $user_id)
    {
        $db = DbConn::$DB->connect();
        $sql = "INSERT INTO comments (text, author_id, post_id, created_at) 
                VALUES (:text,:author_id, :post_id, CURRENT_TIME())";
        $statement = $db->prepare($sql);
        $statement->bindValue(":text", $data['text']);
        $statement->bindValue(":author_id", $user_id);
        $statement->bindValue(":post_id", $post_id);

        $statement->execute();

        $id = $db->lastInsertId();
        $comment = self::getComment($id);

        return $comment;
    }


    /**
     * Получить комментарий добавленый к посту
     *
     * @param $id
     * @return mixed
     */
    public static function getComment($id)
    {
        $db = DbConn::$DB->connect();
        $sql = "SELECT comments.*, users.name, users.lastname, users.image FROM comments
                LEFT JOIN users ON comments.author_id = users.id WHERE comments.id = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $id);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

}