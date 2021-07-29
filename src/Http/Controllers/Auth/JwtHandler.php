<?php


namespace App\Http\Controllers\Auth;


use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;

class JwtHandler
{
    protected string $jwt_secret;
    protected $token;
    protected int $issuedAt;
    protected int $expire;
    protected $jwt;

    public function __construct()
    {
        // set your default time-zone
        date_default_timezone_set('Europe/Ulyanovsk');
        $this->issuedAt = time();

        // Token Validity (3600 second = 1hr)
        $this->expire = $this->issuedAt + 3600 * 3600;

        // Set your secret or signature
        $this->jwt_secret = "this_is_my_secret";
    }

    // ENCODING THE TOKEN
    /**
     * @param $iss
     * @param $data
     * @return string
     */
    public function _jwt_encode_data($iss, $data): string
    {
        $this->token = array(
            //Adding the identifier to the token (who issue the token)
            "iss" => $iss,
            "aud" => $iss,
            // Adding the current timestamp to the token, for identifying that when the token was issued.
            "iat" => $this->issuedAt,
            // Token expiration
            "exp" => $this->expire,
            // Payload
            "data"=> $data
        );

        $this->jwt = JWT::encode($this->token, $this->jwt_secret);
        return $this->jwt;
    }

    /**
     * @param $msg
     * @return array
     */
    protected function _errMsg($msg): array
    {
        return [
            "auth" => 0,
            "message" => $msg
        ];
    }

    //DECODING THE TOKEN
    /**
     * @param $jwt_token
     * @return array
     */
    public function _jwt_decode_data($jwt_token): array
    {
        try{

            $decode = JWT::decode($jwt_token, $this->jwt_secret, array('HS256'));


            return [
                "auth" => 1,
                "data" => $decode->data
            ];
        }
        catch(ExpiredException $e){
            return $this->_errMsg($e->getMessage());
        }
        catch(SignatureInvalidException $e){
            return $this->_errMsg($e->getMessage());
        }
        catch(BeforeValidException $e){
            return $this->_errMsg($e->getMessage());
        }
        catch(\DomainException $e){
            return $this->_errMsg($e->getMessage());
        }
        catch(\InvalidArgumentException $e){
            return $this->_errMsg($e->getMessage());
        }
        catch(\UnexpectedValueException $e){
            return $this->_errMsg($e->getMessage());
        }

    }

}