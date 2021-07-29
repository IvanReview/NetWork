<?php


namespace App\Http\Controllers\Auth;


use App\System\Database\DbConn;
use PDO;
use PDOException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthController
{
    public $db;

    public function __construct()
    {
        $this->db = DbConn::$DB->connect();
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {

        $data = $request->request->all();

        $errors = [];
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            $returnData = $this->msg(0, 404, 'Page Not Found!');
        }

        // CHECKING EMPTY FIELDS
        elseif(!isset($data['name']) || !isset($data['lastname']) || !isset($data['email']) || !isset($data['password'])
            || !isset($data['gender']) || !isset($data['date_birth'])
            || empty($data['name']) || empty($data['email']) || empty($data['email']))
        {
            $errors = ['fields' => ['name','email','password']];
            $returnData = $this->msg(0,422,'Заполните поля', $errors);
        }

        // IF THERE ARE NO EMPTY FIELDS THEN-
        else {

            $name = trim($data['name']);
            $lastname = trim($data['lastname']);
            $email = trim($data['email']);
            $password = trim($data['password']);
            $gender = ($data['gender'] === 'male') ? 1 : 0;
            $date_birth = $data['date_birth'];


            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid Email Address!';
            }

            if (strlen($password) < 6) {
                $errors['password'] = 'Пароль должен быть  не меньше 6 символов';
            }

            if (strlen($name) < 3) {
                $errors['name'] = 'Имя должно содержать минимум 3 символа';
            } else {
                try {

                    $check_email = "SELECT `email` FROM `users` WHERE `email`=:email";
                    $check_email_stmt = $this->db->prepare($check_email);
                    $check_email_stmt->bindValue(':email', $email);
                    $check_email_stmt->execute();

                    if ($check_email_stmt->rowCount()) {

                        $errors['email'] = 'Данный email уже существует';
                    }
                    else {
                        $insert_query = "INSERT INTO `users`(`name`, `lastname`, `email`, `password`, `date_registered`, 
                                    `gender`, `created_at`, `date_birth`, `status`) 
                                    VALUES (:name, :lastname, :email, :password, :date_registered, :gender,
                                        :created_at, :date_birth, :status)";

                        $insert_stmt = $this->db->prepare($insert_query);

                        // DATA BINDING
                        $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($name)));
                        $insert_stmt->bindValue(':lastname', htmlspecialchars(strip_tags($lastname)));
                        $insert_stmt->bindValue(':email', $email);
                        $insert_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
                        $insert_stmt->bindValue(':date_registered', date("Y-m-d H:i:s"));
                        $insert_stmt->bindValue(':gender', $gender);
                        $insert_stmt->bindValue(':created_at', date("Y-m-d H:i:s"));
                        $insert_stmt->bindValue(':date_birth', $date_birth);
                        $insert_stmt->bindValue(':status', '0');

                        $insert_stmt->execute();


                        $returnData = $this->msg(1, 201, 'Регистрация прошла успешно');
                        return new JsonResponse($returnData);
                    }

                } catch (PDOException $e) {

                    $returnData = $this->msg(0, 500, $e->getMessage());
                }
            }
        }

        $response = $this->validation($errors);

        return new JsonResponse($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
    }


    /**
     * Login
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->request->all();
        $errors= [];

        if($_SERVER["REQUEST_METHOD"] != "POST"):
            $returnData = $this->msg(0,404,'Page Not Found!');

        // CHECKING EMPTY FIELDS
        elseif(!isset($data['email'])
            || !isset($data['password'])
            || empty(trim($data['email']))
            || empty(trim($data['password']))
        ):

            $errors['email'][] = $errors['password'][] = 'Поле обязательно для заполнения!';

        // IF THERE ARE NO EMPTY FIELDS THEN-
        else:
            $email = trim($data['email']);
            $password = trim($data['password']);

            // CHECKING THE EMAIL FORMAT (IF INVALID FORMAT)
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
                $errors['email'][] = 'Invalid Email Address!!';

            // IF PASSWORD IS LESS THAN 8 THE SHOW THE ERROR
            elseif(strlen($password) < 6):
                $errors['password'][] = 'Пароль должен быть минимум 6 символов';

            // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
            else:
                try{
                    $fetch_user_by_email = "SELECT id, name, lastname, password, email, date_registered, date_birth, gender, status 
                                            FROM `users` WHERE `email`=:email";
                    $query_stmt = $this->db->prepare($fetch_user_by_email);
                    $query_stmt->bindValue(':email', $email,PDO::PARAM_STR);
                    $query_stmt->execute();


                    // Если пользователь найден
                    if($query_stmt->rowCount()):
                        $user_data = $query_stmt->fetch(PDO::FETCH_ASSOC);
                        $check_password = password_verify($password, $user_data['password']);


                        // Если пароль корректен генерим токен
                        if($check_password):

                            $jwt = new JwtHandler();
                            $token = $jwt->_jwt_encode_data(
                                'http://localhost/php_auth_api/',
                                array("user_id" => $user_data['id'])
                            );

                            unset($user_data['password']);
                            $returnData = [
                                'user' => $user_data,
                                'message' => 'You have successfully logged in!',
                                'token' => $token,
                                'status' => 200
                            ];

                            return new JsonResponse($returnData);

                        // Если пароль не корректен сообщение об ошибке
                        else:
                            $errors['password'][] = 'Неверный логин или пароль';
                        endif;

                    // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
                    else:
                        $errors['email'][] = 'Неверный логин или пароль';
                    endif;
                }
                catch(PDOException $e){
                    $returnData = $this->msg(0,500,$e->getMessage());
                }

            endif;

        endif;

        if (count($errors)) {
            return new JsonResponse(['errors' =>$errors], 422);
        } else {
            return new JsonResponse(['errors' =>$errors], 500);
        }

    }

    public function logout(Request $request)
    {

        return new JsonResponse(true);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function chekToken(Request $request): JsonResponse
    {
        /*$db = DbConn::$DB->connect();*/
        $headers = $request->headers->all();
        $auth = new Auth($this->db, $headers);

        if($auth->isAuth()){
            $returnData = $auth->isAuth();
        } else {
            $returnData = [
                "success" => 0,
                "status" => 401,
                "message" => "Unauthorized"
            ];
        }

        return new JsonResponse($returnData);
    }

    /**
     * @param $success
     * @param $status
     * @param $message
     * @param array $extra
     * @return array
     */
    public function msg($success, $status, $message, $field = ''): array
    {
        if (is_array($field)){
            return [];
        } else {
            return array_merge([
                'success' => $success,
                'status'  => $status,
                'message'  => $message
            ]);
        }

    }

    public function validation($errors): array
    {
        return [
            'status'  => 422,
            'errors'  => $errors,
        ];
    }

}