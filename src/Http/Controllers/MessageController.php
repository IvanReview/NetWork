<?php


namespace App\Http\Controllers;


use App\Http\Models\Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends BaseController
{

    /**
     * Получить все диалоги
     *
     * @param Request $request
     * @param $user_id
     * @return JsonResponse
     */
    public function getDialogs(Request $request, $user_id): JsonResponse
    {
       $dialogs =  Message::getDialogsFromBd($user_id);

       return new JsonResponse($dialogs);
    }

    /**
     * Получить все сообщения диалога
     *
     * @param $dialog_id
     * @return JsonResponse
     */
    public function getMessages($dialog_id, $user_id): JsonResponse
    {
        $messages =  Message::getMessagesFromBd($dialog_id, $user_id);

        return new JsonResponse($messages);
    }

    /**
     * Добавить сообщение в диалог
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addMessage(Request $request): JsonResponse
    {
        $data = $request->request->all();
        $message =  Message::addMessageToBd($data);

        return new JsonResponse($message);
    }

    /**
     * Добавить сообщение в диалог
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delMessageFromDialog(Request $request, $message_id): JsonResponse
    {
        $resp =  Message::deleteMessage($message_id);

        if ($resp) {
            return new JsonResponse(['message' => 'Delete success']);
        }
        return new JsonResponse(['message' => 'Something went wrong']);
    }

    /**
     * Добавить новое сообщение и создать диалог
     *
     * @param Request $request
     * @param $user_id
     */
    public function addNewMessageAndDialog(Request $request, $user_id)
    {
        $data = $request->request->all();

        $resp = Message::addMessageAndDialog($data, $user_id);


        if ($resp) {
            return new JsonResponse(['message' => 'Сообщение успешно добавленно']);
        } else {
            return new JsonResponse(['message' => 'Что то пошло не так']);
        }
    }

}