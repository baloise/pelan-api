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

// ---- Get needed Objects
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of Get needed Objects


try {

    $user->nickname = val_string($data->nickname, 1, 10);
    $user->language = val_string($data->language, 2, 2);

    if(!$conf['env']['auth'] === 'medusa' && !$conf['env']['auth'] === 'medusa_fake'){
        $user->firstname = val_string($data->firstname, 1, 255);
        $user->lastname = val_string($data->lastname, 1, 255);
        $user->email = val_string($data->email, 1, 89);
        $user->authkey = val_string($data->password, 1, 999, false);
    } else {

        $decoded = explode(";", file_get_contents('compress.zlib://data:who/cares;base64,'. $_COOKIE["MedusaToken"] ));
        $authKey = (explode("=", $decoded[0])[1]);

        include_once 'partner.php';
        $userInfo = loadPerson($authKey);

        $user->firstname = $userInfo['firstname'];
        $user->lastname = $userInfo['lastname'];
        $user->email = $userInfo['email'];
        $user->authkey = $authKey;

    }

    if($user->userExists()){
        returnBadRequest('email_in_use');
    }

    $code = $user->create();

    if(!$code){
        returnError('Unable to create user');
    }

    if($conf['env']['auth'] === 'medusa' || $conf['env']['auth'] === 'medusa_fake'){
        $user->verify();
        returnSuccess();
    } else {
        include_once 'sendMail.php';
        $confirm_link = "pelan.osis.io/register/verify";
        sendMail($user->email, $code, $confirm_link, $user->language);
        returnSuccess();
    }

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}
