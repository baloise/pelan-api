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
include_once '../../_config/objects/role.php';
$role = new Role($db);
// ---- End of Get needed Objects

try {

    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);

    $role->team = $decoded->data->team->id;
    $stmt = $role->readAll();
    $num = $stmt->rowCount();

    if($num>0){

        $roles_arr=array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $role_item = array(
                "id" => $id,
                "title" => $title,
                "abbreviation" => $abbreviation,
                "admin" => $admin,
                "team" => $team
            );
            array_push($roles_arr, $role_item);
        }

        returnSuccess($roles_arr);

    } else {
        returnNoData();
    }

} catch(Exception $e){
    returnForbidden();
}


