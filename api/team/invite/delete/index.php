<?php

// ---- Initialize Default
include_once '../../../_config/settings.php';
include_once '../../../_config/core.php';
include_once '../../../_config/headers.php';
include_once '../../../_config/database.php';
include_once '../../../_config/validate.php';
include_once '../../../_config/libraries/php-jwt-master/src/BeforeValidException.php';
include_once '../../../_config/libraries/php-jwt-master/src/ExpiredException.php';
include_once '../../../_config/libraries/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../../_config/libraries/php-jwt-master/src/JWT.php';
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
include_once '../../../_config/objects/team.php';
$team = new Team($db);
// ---- End of Get needed Objects

if (!$decoded->data->role->admin) {
    returnForbidden('Not Admin');
}


try {

    $team->id = $decoded->data->team->id;
    $invite_id = val_number($data->id, 1);

    if($team->deleteInvite($invite_id)){
        returnSuccess();
    }

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}