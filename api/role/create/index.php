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
include_once '../../_config/objects/role.php';
$role = new Role($db);
// ---- End of Get needed Objects


if (!$decoded->data->role->admin) {
    returnForbidden('Not Admin');
}

try {

    $role->title = val_string($data->title, 1, 255, false);
    $role->description = val_string($data->description, 1, 9999, false);
    $role->admin = val_number($data->admin);
    $role->team = $decoded->data->team->id;
    $role->main = 0;

    if($role->admin < 0 || $role->admin > 1){
        returnBadRequest('Admin-Value has to be boolean (0 or 1)');
    }

    if ($role->create()) {
        returnSuccess($role->id);
    }

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}
