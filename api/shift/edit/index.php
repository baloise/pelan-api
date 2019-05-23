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
include_once '../../_config/objects/shift.php';
$shift = new Shift($db);
// ---- End of Get needed Objects


if (!$decoded->data->role->admin) {
    returnForbidden('Not Admin');
}

try {

    $shift->id = val_number($data->id, 1);
    $shift->title = val_string($data->title, 1, 255, false);
    $shift->color = val_string($data->color, 6, 7);
    $shift->description = val_string($data->description, 1, 9999, false);
    $shift->team = $decoded->data->team->id;

    if (substr($shift->color, 0, 1) === "#") {
        $shift->color = substr($shift->color, 1, 6);
    }

    if ($shift->edit()) {
        returnSuccess();
    } else {
        returnError("Update failed. Title or Abbreviation may already exist");
    }

} catch (Exception $e) {
    returnBadRequest($e);
}
