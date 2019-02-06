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
include_once '../../_config/objects/shift.php';
$shift = new Shift($db);
// ---- End of Get needed Objects

try {

    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);

    if(!$decoded->data->role->admin){
        returnForbidden('Not Admin');
    }

    $shift->title = $data->title;
    $shift->abbreviation = $data->abbreviation;
    $shift->color = $data->color;
    $shift->description = $data->description;
    $shift->id = $data->id;
    $shift->team = $decoded->data->team->id;

    if($shift->edit()){
        returnSuccess();
    } else {
        returnError("Update failed. Title or Abbreviation may already exist");
    }

} catch(Exception $e){
    returnForbidden();
}


