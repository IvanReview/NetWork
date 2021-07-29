<?php


namespace App\Http\Controllers;


use App\Http\Models\Image;
use App\Http\Models\Users;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends BaseController
{


    /**
     * Получить всех пользователей
     *
     * @param Request $request
     * @param $user_id
     * @return JsonResponse
     */
    public function peopleNear(Request $request, $user_id): JsonResponse
    {
        $offset = $request->query->all();

        $users =  Users::getAllUsers($user_id, $offset['offset']);

        return new JsonResponse($users);
    }

    /**
     * Получить друзей пользователя
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getFriends(Request $request, $id): JsonResponse
    {
        $page_num = $request->query->get('page');
        $items_on_page = $request->query->get('items_on_page');
        $base_url = $request->getPathInfo(); // "/friends/12"

        //Paginator
        $paginator  = [];
        $paginator['perPage'] = $items_on_page ?? 6;
        $paginator['currentPage'] =  $page_num ?? 1;
        $paginator['offset'] = $paginator['perPage'] * ($paginator['currentPage'] - 1);

        $friends_data =  Users::getAllFriends($id, $paginator['offset'], $paginator['perPage']);

        //число страниц
        $paginator['pageCount']  = ceil($friends_data['count_all'] / $paginator['perPage']);
        //сформировать url для страниц
        for ($i = 1; $i <= $paginator['pageCount']; $i++) {
            $paginator['links'][] = "/friends/{$id}?page={$i}";
        }
        $paginator['next_page_url'] = ((int)$paginator['currentPage'] !== (int)$paginator['pageCount']) ?
                                        $base_url . "?page=" . ($paginator['currentPage'] + 1) : null;

        $paginator['prev_page_url'] = ((int)$paginator['currentPage'] !== 1) ?
                                        $base_url . "?page=" . ($paginator['currentPage'] - 1) : null;

        $friends_data['pagination'] = $paginator;

        return new JsonResponse($friends_data);
    }

    /**
     * Получить данные пользователя к которому зашли
     *
     * @param Request $request
     * @param $user_id
     * @param $friend_id
     * @return JsonResponse
     */
    public function getUserFriendData(Request $request, $user_id, $friend_id): JsonResponse
    {
        $data = Users::userOrFriend($user_id, $friend_id);

        return new JsonResponse($data);
    }


    /**
     * Друзья онлайн
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getFriendsOnline(Request $request, $id): JsonResponse
    {
        $friends_data =  Users::getOnlineFriends($id);

        return new JsonResponse($friends_data);
    }

    /**
     * Принять дружбу
     *
     * @param Request $request
     * @param $user_id
     * @param $friend_id
     * @return JsonResponse
     */
    public function acceptFriend(Request $request, $user_id, $friend_id): JsonResponse
    {
        $friends_data =  Users::acceptFriendship($user_id, $friend_id);

        return new JsonResponse($friends_data);
    }


    /**
     * Редактировать профиль пользователя
     *
     * @param Request $request
     * @param $user_id
     * @return JsonResponse
     */
    public function editUserProfile(Request $request, $user_id): JsonResponse
    {
        $data = $request->request->all();
        $file_image = $request->files->get('image');


        if (isset($file_image))
            $result = Image::validateFiles($file_image);

        if (isset($result)){
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . DS . UPLOAD_DIR;

            $file_names = [];

            $filename = $file_image->getClientOriginalName();
            $filename = Image::generateUniqueFileName($filename);

            $file_image->move($uploadDir, $filename);

            $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
            $data['image'] =  '/images/user_gallery/' . $filename;


            $user = Users::editUserData($user_id, $data);

            if (count($user)) {
                return new JsonResponse($user);
            }
        }
        $user = Users::editUserData($user_id, $data);

        if (count($user)) {
            return new JsonResponse($user);
        }

        return new JsonResponse(['message' => 'Something went wrong']);
    }


    /**
     * Добавить в друзья
     *
     * @param $user_id
     * @param $friend_id
     * @return JsonResponse
     */
    public function addToFriends($user_id, $friend_id): JsonResponse
    {
        $user = Users::addToFriends($user_id, $friend_id);

        if (count($user)) {
            return new JsonResponse($user);
        } else {
            return new JsonResponse(['message' => 'Добавление провалилось/Заявка уже есть']);
        }
    }

    /**
     * Устранить друга
     *
     * @param $user_id
     * @param $friend_id
     * @return JsonResponse
     */
    public function deleteFriend($user_id, $friend_id): JsonResponse
    {
        $user = Users::deleteFromFriends($user_id, $friend_id);

        if (count($user)) {
            return new JsonResponse($user);
        } else {
            return new JsonResponse(['message' => 'Удаление провалилось']);
        }
    }

    /**
     * Найти пользователей
     *
     * @param Request $request
     */
    public function searchUsers(Request $request, $user_id): JsonResponse
    {
        $data = $request->request->all();

        if ($data['search_data']) {
            $search_data  = explode( ' ', $data['search_data']);

            $users = Users::searchUsersData($search_data, $user_id);

            return new JsonResponse($users);
        }

        return new JsonResponse(['message' => 'No data!']);
    }

}