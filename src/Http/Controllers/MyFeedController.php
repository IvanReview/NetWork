<?php


namespace App\Http\Controllers;



use App\Http\Controllers\Auth\Auth;
use App\Http\Models\Post;
use App\Http\Models\Users;
use App\System\Controller\Controller;
use App\System\Database\DbConn;
use PDO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MyFeedController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo 'My site';

        return new JsonResponse('All OK!');
    }


    /**
     * Получить данные пользователя
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getUserData(Request $request, $id)
    {
        $user = Users::userData($id);
        
        return new JsonResponse($user);
    }


    /**
     * Получить посты пользователя
     *
     * @param Request $request
     * @param $user_id
     * @return JsonResponse
     */
    public function getUserPosts(Request $request, $user_id): JsonResponse
    {

        $offset = $request->query->all();

        $posts = Post::getPostsForUser($user_id, $offset['offset']);

        return new JsonResponse($posts);
    }


    /**
     * Смена статусов онлайн оффлайн
     *
     * @param $user_id
     * @return JsonResponse
     */
    public function online($user_id): JsonResponse
    {
        $resp = Users::status_online($user_id);
        sleep(30);
        $resp = Users::status_offline($user_id);

        return new JsonResponse($resp);
    }


}