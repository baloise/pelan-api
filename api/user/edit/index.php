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
$db = $database->connect($conf['db']);
$data = json_decode(file_get_contents("php://input"));
// ---- End of Initialize Default

// ---- Authenticate Request
try {
    $token = authenticate();
    $decoded = JWT::decode($token, $conf['token']['secret'], $conf['token']['algorithm']);
} catch (Exception $e) {
    returnForbidden();
}
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of Get needed Objects


try {

    if(!isset($data->firstname)){

        $user->id = $decoded->data->id;
        $user->language = val_string($data->language, 2, 2);
        $user->editLanguage();

    } else if($decoded->data->role->admin){

        $user->id = val_number($data->id, 1);
        $user->firstname = val_string($data->firstname, 1, 255);
        $user->lastname = val_string($data->lastname, 1, 255);
        $user->nickname = val_string($data->nickname, 1, 10, false);
        $user->role = val_number($data->role, 1);
        $user->team = $decoded->data->team->id;

        $user->editDetails();
        if ($decoded->data->id !== $user->id) {
            returnSuccess();
        }

    } else {
        returnForbidden();
    }

    if ($user->readToken()) {

        $token = array(
            "iss" => $conf['token']['issuer'],
            "iat" => $conf['token']['issuedAt'],
            "exp" => $conf['token']['expireAt'],
            "nbf" => $conf['token']['notBefore'],
            "data" => array(
                "id" => $user->id,
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
                "language" => $user->language,
                "nickname" => $user->nickname,
                "email" => $user->email,
                "role" => array(
                    "id" => $user->role->id,
                    "title" => $user->role->title,
                    "admin" => $user->role->admin,
                ),
                "team" => array(
                    "id" => $user->team->id,
                    "title" => $user->team->title
                ),
            )
        );

        $jwt = JWT::encode($token, $conf['token']['secret']);
        if (setAuth($jwt, $conf['token']['expireAt'], $conf['cookie'])) {
            returnSuccess("TOKEN");
        }

    } else {
        returnError();
    }

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}
