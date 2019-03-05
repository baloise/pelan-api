<?php

// ---- Initialize Default
include_once '../../_config/headers.php';
include_once '../../_config/database.php';
include_once '../../_config/core.php';
$database = new Database();
$db = $database->connect();
$data = json_decode(file_get_contents("php://input"));
// ---- End of Initialize Default

// ---- Get needed Objects
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of Get needed Objects

if (
    $data->firstname &&
    $data->lastname &&
    $data->language &&
    $data->identifier &&
    $data->nickname &&
    $data->email
) {

    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;
    $user->language = $data->language;
    $user->identifier = $data->identifier;
    $user->nickname = $data->nickname;
    $user->email = $data->email;

    try {
        $user->create();
        returnSuccess();
    } catch (Exception $e) {
        returnError($e);
    }

} else {
    returnBadRequest();
}


