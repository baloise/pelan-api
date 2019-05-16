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
include_once '../../_config/objects/role.php';
$role = new Role($db);
// ---- End of Get needed Objects

try {

    $role->team = $decoded->data->team->id;
    $stmt = $role->read();

    if ($stmt->rowCount() > 0) {

        $roles_arr = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $role_item = array(
                "id" => (int) $row['ID'],
                "title" => $row['Title'],
                "admin" => (int) $row['Admin'],
                "main" => (int) $row['Main'],
                "description" => $row['Description']
            );
            array_push($roles_arr, $role_item);
        }

        returnSuccess($roles_arr);

    } else {
        returnNoData();
    }

} catch (Exception $e) {
    returnForbidden();
}
