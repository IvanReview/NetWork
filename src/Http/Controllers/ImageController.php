<?php


namespace App\Http\Controllers;


use App\Http\Models\Image;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends BaseController
{

    private $directory;


    /**
     * Получить изображения для пользователя
     *
     * @param $user_id
     * @return JsonResponse
     */
    public function getImagesForUser($user_id): JsonResponse
    {

       $images = Image::getUserImages($user_id);

        return new JsonResponse($images);
    }

    /**
     * Добавить изображение
     *
     * @param Request $request
     * @param $user_id
     */
    public function addImages(Request $request, $user_id): JsonResponse
    {
        $images_files = $request->files->get('images');

        $result = Image::validateFiles($images_files);

        if($result === true) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . DS . UPLOAD_DIR ;


            $file_names = [];
            foreach ($images_files as $key => $file) {

                $filename = $file->getClientOriginalName();
                $filename = Image::generateUniqueFileName($filename);


                $file->move($uploadDir, $filename);

                $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
                $file_names[] = '/images/user_gallery/' . $filename;
            }

            $images = Image::addImagesInBd($file_names, $user_id);

            return new JsonResponse($images);

        } else {
            return  $result;
        }
    }

    /**
     * Добавить описание к изображению
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editImageDescription(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $resp = Image::addDescription($data);

        return new JsonResponse($resp);

    }

    /**
     * Удалить изображение
     *
     * @param $image_id
     * @return JsonResponse
     */
    public function deleteImage($image_id): JsonResponse
    {
        Image::deleteImageFromBd($image_id);

        return new JsonResponse(['message' => 'Delete success!']);
    }



    /**
     * @param string $directory
     */
    public function setDirectory($directory = '')
    {
        $this->directory = $_SERVER['DOCUMENT_ROOT'] . '/' . UPLOAD_DIR . $directory;

        if (!file_exists($this->directory)) mkdir($this->directory, 0777, true);
    }


    /**
     * Получить комментарии к изображению
     *
     * @param Request $request
     * @param $image_id
     * @return JsonResponse
     */
    public function getCommentsForImage(Request $request, $image_id): JsonResponse
    {
        $comments = Image::getCommentsForImage($image_id);

        return new JsonResponse($comments);
    }

    /**
     * Добавить комментарий к изображению
     *
     * @param Request $request
     * @param $image_id
     * @param $author_id
     * @return JsonResponse
     */
    public function addCommentForImage(Request $request, $image_id, $author_id): JsonResponse
    {
        $data = $request->request->all();

        $comment = Image::addCommentToImage($data, $image_id, $author_id);

        return new JsonResponse($comment);
    }


}