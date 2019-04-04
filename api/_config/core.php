<?php

function setAuth($token, $expire, $conf) {

    $appCookie = setcookie("appToken", $token, $expire, "/", $conf['domain'], $conf['secure'], false);
    $secureCookie = setcookie("secureToken", $token, $expire, "/", $conf['domain'], $conf['secure'], true);

    if ($appCookie && $secureCookie) {
        return true;
    }

    return false;

}

function authenticate() {
    if (isset($_COOKIE["appToken"]) && isset(getallheaders()['Authorization'])) {
        list($type, $data) = explode(" ", getallheaders()['Authorization'], 2);
        if (strcasecmp($type, "Bearer") == 0) {
            if ($_COOKIE["appToken"] === $data) {
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

function val_string ($value, $min=false, $max=false) {

    $state = true;
    $value = trim($value);
    $value = htmlspecialchars($value);

    $value = filter_var($value, FILTER_SANITIZE_STRING);
    if (($min && strlen($value) < $min) || ($max && strlen($value) > $max)) {
        returnBadRequest("Value-Check (String) failed");
    } else {
        return $value;
    }

}

function val_number ($value, $min=false, $max=false) {

    $state = false;
    $value = trim($value);
    $value = htmlspecialchars($value);

    $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
    $state = filter_var($value, FILTER_VALIDATE_FLOAT);
    if (($min && $value < $min) || ($max && $value > $max) || !$state) {
        returnBadRequest("Value-Check (Number) failed");
    } else {
        return $value;
    }

}

function val_email ($value, $min=false, $max=false) {

    $state = false;
    $value = trim($value);
    $value = htmlspecialchars($value);

    $value = filter_var($value, FILTER_SANITIZE_EMAIL);
    $state = filter_var($value, FILTER_VALIDATE_EMAIL);
    if (($min && $value < $min) || ($max && $value > $max) || !$state) {
        returnBadRequest("Value-Check (E-Mail) failed");
    } else {
        return $value;
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
