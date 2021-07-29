<?php


namespace App\Http\Controllers;


use App\Http\Models\Like;
use App\Http\Models\Post;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PostController extends BaseController
{
    /**
     * Добавить пост
     *
     * @param Request $request
     * @param $user_id
     * @param $author_id
     * @return JsonResponse
     */
    public function addPost(Request $request, $user_id, $author_id): JsonResponse
    {

        $data = $request->request->all();

        $posts = Post::addPostForUsers($data,$user_id,$author_id);

        return new JsonResponse($posts);
    }

    /**
     * @param Request $request
     * @param $user_id
     * @param $author_id
     * @return JsonResponse
     */
    public function editPost(Request $request, $post_id): JsonResponse
    {

        $data = $request->request->all();


        $posts = Post::editPostForUsers($data, $post_id);

        return new JsonResponse($posts);
    }


    /**
     * Удалить пост
     *
     * @param $post_id
     * @return JsonResponse
     */
    public function deletePost($post_id): JsonResponse
    {
        $result = Post::deletePost($post_id);
        if ($result) {
            return new JsonResponse(['message' =>'Delete success', 'post_id' => $post_id]);
        }

        return new JsonResponse(['message' =>'Something went wrong']);
    }


    /**
     * Добавить коммент к посту
     *
     * @param Request $request
     * @param $post_id
     * @param $user_id
     * @return JsonResponse
     */
    public function addComment(Request $request, $post_id, $user_id): JsonResponse
    {
        $data = $request->request->all();

        $comment = Post::addCommentToPost($data, $post_id, $user_id);

        return new JsonResponse($comment);
    }

    /**
     * Удалить коммент
     *
     * @param $comment_id
     * @return JsonResponse
     */
    public function deleteComment($comment_id): JsonResponse
    {
        $result = Post::deleteComment($comment_id);

        if ($result) {
            return new JsonResponse(['message' =>'Delete success', 'comment_id' => $comment_id]);
        }

        return new JsonResponse(['message' =>'Something went wrong']);
    }


    /**
     * Добавление лайков дизлайков к посту
     *
     * @param Request $request
     * @param $post_id
     * @param $user_id
     */
    public function likePost(Request $request, $post_id, $user_id): JsonResponse
    {
        $data = $request->request->all();

        $result = Like::addLikeDisLike($data, $post_id, $user_id);

        if ($result) {
            $post = Post::getPost($post_id);
            return  new JsonResponse($post);
        }

        return  new JsonResponse(['message' => 'Ничего не поменялось']);
    }

}