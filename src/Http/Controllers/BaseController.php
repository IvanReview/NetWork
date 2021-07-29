<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Auth\Auth;
use App\System\Controller\Controller;
use App\System\Database\DbConn;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    protected $db;
    protected $headers;
    protected $auth;


    public function __construct()
    {
        parent::__construct();


        $auth_token = null;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth_token = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $auth_token = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        $headers = ['authorization' => [
            $auth_token
        ]];
        $auth = new Auth($headers);

        $result = $auth->isAuth();

        if ($result == null) {
            $returnData = [
                "success" => 0,
                "status" => 401,
                "message" => "Unauthorized"
            ];
            dd($returnData);
        }

    }



}