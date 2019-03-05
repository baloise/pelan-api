<?php

// ---- Initialize Default
include_once '../../_config/headers.php';
include_once '../../_config/database.php';
include_once '../../_config/core.php';
include_once '../../_config/libraries/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libraries/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libraries/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libraries/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
$database = new Database();
$db = $database->connect();
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

    if (!$decoded->data->role->admin) {
        $user->id = $decoded->data->id;
        $user->role = $decoded->data->role->id;
    } else {
        $user->id = $data->id;
        $user->role = $data->role;
    }

    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;
    $user->language = $data->language;
    $user->nickname = $data->nickname;
    $user->team = $decoded->data->team->id;

    $user->edit();

} catch (Exception $e) {

    returnBadRequest($e->getMessage());

}
