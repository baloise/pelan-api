<?php

// ---- Initialize Default
include_once '../../settings.php';
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
    $token = authenticate($conf["env"]["cookie"]);
    $decoded = JWT::decode($token, $conf['token']['secret'], $conf['token']['algorithm']);
} catch (Exception $e) {
    returnForbidden();
}
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../_config/objects/team.php';
$team = new Team($db);
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of Get needed Objects

if (!$decoded->data->role->admin) {
    returnForbidden('Not Admin');
}

try {

    $user->id = (int) $decoded->data->id;
    $team->id = (int) $decoded->data->team->id;
    $team->title = val_string($data->title, 1, 999, false);
    $team->description = val_string($data->description, 1, 999, false);

    $team->edit();

    if ($user->readToken($team->id)) {

        $token = array(
            "iss" => $conf['token']['issuer'],
            "iat" => $conf['token']['issuedAt'],
            "exp" => $conf['token']['expireAt'],
            "nbf" => $conf['token']['notBefore'],
            "data" => array(
                "id" => (int) $user->id,
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
                "language" => $user->language,
                "nickname" => $user->nickname,
                "email" => $user->email,
                "role" => $user->role,
                "team" => $user->team
            )
        );

        $jwt = JWT::encode($token, $conf['token']['secret']);
        if (setAuth($jwt, $conf['token']['expireAt'], $conf['env']['cookie'])) {
            returnSuccess($conf['env']['cookie']['prefix']);
        }

    } else {
        returnError();
    }

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}
