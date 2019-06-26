<?php

// ---- Enable async response
ignore_user_abort(true);
set_time_limit(0);
ob_start();
// ---- End of Enable async response


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


// ---- Get needed Objects
include_once '../../_config/objects/user.php';
$user = new User($db);
include_once '../../_config/objects/team.php';
$team = new Team($db);
// ---- End of Get needed Objects


// Use correct environment
if ($conf['env']['auth'] === 'credentials') {

    $data = json_decode(file_get_contents("php://input"));
    if(isset($data->password) && isset($data->email)){
        $user->email = val_string($data->email, 1, 90);
        $authKey = $data->password;
    } else {
        returnForbidden('credentials_needed');
    }

} else if ($conf['env']['auth'] === 'medusa') {

    include_once '../../_config/partner.php';
    $decoded = explode(";", file_get_contents('compress.zlib://data:who/cares;base64,'. $_COOKIE["MedusaToken"] ));
    $authKey = (explode("=", $decoded[0])[1]);
    $userInfo = loadPerson($authKey);
    $user->email = $userInfo['email'];

} else if ($conf['env']['auth'] === 'medusa_fake') {
    $user->email = "xx0001@demo.com"; // "xx0003@demo.com", "yy0001@demo.com"
    $authKey = "xx0001";
} else if ($conf['env']['auth'] === 'demo') {

    $user->email = "xx0001@demo.com";
    $authKey = "xx0001";

    $user->id = val_number('2', 1);
    $user->firstname = val_string('Nemo', 1, 255);
    $user->lastname = val_string('Nobody', 1, 255);
    $user->nickname = val_string('MrNobody', 1, 10, false);
    $user->edit(true);
    $team->user = $user->id;
    $team->id = val_number('1', 1);
    $team->join(val_number('1', 1));
    $team->id = val_number('2', 1);
    $team->join(val_number('4', 1));

}


// Login user
if(!$user->userExists()){

    returnForbidden('not_registered');

} else if (password_verify($authKey, $user->authkey)) {

    if ($user->readToken()) {

        $token = array(
            "iss" => $conf['token']['issuer'],
            "iat" => $conf['token']['issuedAt'],
            "exp" => $conf['token']['expireAt'],
            "nbf" => $conf['token']['notBefore'],
            "data" => array(
                "id" => (int) $user->id,
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
                "language" => $user->language,
                "nickname" => $user->nickname,
                "email" => $user->email,
                "role" => $user->role,
                "team" => $user->team
            )
        );

        $jwt = JWT::encode($token, $conf['token']['secret']);

        if($conf['env']['auth'] === 'medusa'){
            $toReturn = array(
                "token"=>$jwt,
                "prefix"=>$conf['env']['cookie']['prefix']
            );
        } else {
            $toReturn = $conf['env']['cookie']['prefix'];
        }


        if (setAuth($jwt, $conf['token']['expireAt'], $conf['env']['cookie'])) {
            returnSuccess($toReturn);
        }

    }

} else {
    returnForbidden('password_incorrect');
}

// ---- Send async response and keep processing
header('Content-Length: '.ob_get_length());
ob_end_flush();
ob_flush();
flush();
// ---- End of Send async response and keep processing

$user->edit(date('Y/m/d H:i:s', time()));
