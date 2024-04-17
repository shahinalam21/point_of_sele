<?php 
    namespace App\Helper;
    use Exception;
    use Firebase\JWT\JWT;

    class JWTToken{
        public static function CreateToken($userEmail){
            $key = env('JWT_key');
            $payload = [
                'iss' => 'laravel-token',
                'iat' => time(),
                'exp' => time() + (60*60),
                '$userEmail'=> $userEmail

            ];
            return JWT::encode($payload, $key,'HS256');
        }
        public static function VerifyToken($token){
          try{
            $key = env('JWT_key');
            $decode = JWT::decode($token, new $key($key,'HS256'));
            return $decode->userEmail;
          }
          catch(Exception $e){
            return "unauthorized";
        }
    }
    }