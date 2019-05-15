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
include_once '../../_config/objects/team.php';
$team = new Team($db);
// ---- End of Get needed Objects


try {

    $code = val_string($data->code, 10, 10, true);
    $email = $decoded->data->email;
    $team->user = $decoded->data->id;
    $where = $team->hasInvite($code, $email);

    if ($where) {
        $team->id = $where['team'];
        $joined = $team->join($where['role']);
        if($joined){
            returnSuccess($joined);
        } else {
            returnError('Unable to join team');
        }
    } else {
        returnBadRequest('Invitation not found');
    }


} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}
