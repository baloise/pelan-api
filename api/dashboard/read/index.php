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
include_once '../../_config/objects/shift.php';
$shift = new Shift($db);
include_once '../../_config/objects/daytime.php';
$time = new Daytime($db);
include_once '../../_config/objects/assignment.php';
$assignment = new Assignment($db);
// ---- End of Get needed Objects

try {

    $date = val_string($data->date, 8, 10);
    $error = false;

    $time->team = $decoded->data->team->id;
    $shift->team = $decoded->data->team->id;
    $assignment->team = $decoded->data->team->id;
    $assignment->user = $decoded->data->id;

    $stmt_time = $time->read();
    $stmt_shift = $shift->read();
    $stmt_assign = $assignment->read($date, $date, $decoded->data->id);
    $stmt_note = $assignment->readNotes($date, $date);

    $dash_arr = array(
        "times" => array(),
        "shifts" => array(),
        "assigns" => array(),
        "notes" => array()
    );

    // Read Times
    if ($stmt_time->rowCount() > 0) {
        while ($row = $stmt_time->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $time_item = array(
                "id" => $id,
                "title" => $title,
                "abbreviation" => $abbreviation,
                "position" => $position,
                "description" => $description
            );
            array_push($dash_arr["times"], $time_item);
        }
    } else { $error = true; }

    // Read Shifts
    if ($stmt_shift->rowCount() > 0) {
        while ($row = $stmt_shift->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $shift_item = array(
                "id" => $id,
                "title" => $title,
                "color" => "#" . $color,
                "description" => $description
            );
            array_push($dash_arr["shifts"], $shift_item);
        }
    } else { $error = true; }

    // Read Assigns
    if ($stmt_assign->rowCount() > 0) {
        while ($row = $stmt_assign->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $assignment_item = array(
                "user" => $user,
                "time" => $time,
                "date" => (new DateTime($date))->format('Y/m/d'),
                "note" => $note,
                "shift" => $shift
            );
            array_push($dash_arr["assigns"], $assignment_item);
        }
    } else { $error = true; }

    // Read notes
    if ($stmt_note->rowCount() > 0) {
        while ($row = $stmt_note->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            if (strlen($note) > 0) {
                $note_item = array(
                    "date" => (new DateTime($date))->format('Y/m/d'),
                    "user" => $user,
                    "note" => $note
                );
                array_push($dash_arr["notes"], $note_item);
            }
        }
    } else { $error = true; }

    if(!$error){
        returnSuccess($dash_arr);
    }

    returnNoData();

} catch (Exception $e) {
    returnBadRequest();
}
