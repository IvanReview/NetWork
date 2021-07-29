<?php


namespace App\Http\Models;


use App\System\Database\DbConn;
use PDO;
use Symfony\Component\HttpFoundation\JsonResponse;

class Image
{


    /**
     * @param $user_id
     * @return array
     */
    public  static function getUserImages($user_id): array
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT * FROM images WHERE user_id = $user_id ORDER BY created_at DESC";

        $statement = $db->prepare($sql);
        $statement->execute();

        $images = $statement->fetchAll( PDO::FETCH_ASSOC);

        return $images;
    }


    /**
     * Добавить изображения в базу
     *
     * @param $images_name
     * @param $user_id
     */
    public static function addImagesInBd($images_name, $user_id)
    {
        $db = DbConn::$DB->connect();

        $created_at = date("Y-m-d H:i:s");
        $sql = 'INSERT INTO images (name, user_id, created_at) VALUES ';

        $values_array = [];
        foreach ($images_name as $key => $image_name) {
            $values_array[] = sprintf("('%s', '%s', '%s')", $image_name, $user_id, $created_at);
        }
        $values_string = implode(', ', $values_array);

        $sql .= $values_string;
        $statement = $db->prepare($sql);
        $statement->execute();

        return self::getUserImages($user_id);
    }

    /**
     * Добавить/поменять описание
     *
     * @param $data
     * @return mixed
     */
    public static function addDescription($data)
    {
        $db = DbConn::$DB->connect();

        $sql = "UPDATE images SET description = :description WHERE id = :id";

        $statement = $db->prepare($sql);
        $statement->bindValue(":description", $data['description']);
        $statement->bindValue(":id", $data['image_id']);
        $statement->execute();

        $sql = "SELECT * FROM images WHERE id = {$data['image_id']}";
        $statement = $db->prepare($sql);
        $statement->execute();

        $image = $statement->fetch( PDO::FETCH_ASSOC);

        return $image;

    }


    /**
     * Получить комментарии изображения
     *
     * @param $id
     * @return mixed
     */
    public static function getCommentsForImage($image_id)
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT comments_image.*, users.name, users.lastname, users.image FROM comments_image
                LEFT JOIN users ON comments_image.author_id = users.id WHERE comments_image.image_id = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $image_id);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Добавить комментарий изображенению
     * @param $data
     * @param $post_id
     * @param $user_id
     * @return mixed
     */
    public static function addCommentToImage($data, $image_id, $author_id)
    {
        $db = DbConn::$DB->connect();
        $sql = "INSERT INTO comments_image (text, image_id, author_id, created_at) 
                VALUES (:text, :image_id, :author_id,  CURRENT_TIME())";
        $statement = $db->prepare($sql);
        $statement->bindValue(":text", $data['text']);
        $statement->bindValue(":author_id", $author_id);
        $statement->bindValue(":image_id", $image_id);

        $statement->execute();

        $id = $db->lastInsertId();
        $comment = self::getLastComment($id);

        return $comment;
    }

    public static function getLastComment($id)
    {
        $db = DbConn::$DB->connect();
        $sql = "SELECT comments_image.*, users.name, users.lastname, users.image FROM comments_image
                LEFT JOIN users ON comments_image.author_id = users.id WHERE comments_image.id = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $id);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Удалить изображение
     *
     * @param $image_id
     * @return bool
     */
    public static function deleteImageFromBd($image_id): bool
    {
        $db = DbConn::$DB->connect();

        self::deleteFileImage($image_id);

        $sql = "DELETE FROM images WHERE id = $image_id";

        $statement = $db->prepare($sql);
        $statement->execute();

        return true;
    }

    /**
     * Удалить файл изображения с сервера
     *
     * @param $image_id
     * @return bool
     */
    public static function deleteFileImage($image_id): bool
    {
        $db = DbConn::$DB->connect();

        $sql = "SELECT name FROM images WHERE id = $image_id";

        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $temp_arr = explode('/', $result['name']);
        $file_name = array_pop($temp_arr);

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . DS . UPLOAD_DIR ;
        if (file_exists($uploadDir . DS .  $file_name))
            unlink($uploadDir . DS .  $file_name);

        return true;
    }

    /**
     * Проверить файл изображения
     *
     * @param $images_files
     * @return bool|JsonResponse
     */
    public static function validateFiles($images_files)
    {

        if (empty($images_files)) {
            return new JsonResponse("No file!!",
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }

        $types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');
        foreach ($images_files as $file) {
            $getMime = explode('.', $file->getClientOriginalName());
            $mime = strtolower(array_pop($getMime));

            if (!in_array($mime, $types)) {
                return new JsonResponse(['message' => 'Not correct format file'],
                    JsonResponse::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
            }
        }

        return true;
    }

    /**
     * Сгенерировать уникальное имя файла
     *
     * @param $file_name_with_ext
     * @return string
     */
    public static function generateUniqueFileName($file_name_with_ext): string
    {
        $fileNameArr = explode('.', $file_name_with_ext);
        $ext = array_pop($fileNameArr);
        $fileName = implode('.', $fileNameArr);

        return $fileName . '__' . hash('crc32', time() . mt_rand(1,1000)) . '.' . $ext ;
    }

}