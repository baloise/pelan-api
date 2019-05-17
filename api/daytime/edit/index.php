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
include_once '../../_config/objects/daytime.php';
$time = new Daytime($db);
// ---- End of Get needed Objects

if (!$decoded->data->role->admin) {
    returnForbidden('Not Admin');
}

try {

    $time->id = val_number($data->id, 1);
    $time->title = val_string($data->title, 1, 9999, false);
    $time->abbreviation = val_string($data->abbreviation, 1, 5, false);
    $time->description = val_string($data->description, 1, 9999, false);
    $time->position = val_number($data->position, 1, 200);
    $time->team = $decoded->data->team->id;

    $time->edit();
    returnSuccess();

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}
