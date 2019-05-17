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
    $token = authenticate($conf["env"]["cookie"]);
    $decoded = JWT::decode($token, $conf['token']['secret'], $conf['token']['algorithm']);
} catch (Exception $e) {
    returnForbidden();
}
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../_config/objects/team.php';
$team = new Team($db);
include_once '../../_config/objects/role.php';
$role = new Role($db);
// ---- End of Get needed Objects


try {

    $team->title = val_string($data->title, 1, 255, false);
    $team->description = val_string($data->description, 1, 9999, false);
    $team->user = $decoded->data->id;

    if ($team->create()) {

        $role->title = 'Admin';
        $role->description = 'Admin';
        $role->team = $team->id;
        $role->admin = 1;
        $role->main = 1;

        if ($role->create()) {
            if($team->join($role->id)){

                returnSuccess(array(
                    "id"=>$team->id,
                    "title"=>$team->title,
                    "description"=> $team->description
                ));

            }
        }

    }

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}
