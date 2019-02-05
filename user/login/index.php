<?php

// ---- Include Defaults
include_once '../../_config/headers.php';
include_once '../../_config/database.php';
include_once '../../_config/core.php';
include_once '../../_config/libraries/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libraries/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libraries/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libraries/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// ---- Initialize Default
$database = new Database();
$db = $database->connect();
$data = json_decode(file_get_contents("php://input"));

// ---- Include Object
include_once '../../_config/objects/user.php';
include_once '../../_config/objects/team.php';
include_once '../../_config/objects/role.php';
$user = new User($db);
// ---- End of default Configuration

$user->email = $data->email;
$user_exists = $user->userExists();

if($user_exists && password_verify($data->identifier, $user->identifier)){

    $team = new Team($db);
    $team->id = $user->team;

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
            returnSuccess($jwt);

        }
    }

} else {

    returnNoData('User not existing');

}
?>
