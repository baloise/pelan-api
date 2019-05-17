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
    $token = authenticate($conf["env"]["cookie"]);
    $decoded = JWT::decode($token, $conf['token']['secret'], $conf['token']['algorithm']);
} catch (Exception $e) {
    returnForbidden();
}
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../_config/objects/assignment.php';
$assignment = new Assignment($db);
include_once '../../_config/objects/user.php';
    $user = new User($db);
// ---- End of Get needed Objects


if (!$decoded->data->role->admin) {
    returnForbidden('Not Admin');
}

try {

    $user->team = $decoded->data->team->id;
    $exist = ($user->read(val_number($data->user, 1)))->rowCount();

    if ($exist) {

        $assignment->user = val_number($data->user, 1);
        $assignment->date = val_string($data->date, 8, 10);
        $assignment->time = val_number($data->time, 1);
        $assignment->shift = val_number($data->shift);
        $assignment->note = val_string($data->note, 0, 9999, false);

        $assignment->team = $decoded->data->team->id;
        $assignment->creator = $decoded->data->id;

        $assignment->set();
        returnSuccess();

    } else {
        returnForbidden('User not available');
    }

} catch (Exception $e) {
    returnBadRequest($e);
}
