<?php

    // Application Params
    error_reporting(E_ALL);
    //error_reporting(0); <-- to deactivate
    date_default_timezone_set('Europe/Zurich');

    $token_conf = array(
        "secret" => 'asdffae@hjk4352[bnbnmv]lkjhgfr:334',
        "algorithm" => array('HS256'),
        "issuer" => 'Pelan Application',
        "issuedAt" => time(),
        "notBefore" => time(),
        "expireAt" => time() + (60*60),
    );

    function returnSuccess($data = false){
        if($data){
            http_response_code(200);
            echo json_encode(array(
                "status" => "success",
                "message" => "Request successfully handled",
                "content" => $data
            ));
        } else {
            http_response_code(200);
            echo json_encode(array(
                "status" => "success",
                "message" => "Request successfully handled (Returning no content)"
            ));
        }
    }

    function returnNoData(){
        http_response_code(204);
        echo json_encode(array(
            "status" => "success",
            "message" => "Request successfully handled but no data found"
        ));
    }

    function returnForbidden($reason = false){
        if($reason){
            http_response_code(403);
            echo json_encode(array(
                "status" => "unauthorized",
                "message" => "User is not authorized to perform this action"
            ));
        } else {
            http_response_code(403);
            echo json_encode(array(
                "status" => "unauthorized",
                "message" => "User is not authorized to perform this action",
                "reason" => $reason
            ));
        }
    }

    function returnBadRequest(){
        http_response_code(400);
        echo json_encode(array(
            "status" => "failed",
            "message" => "Bad Request: Values are wrong or missing.",
        ));
    }

    function returnError($reason = false){
        if($reason){
            http_response_code(500);
            echo json_encode(array(
                "status" => "error",
                "message" => "An internal error occured",
                "reason" => $reason
            ));
        } else {
            http_response_code(500);
            echo json_encode(array(
                "status" => "error",
                "message" => "An internal error occured",
            ));
        }
    }



?>
