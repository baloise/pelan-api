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

    $code = val_string($data->code, 10, 10);
    $user->email = val_string($data->email, 1, 89);

    if(!$user->userExists()){
        returnBadRequest('mail_not_found');
    }
    
    if(password_verify($code, $user->verifyCode())){
        $user->verify();
        returnSuccess();
    } else {
        returnBadRequest('code_invalid');
    }

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}
