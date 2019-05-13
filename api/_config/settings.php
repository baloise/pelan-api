<?php

// Application Params
error_reporting(E_ALL);
//error_reporting(0); <-- to deactivate
date_default_timezone_set('Europe/Zurich');

$api_conf = array(
    "environment" => "testMedusa", // 'test', 'testMedusa', 'demo', 'prod'
    "corsOrigins" => array(
        "http://localhost:8080",
        "https://pelan.osis.io"
    ),
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
    "expireAt" => time() + (3*60*60) // 3*60*60=3H; 15*60=15Min
);

$db_conf = array(
    "host" => "localhost",
    "database" => "pelan",
    "user" => "root",
    "pass" => "",
);
