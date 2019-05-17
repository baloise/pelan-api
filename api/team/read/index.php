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
$db = $database->connect($conf['db']);
$data = json_decode(file_get_contents("php://input"));
// ---- End of Initialize Default

// ---- Authenticate Request
try {
    $token = authenticate($conf["env"]["cookie"]);
    $decoded = JWT::decode($token, $conf['token']['secret'], $conf['token']['algorithm']);
} catch (Exception $e) {
    returnForbidden();
}
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../_config/objects/team.php';
$team = new Team($db);
// ---- End of Get needed Objects

try {

    $team->user_id = $decoded->data->id;
    $stmt = $team->read();

    if ($stmt->rowCount() > 0) {

        $elems_arr = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $item = array(
                "id" => $team_id,
                "title" => $team_title,
                "description" => $team_description,
                "owner" => array(
                    "id" => $team_owner_id,
                    "name" => $team_owner
                )
            );
            array_push($elems_arr, $item);
        }

        returnSuccess($elems_arr);

    } else {
        returnNoData();
    }

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}
