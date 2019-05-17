<?php

// Application Params
error_reporting(E_ALL);
//error_reporting(0); <-- to deactivate
date_default_timezone_set('Europe/Zurich');

header("Access-Control-Allow-Origin: " . $conf['env']['cors']);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 86400");

if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
    die();
}
