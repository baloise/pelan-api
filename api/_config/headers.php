<?php

$allow_origins = $api_conf['corsOrigins'];
$origin = array_search($_SERVER['HTTP_ORIGIN'], $allow_origins);

if (is_numeric($origin)) {
    header("Access-Control-Allow-Origin: " . $allow_origins[$origin]);
} else {
    http_response_code(403);
    die();
}

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 86400");

if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
    die();
}
