<?php

// ---- Initialize Default
include_once '../../_config/core.php';
include_once '../../_config/headers.php';
include_once '../../_config/database.php';
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
include_once '../../_config/objects/assignment.php';
$assignment = new Assignment($db);
// ---- End of Get needed Objects


if (!$decoded->data->role->admin) {
    returnForbidden('Not Admin');
}

try {

    include_once '../../_config/objects/user.php';
    $user = new User($db);
    $user->team = $decoded->data->team->id;
    $exist = ($user->read($data->user))->rowCount();

    if($exist){

        $assignment->user = $data->user;
        $assignment->time = $data->time;
        $assignment->shift = $data->shift;
        $assignment->date = $data->date;
        $assignment->note = $data->note;
        $assignment->creator = $decoded->data->id;

        $assignment->set();
        returnSuccess();

    } else {
        returnForbidden('User not available');
    }

} catch (Exception $e) {
    returnBadRequest($e);
}
