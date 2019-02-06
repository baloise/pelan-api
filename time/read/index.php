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

    $time->team = $decoded->data->team->id;
    $stmt = $time->read();
    $num = $stmt->rowCount();

    if($num>0){

        $times_arr=array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $time_item = array(
                "id" => $id,
                "title" => $title,
                "abbreviation" => $abbreviation,
                "position" => $position,
                "description" => $description
            );
            array_push($times_arr, $time_item);
        }

        returnSuccess($times_arr);

    } else {
        returnNoData();
    }

} catch(Exception $e){
    returnForbidden();
}


