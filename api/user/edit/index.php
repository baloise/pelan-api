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

// ---- Authenticate Request
try {
    $token = authenticate();
    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);
} catch (Exception $e) {
    returnForbidden();
}
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of Get needed Objects


try {

    if ($decoded->data->role->admin && isset($data->firstname)) {
        //User is Admin

        $user->id = val_number($data->id, 1);
        $user->firstname = val_string($data->firstname, 1, 255);
        $user->lastname = val_string($data->lastname, 1, 255);
        $user->nickname = val_string($data->nickname, 1, 10, false);
        $user->language = val_string($data->language, 2, 2);
        $user->role = val_number($data->role, 1);
        $user->team = $decoded->data->team->id;

        if ($user->editDetails()) {
            if ($decoded->data->id !== $data->id) {
                returnSuccess();
            }
        } else {
            returnError();
        }

    } else {
        //User is not Admin

        $user->id = $decoded->data->id;
        $user->language = val_string($data->language, 2, 2);

        if (!$user->editLanguage()) {
            returnError();
        }

    }

    if ($user->readToken() && $decoded->data->id === $user->id) {

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
                "role" => array(
                    "id" => $user->role->id,
                    "title" => $user->role->title,
                    "description" => $user->role->description,
                    "admin" => $user->role->admin,
                ),
                "team" => array(
                    "id" => $user->team->id,
                    "title" => $user->team->title
                ),
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
