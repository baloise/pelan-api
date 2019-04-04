<?php

// ---- Initialize Default
include_once '../../_config/settings.php';
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
include_once '../../_config/objects/shift.php';
$shift = new Shift($db);
// ---- End of Get needed Objects


if (!$decoded->data->role->admin) {
    returnForbidden('Not Admin');
}

try {

    $shift->id = $data->id;
    $shift->title = $data->title;
    $shift->color = $data->color;
    $shift->description = $data->description;
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
