<?php

// ---- Initialize Default
include_once '../../settings.php';
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
include_once '../../_config/objects/assignment.php';
$assignment = new Assignment($db);
// ---- End of Get needed Objects


try {

    $assignment->team = $decoded->data->team->id;
    $from = val_string($data->from, 8, 10);
    $to = val_string($data->to, 8, 10);

    if(isset($data->user)){
        $stmt = $assignment->read($from, $to, val_number($data->user, 1));
    } else {
        $stmt = $assignment->read($from, $to);
    }

    if ($stmt->rowCount() > 0) {

        $assignments_arr = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $assignment_item = array(
                "user" => $user,
                "time" => $time,
                "date" => (new DateTime($date))->format('Y/m/d'),
                "note" => $note,
                "shift" => $shift
            );
            array_push($assignments_arr, $assignment_item);
        }

        returnSuccess($assignments_arr);

    } else {
        returnNoData();
    }

} catch (Exception $e) {
    returnBadRequest();
}
