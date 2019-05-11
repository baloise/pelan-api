<?php

// ---- Include Defaults
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

// ---- Initialize Default
$database = new Database();
$db = $database->connect($db_conf);

// ---- Include Object
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of default Configuration

if ($api_conf['environment'] === 'test') {

    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="Dev-Mode: Auth required"');
        header('HTTP/1.0 401 Unauthorized');
        die('Auth unsuccessful');
    } else {
        $user->email = $_SERVER['PHP_AUTH_USER'];
        $submitKey = $_SERVER['PHP_AUTH_PW'];
    }

} else if ($api_conf['environment'] === 'testMedusa') {

    $user->email = "xx0001@demo.com"; // = Admin Helpdesk
    $submitKey = "xx0001";
    //$user->email = "xx0003@demo.com"; // = Teammitglied Helpdesk
    //$submitKey = "xx0003";
    //$user->email = "yy0001@demo.com"; // = Admin Verkauf
    //$submitKey = "yy0001";

} else if ($api_conf['environment'] === 'demo') {

    $user->email = "xx0001@demo.com";
    $submitKey = "xx0001";

    $user->id = val_number('2', 1);
    $user->firstname = val_string('Nemo', 1, 255);
    $user->lastname = val_string('Nobody', 1, 255);
    $user->nickname = val_string('MrNobody', 1, 10, false);
    $user->role = val_number('1', 1);
    $user->team = '1';
    $user->editDetails();

} else if ($api_conf['environment'] === 'prod') {
    $user->email = 'mailByMedusa';
    $submitKey = 'keyByMedusa';
}


if ($user->userExists() && password_verify($submitKey, $user->authkey)) {

    if ($user->readToken()) {

        $token = array(
            "iss" => $token_conf['issuer'],
            "iat" => $token_conf['issuedAt'],
            "exp" => $token_conf['expireAt'],
            "nbf" => $token_conf['notBefore'],
            "data" => array(
                "id" => $user->id,
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
                "language" => $user->language,
                "nickname" => $user->nickname,
                "email" => $user->email,
                "role" => array(
                    "id" => $user->role->id,
                    "title" => $user->role->title,
                    "description" => $user->role->description,
                    "admin" => $user->role->admin,
                ),
                "team" => array(
                    "id" => $user->team->id,
                    "title" => $user->team->title
                ),
            )
        );

        $jwt = JWT::encode($token, $token_conf['secret']);
        if (setAuth($jwt, $token_conf['expireAt'], $api_conf['cookie'])) {
            returnSuccess($user->email . " authenticated");
        }

    }

} else {
    returnBadRequest();
}
