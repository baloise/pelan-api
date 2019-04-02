<?php

// ---- Initialize Default
include_once '../../../_config/core.php';
include_once '../../../_config/headers.php';
include_once '../../../_config/database.php';
include_once '../../../_config/libraries/php-jwt-master/src/BeforeValidException.php';
include_once '../../../_config/libraries/php-jwt-master/src/ExpiredException.php';
include_once '../../../_config/libraries/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../../_config/libraries/php-jwt-master/src/JWT.php';
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
include_once '../../../_config/objects/assignment.php';
$assignment = new Assignment($db);
// ---- End of Get needed Objects


try {

    $stmt = $assignment->readNotes($data->from, $data->to);
    $num = $stmt->rowCount();

    if ($num > 0) {

        $assignments_arr = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $assignment_item = array(
                "date" => (new DateTime($date))->format('Y/m/d'),
                "user" => $user,
                "note" => $note
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
