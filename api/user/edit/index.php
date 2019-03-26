<?php

// ---- Initialize Default
include_once '../../_config/core.php';
include_once '../../_config/headers.php';
include_once '../../_config/database.php';
include_once '../../_config/libraries/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libraries/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libraries/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libraries/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
$database = new Database();
$db = $database->connect();
$data = json_decode(file_get_contents("php://input"));
// ---- End of Initialize Default

// ---- Authenticate Request
try {
    $token = authenticate();
    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);
} catch (Exception $e) {
    returnForbidden();
}
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of Get needed Objects


try {

    if (!$decoded->data->role->admin) {
        $user->id = $decoded->data->id;
        $user->role = $decoded->data->role->id;
        if(isset($data->language)){
            $user->language = $data->language;
        } else {
            returnBadRequest('What are you trying to do???');
        }
    } else {
        $user->id = $data->id;
        $user->role = $data->role;
        $user->firstname = $data->firstname;
        $user->lastname = $data->lastname;
        $user->nickname = $data->nickname;
        if(isset($data->language)){
            $user->language = $data->language;
        }
    }

    $user->team = $decoded->data->team->id;
    $user->email = $decoded->data->email;

    if($user->edit()){
        if($user->id !== $decoded->data->id){
            returnSuccess();
        } else if($user->edit() && $user->userExists()){

        include_once '../../_config/objects/team.php';
        include_once '../../_config/objects/role.php';

        $team = new Team($db);
        $team->id = $decoded->data->team->id;

        if($team->read()){

            $role = new Role($db);
            $role->id = $user->role;
            if($role->read()){

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
                "id" => $role->id,
                "title" => $role->title,
                "abbreviation" => $role->abbreviation,
                "admin" => $role->admin,
                ),
                "team" => array(
                "id" => $team->id,
                "title" => $team->title,
                "abbreviation" => $team->abbreviation
                ),
                )
                );

                $jwt = JWT::encode($token, $token_conf['secret']);
                if(setAuth($jwt, $token_conf['expireAt'], $api_conf['cookie'])){
                    returnSuccess();
                }

            }
        }
    }
}

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}
