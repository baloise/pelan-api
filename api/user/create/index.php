<?php

// ---- Initialize Default
include_once '../../_config/settings.php';
include_once '../../_config/core.php';
include_once '../../_config/headers.php';
include_once '../../_config/database.php';
include_once '../../_config/validate.php';
include_once '../../_config/libraries/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libraries/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libraries/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libraries/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
$database = new Database();
$db = $database->connect($db_conf);
$data = json_decode(file_get_contents("php://input"));
// ---- End of Initialize Default

// ---- Get needed Objects
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of Get needed Objects


try {

    $login = val_number($data->withLogin);

    $user->firstname = val_string($data->user->firstname, 1, 255);
    $user->lastname = val_string($data->user->lastname, 1, 255);
    $user->email = val_string($data->user->email, 1, 89);
    $user->nickname = val_string($data->user->nickname, 1, 10);
    $user->language = val_string($data->user->language, 2, 2);
    $user->authkey = val_string($data->user->password, 1, 999, false);

    if($user->userExists()){
        returnBadRequest('email_in_use');
    }

    if(!$user->create()){
        returnError('Unable to create user');
    }

    if(!$login){
        returnSuccess();
    }

    if ($user->readToken()) {

        $token = array(
            "iss" => $token_conf['issuer'],
            "iat" => $token_conf['issuedAt'],
            "exp" => $token_conf['expireAt'],
            "nbf" => $token_conf['notBefore'],
            "data" => array(
                "id" => $user->id,
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
                "language" => $user->language,
                "nickname" => $user->nickname,
                "email" => $user->email,
                "role" => $user->role,
                "team" => $user->team
            )
        );

        $jwt = JWT::encode($token, $token_conf['secret']);
        if (setAuth($jwt, $token_conf['expireAt'], $api_conf['cookie'])) {
            returnSuccess("TOKEN");
        }

    } else {
        returnError();
    }

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}
