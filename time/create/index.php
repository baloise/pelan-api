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
$token = authenticate();
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../_config/objects/time.php';
$time = new Time($db);
// ---- End of Get needed Objects

try {

    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);

    if(!$decoded->data->role->admin){
        returnForbidden('Not Admin');
    }

    $time->title = $data->title;
    $time->abbreviation = $data->abbreviation;
    $time->position = $data->position;
    $time->description = $data->description;
    $time->team = $decoded->data->team->id;

    if($time->create()){
        returnSuccess($time->id);
    } else {
        returnError("Could not create entry");
    }

} catch(Exception $e){
    returnForbidden();
}


?>
