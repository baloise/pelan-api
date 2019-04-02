<?php
// Application Params
error_reporting(E_ALL);
//error_reporting(0); <-- to deactivate
date_default_timezone_set('Europe/Zurich');

$api_conf = array(
    "environment" => "testMedusa", // 'test', 'testMedusa', 'prod'
    "corsOrigin" => "http://localhost:8080",
    "cookie" => array(
        "domain" => "", //IE11 doesn't like this
        "secure" => false //Set TRUE if HTTPS
    )
);

$token_conf = array(
    "secret" => 'asdffae@hjk4352[bnbnmv]lkjhgfr:334', //Change for PROD!
    "algorithm" => array('HS256'),
    "issuer" => 'Pelan Application',
    "issuedAt" => time(),
    "notBefore" => time(),
    "expireAt" => time() + (15*60) // 15*60=15Min
);

$db_conf = array(
    "host" => "localhost",
    "database" => "pelan_api",
    "user" => "root",
    "pass" => "",
);

function setAuth($token, $expire, $conf){

    $secure = false;
    if(isset($_SERVER['HTTPS'])){
        $secure = true;
    }

    $appCookie = setcookie ("appToken", $token, $expire, "/", $conf['domain'], $conf['secure'], false);
    $secureCookie = setcookie ("secureToken", $token, $expire, "/", $conf['domain'], $conf['secure'], true);
    if($appCookie && $secureCookie){
        return true;
    }

    return false;

}

function authenticate() {
    if (isset($_COOKIE["appToken"]) && isset(getallheaders()['Authorization'])) {
        list($type, $data) = explode(" ", getallheaders()['Authorization'], 2);
        if (strcasecmp($type, "Bearer") == 0) {
            if($_COOKIE["appToken"] === $data){
                return $_COOKIE["appToken"];
            } else {
                returnForbidden("Tokens not correct");
            }
        } else {
            returnForbidden("Auth-Token invalid.");
        }
    } else {
        returnForbidden("Required Tokens not found.");
    }
}

function returnSuccess($data = false) {
    http_response_code(200);
    if ($data) {
        echo json_encode(array(
        "status" => "success",
        "message" => "Request successfully handled",
        "content" => $data
        ));
    } else {
        echo json_encode(array(
        "status" => "success",
        "message" => "Request successfully handled (Returning no content)"
        ));
    }
    die();
}

function returnNoData() {
    http_response_code(204);
    echo json_encode(array(
    "status" => "success",
    "message" => "Request successfully handled but no data found"
    ));
    die();
}

function returnForbidden($reason = false) {
    http_response_code(403);
    if ($reason) {
        echo json_encode(array(
        "status" => "unauthorized",
        "message" => "User is not authorized to perform this action",
        "reason" => $reason
        ));
    } else {
        echo json_encode(array(
        "status" => "unauthorized",
        "message" => "User is not authorized to perform this action"
        ));
    }
    die();
}

function returnBadRequest($reason = false) {
    http_response_code(400);
    if ($reason) {
        echo json_encode(array(
        "status" => "failed",
        "message" => "Bad Request: Values are wrong or missing.",
        "reason" => $reason
        ));
    } else {
        echo json_encode(array(
        "status" => "failed",
        "message" => "Bad Request: Values are wrong or missing."
        ));
    }
    die();
}

function returnError($reason = false) {
    http_response_code(500);
    if ($reason) {
        echo json_encode(array(
        "status" => "error",
        "message" => "An internal error occured",
        "reason" => $reason
        ));
    } else {
        echo json_encode(array(
        "status" => "error",
        "message" => "An internal error occured",
        ));
    }
    die();
}
