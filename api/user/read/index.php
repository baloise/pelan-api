<?php

// ---- Initialize Default
include_once '../../_config/core.php';
include_once '../../_config/headers.php';
include_once '../../_config/database.php';
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
try {
    $token = authenticate();
    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);
} catch (Exception $e) {
    returnForbidden();
}
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of Get needed Objects


try {

    $user->team = $decoded->data->team->id;

    if($data && $data->user){
        $stmt = $user->read($data->user);
    } else {
        $stmt = $user->read();
    }

    $num = $stmt->rowCount();

    if ($num > 0) {

        $users_arr = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user_item = array(
                "id" => $id,
                "firstname" => $firstname,
                "lastname" => $lastname,
                "nickname" => $nickname,
                "role" => $role
            );
            array_push($users_arr, $user_item);
        }

        returnSuccess($users_arr);

    } else {
        returnNoData();
    }

} catch (Exception $e) {
    returnBadRequest();
}
